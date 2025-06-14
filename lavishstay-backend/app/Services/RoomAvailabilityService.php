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
        try {
            \Log::info('Starting room availability search', [
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'guest_count' => $guestCount,
                'room_type_id' => $roomTypeId
            ]);

            $checkIn = Carbon::parse($checkInDate);
            $checkOut = Carbon::parse($checkOutDate);
            
            // 1. Test tìm phòng trống
            $availableRooms = $this->findAvailableRooms($checkIn, $checkOut, $guestCount, $roomTypeId);
            
            \Log::info('Found available rooms', ['count' => $availableRooms->count()]);

            // 2. Nếu không có phòng trống, return empty
            if ($availableRooms->isEmpty()) {
                return collect([]);
            }

            // 3. Tính giá cho từng phòng (đơn giản hóa trước)
            $roomsWithPricing = $availableRooms->map(function ($room) use ($checkIn, $checkOut) {
                return [
                    'room_id' => $room->room_id,
                    'name' => $room->name,
                    'room_type' => [
                        'room_type_id' => $room->roomType->room_type_id,
                        'name' => $room->roomType->name,
                        'description' => $room->roomType->description ?? '',
                        'max_guests' => $room->max_guests,
                    ],
                    'pricing' => [
                        'base_total_price' => 1000000, // Test giá cố định
                        'final_total_price' => 1000000,
                        'currency' => 'VND'
                    ]
                ];
            });

            return $roomsWithPricing;

        } catch (\Exception $e) {
            \Log::error('Error in getAvailableRoomsWithPricing', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    private function findAvailableRooms(
        Carbon $checkIn,
        Carbon $checkOut,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        try {
            // Đơn giản hóa query trước
            $query = Room::with(['roomType']);

            // Kiểm tra room_type tồn tại
            if ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId);
            }

            $rooms = $query->get();
            
            \Log::info('Total rooms found', ['count' => $rooms->count()]);

            // Tạm thời return tất cả rooms để test
            return $rooms;

        } catch (\Exception $e) {
            \Log::error('Error in findAvailableRooms', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function isRoomAvailable(Room $room, Carbon $checkIn, Carbon $checkOut): bool
    {
        // Kiểm tra từng ngày trong khoảng thời gian
        $currentDate = $checkIn->copy();
        
        while ($currentDate->lt($checkOut)) {
            $availability = RoomAvailability::where('room_id', $room->id)
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
