<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
 * Get room availability calendar data
 */
public function getCalendarData(Request $request, $roomId)
    {
        $room = Room::findOrFail($roomId);
        
        // Get date range (default: 2 months before to 2 months after current date)
        $startDate = $request->get('start_date', now()->subMonths(2)->startOfMonth());
        $endDate = $request->get('end_date', now()->addMonths(2)->endOfMonth());
        
        // Get all room options for this room
        $roomOptions = DB::table('room_option')
            ->where('room_id', $roomId)
            ->pluck('option_id');
        
        if ($roomOptions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Phòng này chưa có tùy chọn nào được cấu hình'
            ]);
        }
        
        // Get availability data for all options of this room
        $availabilityData = DB::table('room_availability')
            ->whereIn('option_id', $roomOptions)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date', 
                DB::raw('SUM(total_rooms) as total_rooms'),
                DB::raw('SUM(available_rooms) as available_rooms')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get booking data for additional context
        $bookingData = DB::table('booking')
            ->whereIn('option_id', $roomOptions)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                      ->orWhereBetween('check_out_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('check_in_date', '<=', $startDate)
                            ->where('check_out_date', '>=', $endDate);
                      });
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->select('check_in_date', 'check_out_date', 'status', 'guest_count')
            ->get();
        
        // Process data for calendar
        $calendarData = [];
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);
        
        while ($currentDate->lte($endDateCarbon)) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Find availability for this date
            $availability = $availabilityData->firstWhere('date', $dateStr);
            
            // Count active bookings for this date
            $activeBookings = $bookingData->filter(function($booking) use ($dateStr) {
                return $dateStr >= $booking->check_in_date && $dateStr < $booking->check_out_date;
            })->count();
            
            $totalRooms = $availability ? $availability->total_rooms : 0;
            $availableRooms = $availability ? $availability->available_rooms : 0;
            
            $calendarData[] = [
                'date' => $dateStr,
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'occupied_rooms' => $totalRooms - $availableRooms,
                'active_bookings' => $activeBookings,
                'status' => $availableRooms > 0 ? 'available' : ($totalRooms > 0 ? 'full' : 'unavailable'),
                'occupancy_rate' => $totalRooms > 0 ? round((($totalRooms - $availableRooms) / $totalRooms) * 100, 1) : 0
            ];
            
            $currentDate->addDay();
        }
        
        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->room_id,
                'name' => $room->name,
                'room_number' => $room->room_number,
                'type' => $room->roomType->name ?? 'N/A'
            ],
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'calendar_data' => $calendarData,
            'summary' => [
                'total_days' => count($calendarData),
                'available_days' => collect($calendarData)->where('status', 'available')->count(),
                'full_days' => collect($calendarData)->where('status', 'full')->count(),
                'average_occupancy' => collect($calendarData)->avg('occupancy_rate')
            ]
        ]);
    }

}
