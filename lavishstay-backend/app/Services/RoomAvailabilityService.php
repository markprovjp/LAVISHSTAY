<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomAvailability;
use App\Models\RoomPricing;
use App\Models\Event;
use App\Models\Holiday;
use App\Models\DynamicPricingRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RoomAvailabilityService
{
    public function getAvailableRoomsWithPricing(
        string $checkInDate,
        string $checkOutDate,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        
        // 1. Tìm phòng trống
        $availableRooms = $this->findAvailableRooms($checkIn, $checkOut, $guestCount, $roomTypeId);
        
        // 2. Tính giá cho từng phòng
        $roomsWithPricing = $availableRooms->map(function ($room) use ($checkIn, $checkOut) {
            $pricingDetails = $this->calculateRoomPricing($room, $checkIn, $checkOut);
            
            return [
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => [
                    'id' => $room->roomType->id,
                    'name' => $room->roomType->name,
                    'description' => $room->roomType->description,
                    'max_guests' => $room->max_guests,
                    'size' => $room->roomType->size,
                    'bed_type' => $room->roomType->bed_type,
                    'images' => $room->roomType->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'image_url' => $image->image_url,
                            'is_main' => $image->is_main
                        ];
                    })
                ],
                'amenities' => $room->roomType->amenities->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                        'is_highlighted' => $amenity->pivot->is_highlighted ?? false
                    ];
                }),
                'pricing' => $pricingDetails,
                'policies' => [
                    'cancellation_policy' => $room->roomType->cancellation_policy,
                    'deposit_policy' => $room->roomType->deposit_policy,
                    'check_in_time' => $room->roomType->check_in_time,
                    'check_out_time' => $room->roomType->check_out_time
                ]
            ];
        });

        return $roomsWithPricing;
    }

    private function findAvailableRooms(
        Carbon $checkIn,
        Carbon $checkOut,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        $query = Room::with(['roomType.amenities', 'roomType.images', 'roomOptions'])
            ->whereHas('roomType', function ($q) use ($guestCount) {
                $q->where('max_guests', '>=', $guestCount);
            });

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        $rooms = $query->get();

        // Lọc phòng có sẵn trong khoảng thời gian
        return $rooms->filter(function ($room) use ($checkIn, $checkOut) {
            return $this->isRoomAvailable($room, $checkIn, $checkOut);
        });
    }

    private function isRoomAvailable(Room $room, Carbon $checkIn, Carbon $checkOut): bool
    {
        // Kiểm tra từng ngày trong khoảng thời gian
        $currentDate = $checkIn->copy();
        
        while ($currentDate->lt($checkOut)) {
            $availability = RoomAvailability::where('availability_id', $room->id)
                ->whereDate('date', $currentDate)
                ->first();

            if (!$availability || $availability->available_rooms <= 0) {
                return false;
            }
            
            $currentDate->addDay();
        }

        return true;
    }

    private function calculateRoomPricing(Room $room, Carbon $checkIn, Carbon $checkOut): array
    {
        $totalPrice = 0;
        $dailyPrices = [];
        $currentDate = $checkIn->copy();
        $nights = $checkIn->diffInDays($checkOut);

        while ($currentDate->lt($checkOut)) {
            $dailyPrice = $this->getDailyPrice($room, $currentDate);
            $dailyPrices[] = [
                'date' => $currentDate->format('Y-m-d'),
                'price' => $dailyPrice,
                'is_weekend' => $currentDate->isWeekend(),
                'special_event' => $this->getSpecialEvent($currentDate),
                'holiday' => $this->getHoliday($currentDate)
            ];
            
            $totalPrice += $dailyPrice;
            $currentDate->addDay();
        }

        // Áp dụng dynamic pricing nếu có
        $dynamicPriceAdjustment = $this->applyDynamicPricing($room, $checkIn, $checkOut, $totalPrice);

        return [
            'base_total_price' => $totalPrice,
            'dynamic_adjustment' => $dynamicPriceAdjustment,
            'final_total_price' => $totalPrice + $dynamicPriceAdjustment,
            'price_per_night' => round(($totalPrice + $dynamicPriceAdjustment) / $nights, 2),
            'nights' => $nights,
            'daily_breakdown' => $dailyPrices,
            'currency' => 'VND'
        ];
    }

    private function getDailyPrice(Room $room, Carbon $date): float
    {
        // Tìm giá theo thứ tự ưu tiên: Event > Holiday > Weekend > Base price
        
        // 1. Kiểm tra giá sự kiện
        $eventPrice = RoomPricing::where('room_id', $room->id)
            ->whereNotNull('event_id')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($eventPrice) {
            return $eventPrice->price_vnd;
        }

        // 2. Kiểm tra giá ngày lễ
        $holidayPrice = RoomPricing::where('room_id', $room->id)
            ->whereNotNull('holiday_id')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($holidayPrice) {
            return $holidayPrice->price_vnd;
        }

        // 3. Kiểm tra giá cuối tuần
        if ($date->isWeekend()) {
            $weekendPrice = RoomPricing::where('room_id', $room->id)
                ->where('is_weekend', true)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            if ($weekendPrice) {
                return $weekendPrice->price_vnd;
            }
        }

        // 4. Giá cơ bản
        $basePrice = RoomPricing::where('room_id', $room->id)
            ->whereNull('event_id')
            ->whereNull('holiday_id')
            ->where('is_weekend', false)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        return $basePrice ? $basePrice->price_vnd : 0;
    }

    private function applyDynamicPricing(Room $room, Carbon $checkIn, Carbon $checkOut, float $basePrice): float
    {
        // Tính tỷ lệ lấp đầy phòng trong khoảng thời gian
        $occupancyRate = $this->calculateOccupancyRate($room->roomType, $checkIn, $checkOut);
        
        // Tìm rule dynamic pricing phù hợp
        $dynamicRule = DynamicPricingRule::where('room_type_id', $room->room_type_id)
            ->where('occupancy_threshold', '<=', $occupancyRate)
            ->orderBy('occupancy_threshold', 'desc')
            ->first();

        if (!$dynamicRule) {
            return 0;
        }

        if ($dynamicRule->adjustment_type === 'percentage') {
            return $basePrice * ($dynamicRule->adjustment_value / 100);
        } else {
            return $dynamicRule->adjustment_value;
        }
    }

    private function calculateOccupancyRate($roomType, Carbon $checkIn, Carbon $checkOut): float
    {
        $totalRooms = Room::where('room_type_id', $roomType->id)->count();
        if ($totalRooms === 0) return 0;

        $currentDate = $checkIn->copy();
        $totalAvailableRooms = 0;
        $days = 0;

        while ($currentDate->lt($checkOut)) {
            $availableRooms = RoomAvailability::whereHas('room', function ($q) use ($roomType) {
                $q->where('room_type_id', $roomType->id);
            })
            ->whereDate('date', $currentDate)
            ->sum('available_rooms');

            $totalAvailableRooms += $availableRooms;
            $days++;
            $currentDate->addDay();
        }

        $averageAvailableRooms = $days > 0 ? $totalAvailableRooms / $days : $totalRooms;
        $occupancyRate = (($totalRooms - $averageAvailableRooms) / $totalRooms) * 100;

        return max(0, min(100, $occupancyRate));
    }

    private function getSpecialEvent(Carbon $date): ?array
    {
        $event = Event::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        return $event ? [
            'id' => $event->id,
            'name' => $event->name,
            'description' => $event->description
        ] : null;
    }

    private function getHoliday(Carbon $date): ?array
    {
        $holiday = Holiday::whereDate('date', $date)->first();

        return $holiday ? [
            'id' => $holiday->id,
            'name' => $holiday->name,
            'description' => $holiday->description
        ] : null;
    }
}
