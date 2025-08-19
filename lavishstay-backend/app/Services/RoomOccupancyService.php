<?php

namespace App\Services;

use App\Models\RoomOccupancy;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RoomOccupancyService
{
    /**
     * Calculate and update room occupancy for all room types for a specific date
     * This is the main method that runs daily at 00:01 AM
     * 
     * @param Carbon|string|null $date
     * @return array
     */
    public function calculateDailyOccupancy($date = null)
    {
        try {
            $targetDate = $date ? Carbon::parse($date) : Carbon::today();
            
            Log::info("Starting daily room occupancy calculation for date: {$targetDate->toDateString()}");

            DB::beginTransaction();

            $results = [];
            $roomTypes = RoomType::all();

            foreach ($roomTypes as $roomType) {
                try {
                    $result = $this->calculateOccupancyForRoomType($roomType, $targetDate);
                    $results[] = $result;
                    
                    Log::info("Calculated occupancy for room type {$roomType->room_type_id}", [
                        'room_type_name' => $roomType->name,
                        'total_rooms' => $result['total_rooms'],
                        'booked_rooms' => $result['booked_rooms'],
                        'occupancy_rate' => $result['occupancy_rate']
                    ]);
                    
                } catch (Exception $e) {
                    Log::error("Error calculating occupancy for room type {$roomType->room_type_id}: " . $e->getMessage());
                    throw $e;
                }
            }

            DB::commit();

            Log::info("Successfully completed daily occupancy calculation for {$targetDate->toDateString()}", [
                'total_room_types' => count($results),
                'date' => $targetDate->toDateString()
            ]);

            return [
                'success' => true,
                'date' => $targetDate->toDateString(),
                'results' => $results,
                'summary' => $this->generateSummary($results)
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error("Failed to calculate daily occupancy for date: " . ($date ?? 'today'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'date' => $targetDate->toDateString() ?? null
            ];
        }
    }

    /**
     * Calculate occupancy for a specific room type and date
     * 
     * @param RoomType $roomType
     * @param Carbon $date
     * @return array
     */
    private function calculateOccupancyForRoomType(RoomType $roomType, Carbon $date)
    {
        try {
            // Get total rooms for this room type from room_types table
            $totalRooms = $roomType->total_room ?? 0;

            if ($totalRooms == 0) {
                Log::warning("Room type {$roomType->room_type_id} has 0 total rooms");
            }

            // Calculate booked rooms for this date
            $bookedRooms = $this->calculateBookedRooms($roomType->room_type_id, $date);

            // Ensure booked_rooms doesn't exceed total_rooms
            $bookedRooms = min($bookedRooms, $totalRooms);

            // Create or update the room_occupancy record
            $occupancy = RoomOccupancy::updateOrCreate(
                [
                    'room_type_id' => $roomType->room_type_id,
                    'date' => $date->toDateString()
                ],
                [
                    'total_rooms' => $totalRooms,
                    'booked_rooms' => $bookedRooms,
                    // occupancy_rate is auto-calculated by database
                    'updated_at' => now()
                ]
            );

            // Get the calculated occupancy_rate from database
            $occupancy->refresh();
            $occupancyRate = $occupancy->occupancy_rate ?? 0;

            return [
                'occupancy_id' => $occupancy->occupancy_id,
                'room_type_id' => $roomType->room_type_id,
                'room_type_name' => $roomType->name,
                'date' => $date->toDateString(),
                'total_rooms' => $totalRooms,
                'booked_rooms' => $bookedRooms,
                'available_rooms' => max(0, $totalRooms - $bookedRooms),
                'occupancy_rate' => $occupancyRate
            ];

        } catch (Exception $e) {
            Log::error("Error in calculateOccupancyForRoomType for room type {$roomType->room_type_id}: " . $e->getMessage());
            throw new Exception("Failed to calculate occupancy for room type {$roomType->room_type_id}: " . $e->getMessage());
        }
    }

    /**
     * Calculate booked rooms for specific room type and date
     * Logic: Rooms that are occupied on the target date
     * - Bookings with check_in <= target_date AND check_out > target_date
     * 
     * @param int $roomTypeId
     * @param Carbon $date
     * @return int
     */
    private function calculateBookedRooms($roomTypeId, Carbon $date)
    {
        try {
            // Count rooms that are occupied on the target date
            // This includes:
            // 1. Guests checking in today
            // 2. Guests who are staying through today (checked in before, checking out after)
            
            $bookedRooms = DB::table('booking')
                ->join('booking_rooms', 'booking.booking_id', '=', 'booking_rooms.booking_id')
                ->join('room', 'booking_rooms.room_id', '=', 'room.room_id')
                ->where('room.room_type_id', $roomTypeId)
                ->whereIn('booking.status', ['confirmed', 'operational']) // Active bookings
                ->where('booking.check_in_date', '<=', $date->toDateString())
                ->where('booking.check_out_date', '>', $date->toDateString())
                ->count('booking_rooms.id'); // Count individual room bookings

            Log::debug("Calculated booked rooms for room type {$roomTypeId} on {$date->toDateString()}: {$bookedRooms}");

            return $bookedRooms;

        } catch (Exception $e) {
            Log::error("Error calculating booked rooms for room type {$roomTypeId}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update occupancy when a same-day booking is made
     * This method should be called from PaymentController after successful booking
     * 
     * @param int $roomTypeId
     * @param int $roomsCount
     * @param Carbon|string|null $date
     * @return bool
     */
    public function updateOccupancyForSameDayBooking($roomTypeId, $roomsCount = 1, $date = null)
    {
        try {
            $targetDate = $date ? Carbon::parse($date) : Carbon::today();
            
            Log::info("Updating occupancy for same-day booking", [
                'room_type_id' => $roomTypeId,
                'rooms_count' => $roomsCount,
                'date' => $targetDate->toDateString()
            ]);

            DB::beginTransaction();

            // Find existing occupancy record for today
            $occupancy = RoomOccupancy::where('room_type_id', $roomTypeId)
                ->where('date', $targetDate->toDateString())
                ->first();

            if ($occupancy) {
                // Update existing record
                $newBookedRooms = min($occupancy->booked_rooms + $roomsCount, $occupancy->total_rooms);
                
                $occupancy->update([
                    'booked_rooms' => $newBookedRooms,
                    'updated_at' => now()
                ]);

                Log::info("Updated existing occupancy record", [
                    'occupancy_id' => $occupancy->occupancy_id,
                    'old_booked_rooms' => $occupancy->booked_rooms - $roomsCount,
                    'new_booked_rooms' => $newBookedRooms
                ]);
            } else {
                // Create new record if doesn't exist (shouldn't happen if daily job runs properly)
                $roomType = RoomType::find($roomTypeId);
                if ($roomType) {
                    $this->calculateOccupancyForRoomType($roomType, $targetDate);
                    Log::info("Created new occupancy record for same-day booking");
                }
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error("Failed to update occupancy for same-day booking: " . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'rooms_count' => $roomsCount,
                'date' => $targetDate->toDateString() ?? null
            ]);

            return false;
        }
    }

    /**
     * Get current occupancy data for all room types
     * 
     * @param Carbon|string|null $date
     * @return array
     */
    public function getCurrentOccupancy($date = null)
    {
        try {
            $targetDate = $date ? Carbon::parse($date) : Carbon::today();
            
            $occupancies = RoomOccupancy::with('roomType')
                ->where('date', $targetDate->toDateString())
                ->get();

            // If no records exist for the date, calculate them
            if ($occupancies->isEmpty()) {
                Log::info("No occupancy records found for {$targetDate->toDateString()}, calculating now...");
                $this->calculateDailyOccupancy($targetDate);
                $occupancies = RoomOccupancy::with('roomType')->where('date', $targetDate->toDateString())->get();
            }

            return $occupancies->map(function ($occupancy) {
                return [
                    'occupancy_id' => $occupancy->occupancy_id,
                    'room_type_id' => $occupancy->room_type_id,
                    'room_type_name' => $occupancy->roomType->name ?? 'Unknown',
                    'date' => $occupancy->date,
                    'total_rooms' => $occupancy->total_rooms,
                    'booked_rooms' => $occupancy->booked_rooms,
                    'available_rooms' => $occupancy->total_rooms - $occupancy->booked_rooms,
                    'occupancy_rate' => $occupancy->occupancy_rate
                ];
            })->toArray();

        } catch (Exception $e) {
            Log::error("Error getting current occupancy: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get occupancy statistics for reporting
     * 
     * @param Carbon|string|null $startDate
     * @param Carbon|string|null $endDate
     * @return array
     */
    public function getOccupancyStatistics($startDate = null, $endDate = null)
    {
        try {
            $start = $startDate ? Carbon::parse($startDate) : Carbon::today()->subDays(7);
            $end = $endDate ? Carbon::parse($endDate) : Carbon::today();

            $occupancies = RoomOccupancy::with('roomType')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('date', 'desc')
                ->get();

            $totalRooms = $occupancies->sum('total_rooms');
            $totalBooked = $occupancies->sum('booked_rooms');
            $averageOccupancy = $occupancies->avg('occupancy_rate');

            return [
                'period' => [
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'days' => $start->diffInDays($end) + 1
                ],
                'summary' => [
                    'total_rooms' => $totalRooms,
                    'total_booked' => $totalBooked,
                    'total_available' => $totalRooms - $totalBooked,
                    'average_occupancy_rate' => round($averageOccupancy, 2),
                    'records_count' => $occupancies->count()
                ],
                'by_room_type' => $occupancies->groupBy('room_type_id')->map(function ($group) {
                    $first = $group->first();
                    return [
                        'room_type_id' => $first->room_type_id,
                        'room_type_name' => $first->roomType->name ?? 'Unknown',
                        'average_occupancy_rate' => round($group->avg('occupancy_rate'), 2),
                        'max_occupancy_rate' => $group->max('occupancy_rate'),
                        'min_occupancy_rate' => $group->min('occupancy_rate'),
                        'total_records' => $group->count()
                    ];
                })->values()
            ];

        } catch (Exception $e) {
            Log::error("Error getting occupancy statistics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate summary from calculation results
     * 
     * @param array $results
     * @return array
     */
    private function generateSummary(array $results)
    {
        $totalRooms = array_sum(array_column($results, 'total_rooms'));
        $totalBooked = array_sum(array_column($results, 'booked_rooms'));
        $totalAvailable = $totalRooms - $totalBooked;

        return [
            'total_rooms' => $totalRooms,
            'total_booked' => $totalBooked,
            'total_available' => $totalAvailable,
            'overall_occupancy_rate' => $totalRooms > 0 ? round(($totalBooked / $totalRooms) * 100, 2) : 0,
            'room_types_processed' => count($results)
        ];
    }

    /**
     * Clean up old occupancy records
     * Keep records for analysis and reporting
     * 
     * @param int $daysToKeep
     * @return int
     */
    public function cleanupOldRecords($daysToKeep = 365)
    {
        try {
            $cutoffDate = Carbon::now()->subDays($daysToKeep);
            
            $deletedCount = RoomOccupancy::where('date', '<', $cutoffDate)->delete();
            
            Log::info("Cleaned up old room_occupancy records", [
                'cutoff_date' => $cutoffDate->toDateString(),
                'deleted_count' => $deletedCount,
                'days_kept' => $daysToKeep
            ]);

            return $deletedCount;

        } catch (Exception $e) {
            Log::error("Error cleaning up old room_occupancy records: " . $e->getMessage());
            return 0;
        }
    }
}