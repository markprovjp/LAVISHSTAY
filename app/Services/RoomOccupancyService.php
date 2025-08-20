<?php

namespace App\Services;

use App\Models\RoomOccupancy;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomOccupancyService
{
    /**
     * Sync occupancy data for a specific date
     */
    public function syncOccupancyForDate($date)
    {
        $date = Carbon::parse($date)->toDateString();
        
        // Get occupancy data for all room types
        $occupancyData = $this->calculateOccupancyFromBookings(null, $date);
        
        // Update or create occupancy records
        foreach ($occupancyData as $roomTypeId => $data) {
            RoomOccupancy::updateOrCreate(
                [
                    'room_type_id' => $roomTypeId,
                    'date' => $date
                ],
                [
                    'total_rooms' => $data['total_rooms'],
                    'booked_rooms' => $data['booked_rooms']
                ]
            );
        }
    }

    /**
     * Calculate occupancy from bookings
     */
    public function calculateOccupancyFromBookings($roomTypeId = null, $date = null)
    {
        $date = $date ?: now()->toDateString();
        
        try {
            // Base query to get room availability data
            $query = DB::table('room')
                ->join('room_option', 'room.room_id', '=', 'room_option.room_id')
                ->join('room_availability', 'room_option.option_id', '=', 'room_availability.option_id')
                ->where('room_availability.date', $date)
                ->select([
                    'room.room_type_id',
                    DB::raw('SUM(room_availability.total_rooms) as total_rooms'),
                    DB::raw('SUM(room_availability.available_rooms) as available_rooms')
                ])
                ->groupBy('room.room_type_id');

            // Filter by room type if specified
            if ($roomTypeId) {
                $query->where('room.room_type_id', $roomTypeId);
            }

            $results = $query->get();

            if ($roomTypeId) {
                // Return single room type data
                $result = $results->first();
                if (!$result) {
                    return [
                        'total_rooms' => 0,
                        'available_rooms' => 0,
                        'booked_rooms' => 0,
                        'occupancy_rate' => 0
                    ];
                }

                $bookedRooms = $result->total_rooms - $result->available_rooms;
                $occupancyRate = $result->total_rooms > 0 ? 
                    round(($bookedRooms / $result->total_rooms) * 100, 2) : 0;

                return [
                    'total_rooms' => $result->total_rooms,
                    'available_rooms' => $result->available_rooms,
                    'booked_rooms' => $bookedRooms,
                    'occupancy_rate' => $occupancyRate
                ];
            } else {
                // Return all room types data
                $data = [];
                foreach ($results as $result) {
                    $bookedRooms = $result->total_rooms - $result->available_rooms;
                    $occupancyRate = $result->total_rooms > 0 ? 
                        round(($bookedRooms / $result->total_rooms) * 100, 2) : 0;

                    $data[$result->room_type_id] = [
                        'total_rooms' => $result->total_rooms,
                        'available_rooms' => $result->available_rooms,
                        'booked_rooms' => $bookedRooms,
                        'occupancy_rate' => $occupancyRate
                    ];
                }
                return $data;
            }

        } catch (\Exception $e) {
            \Log::error('Error calculating occupancy: ' . $e->getMessage());
            
            if ($roomTypeId) {
                return [
                    'total_rooms' => 0,
                    'available_rooms' => 0,
                    'booked_rooms' => 0,
                    'occupancy_rate' => 0
                ];
            } else {
                return [];
            }
        }
    }

    /**
     * Clean old occupancy data
     */
    public function cleanOldOccupancyData($daysToKeep = 90)
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep)->toDateString();
        
        return RoomOccupancy::where('date', '<', $cutoffDate)->delete();
    }
}
