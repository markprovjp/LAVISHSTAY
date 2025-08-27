<?php

namespace App\Services;

use App\Models\RoomOccupancy;
use App\Models\RoomType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RoomOccupancyService
{
    /**
     * Get current occupancy rate for a specific room type
     */
    public function getCurrentOccupancyRate(int $roomTypeId, Carbon $date = null): float
    {
        $date = $date ?? Carbon::today();
        
        try {
            $occupancy = RoomOccupancy::where('room_type_id', $roomTypeId)
                ->where('date', $date->format('Y-m-d'))
                ->first();

            if (!$occupancy) {
                // If no record exists, create one
                $this->updateRoomOccupancy($roomTypeId, $date);
                
                // Try to get the record again
                $occupancy = RoomOccupancy::where('room_type_id', $roomTypeId)
                    ->where('date', $date->format('Y-m-d'))
                    ->first();
            }

            return $occupancy ? (float) $occupancy->occupancy_rate : 0.0;

        } catch (\Exception $e) {
            Log::error("Error getting occupancy rate", [
                'room_type_id' => $roomTypeId,
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return 0.0;
        }
    }

    /**
     * Update room occupancy for a specific room type and date
     */
    public function updateRoomOccupancy(int $roomTypeId, Carbon $date = null): bool
    {
        $date = $date ?? Carbon::today();
        
        try {
            $roomType = RoomType::find($roomTypeId);
            if (!$roomType) {
                throw new \Exception("Room type not found: {$roomTypeId}");
            }

            $totalRooms = (int) $roomType->total_room;
            $bookedRooms = $this->calculateBookedRooms($roomTypeId, $date);
            
            // Ensure booked rooms doesn't exceed total rooms
            $bookedRooms = min($bookedRooms, $totalRooms);

            RoomOccupancy::updateOrCreate(
                [
                    'room_type_id' => $roomTypeId,
                    'date' => $date->format('Y-m-d')
                ],
                [
                    'total_rooms' => $totalRooms,
                    'booked_rooms' => $bookedRooms
                ]
            );

            // Clear cache for this room type
            $this->clearOccupancyCache($roomTypeId, $date);

            return true;

        } catch (\Exception $e) {
            Log::error("Error updating room occupancy", [
                'room_type_id' => $roomTypeId,
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Update occupancy for all room types for a specific date
     */
    public function updateAllRoomOccupancy(Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        $results = [];
        
        $roomTypes = RoomType::all();
        
        foreach ($roomTypes as $roomType) {
            $success = $this->updateRoomOccupancy($roomType->room_type_id, $date);
            $results[$roomType->room_type_id] = [
                'room_type_name' => $roomType->name,
                'success' => $success
            ];
        }
        
        return $results;
    }

    /**
     * Calculate booked rooms for a specific room type and date
     */
    private function calculateBookedRooms(int $roomTypeId, Carbon $date): int
    {
        $cacheKey = "booked_rooms_{$roomTypeId}_{$date->format('Y-m-d')}";
        
        return Cache::remember($cacheKey, 300, function () use ($roomTypeId, $date) { // Cache for 5 minutes
            try {
                // Count unique rooms that are booked for the target date
                $bookedRooms = DB::table('bookings')
                    ->join('booking_rooms', 'bookings.booking_id', '=', 'booking_rooms.booking_id')
                    ->join('rooms', 'booking_rooms.room_id', '=', 'rooms.room_id')
                    ->where('rooms.room_type_id', $roomTypeId)
                    ->where('bookings.status', 'confirmed')
                    ->where('bookings.check_in_date', '<=', $date->format('Y-m-d'))
                    ->where('bookings.check_out_date', '>', $date->format('Y-m-d'))
                    ->distinct('booking_rooms.room_id')
                    ->count('booking_rooms.room_id');

                return (int) $bookedRooms;

            } catch (\Exception $e) {
                Log::error("Error calculating booked rooms", [
                    'room_type_id' => $roomTypeId,
                    'date' => $date->format('Y-m-d'),
                    'error' => $e->getMessage()
                ]);
                
                return 0;
            }
        });
    }

    /**
     * Get occupancy statistics for all room types
     */
    public function getOccupancyStats(Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        
        try {
            $occupancies = RoomOccupancy::with('roomType')
                ->where('date', $date->format('Y-m-d'))
                ->get();

            $stats = [];
            
            foreach ($occupancies as $occupancy) {
                if (!$occupancy->roomType) continue;
                
                $occupancyRate = (float) $occupancy->occupancy_rate;
                $availableRooms = $occupancy->total_rooms - $occupancy->booked_rooms;
                
                $stats[] = [
                    'room_type_id' => $occupancy->room_type_id,
                    'room_type_name' => $occupancy->roomType->name,
                    'total_rooms' => $occupancy->total_rooms,
                    'booked_rooms' => $occupancy->booked_rooms,
                    'available_rooms' => $availableRooms,
                    'occupancy_rate' => $occupancyRate,
                    'status' => $this->getOccupancyStatus($occupancyRate),
                    'date' => $occupancy->date
                ];
            }
            
            return $stats;

        } catch (\Exception $e) {
            Log::error("Error getting occupancy stats", [
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Get occupancy status based on rate
     */
    private function getOccupancyStatus(float $occupancyRate): string
    {
        if ($occupancyRate >= 90) {
            return 'Đầy';
        } elseif ($occupancyRate >= 75) {
            return 'Cao';
        } elseif ($occupancyRate >= 50) {
            return 'Trung bình';
        } elseif ($occupancyRate >= 25) {
            return 'Thấp';
        } else {
            return 'Rất thấp';
        }
    }

    /**
     * Clear occupancy cache for a specific room type and date
     */
    private function clearOccupancyCache(int $roomTypeId, Carbon $date): void
    {
        $cacheKey = "booked_rooms_{$roomTypeId}_{$date->format('Y-m-d')}";
        Cache::forget($cacheKey);
    }

    /**
     * Get available rooms for a specific room type and date range
     */
    public function getAvailableRooms(int $roomTypeId, Carbon $checkIn, Carbon $checkOut): int
    {
        try {
            $roomType = RoomType::find($roomTypeId);
            if (!$roomType) {
                return 0;
            }

            $totalRooms = (int) $roomType->total_room;
            
            // Find the maximum booked rooms across the date range
            $maxBookedRooms = 0;
            $currentDate = $checkIn->copy();
            
            while ($currentDate->lt($checkOut)) {
                $bookedRooms = $this->calculateBookedRooms($roomTypeId, $currentDate);
                $maxBookedRooms = max($maxBookedRooms, $bookedRooms);
                $currentDate->addDay();
            }
            
            return max(0, $totalRooms - $maxBookedRooms);

        } catch (\Exception $e) {
            Log::error("Error getting available rooms", [
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Trigger real-time occupancy update when booking status changes
     */
    public function onBookingStatusChanged(int $bookingId): void
    {
        try {
            $booking = Booking::with('bookingRooms.room.roomType')->find($bookingId);
            if (!$booking) return;

            // Get affected room types
            $roomTypeIds = $booking->bookingRooms
                ->pluck('room.room_type_id')
                ->unique()
                ->filter();

            // Update occupancy for each affected room type
            $checkIn = Carbon::parse($booking->check_in_date);
            $checkOut = Carbon::parse($booking->check_out_date);
            $currentDate = $checkIn->copy();

            while ($currentDate->lt($checkOut)) {
                foreach ($roomTypeIds as $roomTypeId) {
                    $this->updateRoomOccupancy($roomTypeId, $currentDate);
                }
                $currentDate->addDay();
            }

        } catch (\Exception $e) {
            Log::error("Error updating occupancy after booking status change", [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update occupancy specifically for same-day booking flow.
     * Kept as a thin wrapper to reuse existing updateRoomOccupancy logic.
     *
     * @param int $roomTypeId
     * @param int $roomsCount
     * @param string|\Carbon\Carbon $date
     * @return bool
     */
    public function updateOccupancyForSameDayBooking(int $roomTypeId, int $roomsCount, $date): bool
    {
        try {
            $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

            // Use the full recalculation to ensure accurate counts (handles concurrency)
            return $this->updateRoomOccupancy($roomTypeId, $carbonDate);

        } catch (\Exception $e) {
            Log::error('Error in updateOccupancyForSameDayBooking', [
                'room_type_id' => $roomTypeId,
                'rooms_count' => $roomsCount,
                'date' => is_string($date) ? $date : $date->toDateString(),
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}