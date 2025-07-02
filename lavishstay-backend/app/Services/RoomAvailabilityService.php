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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomAvailabilityService
{
    public function getAvailableRoomsWithPricing(
        string $checkInDate,
        string $checkOutDate,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        try {
            $checkIn = Carbon::parse($checkInDate);
            $checkOut = Carbon::parse($checkOutDate);
            
            Log::info('RoomAvailabilityService - getAvailableRoomsWithPricing called', [
                'checkIn' => $checkIn->format('Y-m-d'),
                'checkOut' => $checkOut->format('Y-m-d'),
                'guestCount' => $guestCount,
                'roomTypeId' => $roomTypeId
            ]);
            
            // 1. Tìm phòng trống
            $availableRooms = $this->findAvailableRooms($checkIn, $checkOut, $guestCount, $roomTypeId);
            
            Log::info('Available rooms found: ' . $availableRooms->count());
            
            // 2. Tính giá cho từng phòng
            $roomsWithPricing = $availableRooms->map(function ($room) use ($checkIn, $checkOut) {
                try {
                    $pricingDetails = $this->calculateRoomPricing($room, $checkIn, $checkOut);
                    
                    return [
                        'room_id' => $room->id,
                        'room_number' => $room->room_number,
                        'room_type' => [
                            'id' => $room->roomType->id,
                            'name' => $room->roomType->name,
                            'description' => $room->roomType->description,
                            'max_guests' => $room->max_guests,
                            'size' => $room->roomType->size ?? $room->roomType->room_area,
                            'bed_type' => $room->roomType->bed_type ?? 'N/A',
                            'images' => $room->roomType->images ? $room->roomType->images->map(function ($image) {
                                return [
                                    'id' => $image->id,
                                    'image_path' => $image->image_path,
                                    'is_main' => $image->is_main
                                ];
                            }) : collect([])
                        ],
                        'amenities' => $room->roomType->amenities ? $room->roomType->amenities->map(function ($amenity) {
                            return [
                                'id' => $amenity->id,
                                'name' => $amenity->name,
                                'icon' => $amenity->icon,
                                'is_highlighted' => $amenity->pivot->is_highlighted ?? false
                            ];
                        }) : collect([]),
                        'pricing' => $pricingDetails,
                        'policies' => [
                            'cancellation_policy' => $room->roomType->cancellation_policy ?? 'N/A',
                            'deposit_policy' => $room->roomType->deposit_policy ?? 'N/A',
                            'check_in_time' => $room->roomType->check_in_time ?? '14:00',
                            'check_out_time' => $room->roomType->check_out_time ?? '12:00'
                        ]
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing room ' . $room->id . ': ' . $e->getMessage());
                    return null;
                }
            })->filter(); // Remove null values

            return $roomsWithPricing;
            
        } catch (\Exception $e) {
            Log::error('Error in getAvailableRoomsWithPricing: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return collect([]);
        }
    }

    private function findAvailableRooms(
        Carbon $checkIn,
        Carbon $checkOut,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        try {
            Log::info('Finding available rooms...');
            
            $query = Room::with(['roomType.amenities', 'roomType.images'])
                ->whereHas('roomType', function ($q) use ($guestCount) {
                    $q->where('max_guests', '>=', $guestCount);
                });

            if ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId);
            }

            // Add status filter if column exists
            $roomColumns = DB::select("SHOW COLUMNS FROM " . (new Room())->getTable() . " LIKE 'status'");
            if (!empty($roomColumns)) {
                $query->where('status', 'available');
            }

            $rooms = $query->get();
            Log::info('Total rooms before availability check: ' . $rooms->count());

            // Lọc phòng có sẵn trong khoảng thời gian
            $availableRooms = $rooms->filter(function ($room) use ($checkIn, $checkOut) {
                return $this->isRoomAvailable($room, $checkIn, $checkOut);
            });

            Log::info('Available rooms after filtering: ' . $availableRooms->count());
            
            return $availableRooms;
            
        } catch (\Exception $e) {
            Log::error('Error in findAvailableRooms: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function isRoomAvailable(Room $room, Carbon $checkIn, Carbon $checkOut): bool
    {
        try {
            // Kiểm tra xem có bảng RoomAvailability không
            $availabilityTableExists = DB::select("SHOW TABLES LIKE 'room_availability'");
            if (empty($availabilityTableExists)) {
                // Nếu không có bảng availability, kiểm tra booking
                return $this->isRoomAvailableByBooking($room, $checkIn, $checkOut);
            }

            // Kiểm tra từng ngày trong khoảng thời gian
            $currentDate = $checkIn->copy();
            
            while ($currentDate->lt($checkOut)) {
                $availability = RoomAvailability::where('room_id', $room->id) // Sửa từ availability_id thành room_id
                    ->whereDate('date', $currentDate)
                    ->first();

                if (!$availability || $availability->available_rooms <= 0) {
                    return false;
                }
                
                $currentDate->addDay();
            }

            return true;
            
        } catch (\Exception $e) {
            Log::error('Error checking room availability for room ' . $room->id . ': ' . $e->getMessage());
            // Fallback to booking check
            return $this->isRoomAvailableByBooking($room, $checkIn, $checkOut);
        }
    }

    private function isRoomAvailableByBooking(Room $room, Carbon $checkIn, Carbon $checkOut): bool
    {
        try {
            // Kiểm tra xem có bảng booking không
            $bookingTableExists = DB::select("SHOW TABLES LIKE 'booking'");
            $bookingRoomsTableExists = DB::select("SHOW TABLES LIKE 'booking_rooms'");
            
            if (empty($bookingTableExists) || empty($bookingRoomsTableExists)) {
                // Nếu không có bảng booking, coi như phòng available
                return true;
            }

            // Kiểm tra xem phòng có bị đặt trong khoảng thời gian không
            $conflictingBookings = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->where('br.room_id', $room->id)
                ->whereIn('b.status', ['pending', 'confirmed'])
                ->where('br.check_in_date', '<', $checkOut->format('Y-m-d'))
                ->where('br.check_out_date', '>', $checkIn->format('Y-m-d'))
                ->count();

            return $conflictingBookings == 0;
            
        } catch (\Exception $e) {
            Log::error('Error checking booking availability: ' . $e->getMessage());
            return true; // Default to available if can't check
        }
    }

    private function calculateRoomPricing(Room $room, Carbon $checkIn, Carbon $checkOut): array
    {
        try {
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
                'price_per_night' => $nights > 0 ? round(($totalPrice + $dynamicPriceAdjustment) / $nights, 2) : 0,
                'nights' => $nights,
                'daily_breakdown' => $dailyPrices,
                'currency' => 'VND'
            ];
            
        } catch (\Exception $e) {
            Log::error('Error calculating pricing: ' . $e->getMessage());
            
            // Fallback pricing
            $nights = $checkIn->diffInDays($checkOut);
            $basePrice = $room->roomType->base_price ?? 0;
            $totalPrice = $basePrice * $nights;
            
            return [
                'base_total_price' => $totalPrice,
                'dynamic_adjustment' => 0,
                'final_total_price' => $totalPrice,
                'price_per_night' => $basePrice,
                'nights' => $nights,
                'daily_breakdown' => [],
                'currency' => 'VND'
            ];
        }
    }

    private function getDailyPrice(Room $room, Carbon $date): float
    {
        try {
            // Kiểm tra xem có bảng RoomPricing không
            $pricingTableExists = DB::select("SHOW TABLES LIKE 'room_pricing'");
            if (empty($pricingTableExists)) {
                // Fallback to base price
                return $room->roomType->base_price ?? 0;
            }

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

            return $basePrice ? $basePrice->price_vnd : ($room->roomType->base_price ?? 0);
            
        } catch (\Exception $e) {
            Log::error('Error getting daily price: ' . $e->getMessage());
            return $room->roomType->base_price ?? 0;
        }
    }

    private function applyDynamicPricing(Room $room, Carbon $checkIn, Carbon $checkOut, float $basePrice): float
    {
        try {
            // Kiểm tra xem có bảng DynamicPricingRule không
            $dynamicTableExists = DB::select("SHOW TABLES LIKE 'dynamic_pricing_rules'");
            if (empty($dynamicTableExists)) {
                return 0;
            }

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
            
        } catch (\Exception $e) {
            Log::error('Error applying dynamic pricing: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateOccupancyRate($roomType, Carbon $checkIn, Carbon $checkOut): float
    {
        try {
            $totalRooms = Room::where('room_type_id', $roomType->id)->count();
            if ($totalRooms === 0) return 0;

            // Kiểm tra xem có bảng RoomAvailability không
            $availabilityTableExists = DB::select("SHOW TABLES LIKE 'room_availability'");
            if (empty($availabilityTableExists)) {
                // Fallback: tính dựa trên booking
                return $this->calculateOccupancyRateByBooking($roomType, $checkIn, $checkOut, $totalRooms);
            }

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
            
        } catch (\Exception $e) {
            Log::error('Error calculating occupancy rate: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateOccupancyRateByBooking($roomType, Carbon $checkIn, Carbon $checkOut, int $totalRooms): float
    {
        try {
            // Kiểm tra bảng booking
            $bookingTableExists = DB::select("SHOW TABLES LIKE 'booking'");
            $bookingRoomsTableExists = DB::select("SHOW TABLES LIKE 'booking_rooms'");
            
            if (empty($bookingTableExists) || empty($bookingRoomsTableExists)) {
                return 0; // No bookings, 0% occupancy
            }

            $currentDate = $checkIn->copy();
            $totalBookedRooms = 0;
            $days = 0;

            while ($currentDate->lt($checkOut)) {
                $bookedRooms = DB::table('booking_rooms as br')
                    ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                    ->join('room as r', 'br.room_id', '=', 'r.room_id')
                    ->where('r.room_type_id', $roomType->id)
                    ->whereIn('b.status', ['pending', 'confirmed'])
                    ->where('br.check_in_date', '<=', $currentDate->format('Y-m-d'))
                    ->where('br.check_out_date', '>', $currentDate->format('Y-m-d'))
                    ->count();

                $totalBookedRooms += $bookedRooms;
                $days++;
                $currentDate->addDay();
            }

            $averageBookedRooms = $days > 0 ? $totalBookedRooms / $days : 0;
            $occupancyRate = ($averageBookedRooms / $totalRooms) * 100;

            return max(0, min(100, $occupancyRate));
            
        } catch (\Exception $e) {
            Log::error('Error calculating occupancy rate by booking: ' . $e->getMessage());
            return 0;
        }
    }

    private function getSpecialEvent(Carbon $date): ?array
    {
        try {
            // Kiểm tra bảng events
            $eventsTableExists = DB::select("SHOW TABLES LIKE 'events'");
            if (empty($eventsTableExists)) {
                return null;
            }

            $event = Event::whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            return $event ? [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description
            ] : null;
            
        } catch (\Exception $e) {
            Log::error('Error getting special event: ' . $e->getMessage());
            return null;
        }
    }

    private function getHoliday(Carbon $date): ?array
    {
        try {
            // Kiểm tra bảng holidays
            $holidaysTableExists = DB::select("SHOW TABLES LIKE 'holidays'");
            if (empty($holidaysTableExists)) {
                return null;
            }

            $holiday = Holiday::whereDate('date', $date)->first();

            return $holiday ? [
                'id' => $holiday->id,
                'name' => $holiday->name,
                'description' => $holiday->description
            ] : null;
            
        } catch (\Exception $e) {
            Log::error('Error getting holiday: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simple method to get available rooms without complex pricing
     */
    public function getSimpleAvailableRooms(
        string $checkInDate,
        string $checkOutDate,
        int $guestCount,
        ?int $roomTypeId = null
    ): Collection {
        try {
            $checkIn = Carbon::parse($checkInDate);
            $checkOut = Carbon::parse($checkOutDate);
            
            Log::info('Getting simple available rooms', [
                'checkIn' => $checkIn->format('Y-m-d'),
                'checkOut' => $checkOut->format('Y-m-d'),
                'guestCount' => $guestCount,
                'roomTypeId' => $roomTypeId
            ]);

            // Build query using DB facade for better error handling
            $query = DB::table('room as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->select([
                    'r.room_id as id',
                    'r.room_number',
                    'r.room_type_id',
                    'rt.name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area',
                    'rt.max_guests',
                    'rt.rating'
                ])
                ->where('rt.max_guests', '>=', $guestCount);

            // Add status filter if column exists
            $roomColumns = DB::select("SHOW COLUMNS FROM room LIKE 'status'");
            if (!empty($roomColumns)) {
                $query->where('r.status', 'available');
            }

            // Add is_active filter if column exists
            $roomTypeColumns = DB::select("SHOW COLUMNS FROM room_types LIKE 'is_active'");
            if (!empty($roomTypeColumns)) {
                $query->where('rt.is_active', 1);
            }

            if ($roomTypeId) {
                $query->where('r.room_type_id', $roomTypeId);
            }

            // Exclude booked rooms
            $bookingTableExists = DB::select("SHOW TABLES LIKE 'booking'");
            $bookingRoomsTableExists = DB::select("SHOW TABLES LIKE 'booking_rooms'");

            if (!empty($bookingTableExists) && !empty($bookingRoomsTableExists)) {
                $bookedRoomIds = DB::table('booking_rooms as br')
                    ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                    ->whereIn('b.status', ['pending', 'confirmed'])
                    ->where('br.check_in_date', '<', $checkOut->format('Y-m-d'))
                    ->where('br.check_out_date', '>', $checkIn->format('Y-m-d'))
                    ->whereNotNull('br.room_id')
                    ->pluck('br.room_id')
                    ->toArray();

                if (!empty($bookedRoomIds)) {
                    $query->whereNotIn('r.room_id', $bookedRoomIds);
                }
            }

            $rooms = $query->get();
            
            Log::info('Simple available rooms found: ' . $rooms->count());

            return collect($rooms)->map(function ($room) use ($checkIn, $checkOut) {
                $nights = $checkIn->diffInDays($checkOut);
                $totalPrice = $room->base_price * $nights;

                return (object) [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_type_id' => $room->room_type_id,
                    'roomType' => (object) [
                        'id' => $room->room_type_id,
                        'name' => $room->name,
                        'description' => $room->description,
                        'base_price' => $room->base_price,
                        'room_area' => $room->room_area,
                        'max_guests' => $room->max_guests,
                        'rating' => $room->rating,
                        'images' => collect([]),
                        'amenities' => collect([])
                    ],
                    'pricing' => [
                        'base_total_price' => $totalPrice,
                        'dynamic_adjustment' => 0,
                        'final_total_price' => $totalPrice,
                        'price_per_night' => $room->base_price,
                        'nights' => $nights,
                        'currency' => 'VND'
                    ]
                ];
            });
            
        } catch (\Exception $e) {
            Log::error('Error in getSimpleAvailableRooms: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return collect([]);
        }
    }
}
