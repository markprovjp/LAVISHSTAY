<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {

        //Lấy tất cả các loại phòng và tổng các phòng trong mỗi loại
        $allrooms = RoomType::withCount('rooms')
            ->with(['rooms' => function ($query) {
                $query->orderBy('room_type_id', 'asc');
            }])
            ->paginate(7);
            // dd($allrooms);
        return view('admin.rooms.index', compact('allrooms'));
    }
    
    public function roomsByType(Request $request, $roomTypeId)
    {
        // Get the room type
        $roomType = RoomType::where('room_type_id', $roomTypeId)->firstOrFail();
        
        // Query rooms of this type
        $query = Room::with(['roomType'])
            ->where('room_type_id', $roomTypeId);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('floor', 'LIKE', "%{$search}%")
                  ->orWhere('room_number', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('base_price_vnd', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price_vnd', '<=', $request->max_price);
        }

        // Size range filter
        if ($request->filled('min_size')) {
            $query->where('size', '>=', $request->min_size);
        }
        if ($request->filled('max_size')) {
            $query->where('size', '<=', $request->max_size);
        }

        // Max guests filter
        if ($request->filled('max_guests')) {
            $query->where('max_guests', '>=', $request->max_guests);
        }

        // View filter
        if ($request->filled('view')) {
            $query->where('view', $request->view);
        }

        // Date availability filter
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            
            if ($checkOut->lte($checkIn)) {
                return redirect()->back()->withErrors(['check_out' => 'Ngày trả phòng phải sau ngày nhận phòng']);
            }
            
            // Here you would check against bookings table for availability
            // For now, we'll just validate the dates
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['name', 'base_price_vnd', 'size', 'max_guests', 'rating', 'floor', 'room_number'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $rooms = $query->paginate(12)->withQueryString();

        // Get filter options for dropdowns
        $statusOptions = [
            'available' => 'Trống',
            'occupied' => 'Đang sử dụng', 
            'maintenance' => 'Đang bảo trì',
            'cleaning' => 'Đang dọn dẹp'
        ];
        
        // Get unique values for this room type
        $viewOptions = Room::where('room_type_id', $roomTypeId)
            ->distinct()
            ->pluck('view')
            ->filter()
            ->sort();
            
        $priceRange = [
            'min' => Room::where('room_type_id', $roomTypeId)->min('base_price_vnd') ?? 0,
            'max' => Room::where('room_type_id', $roomTypeId)->max('base_price_vnd') ?? 10000000
        ];
        
        $sizeRange = [
            'min' => Room::where('room_type_id', $roomTypeId)->min('size') ?? 0,
            'max' => Room::where('room_type_id', $roomTypeId)->max('size') ?? 200
        ];

        return view('admin.rooms.rooms', compact(
            'rooms', 
            'roomType', 
            'statusOptions', 
            'viewOptions', 
            'priceRange', 
            'sizeRange'
        ));
    }

    /**
     * Display the specified room
     */
    public function show($roomId)
    {
        $room = Room::with(['roomType.amenities'])
            ->where('room_id', $roomId)
            ->firstOrFail();
            
        return view('admin.rooms.show', compact('room'));
    }


    /**
 * Get room availability calendar data
 */
public function getCalendarData($roomId)
    {
        try {
            // Tìm phòng
            $room = Room::with('roomType')->find($roomId);
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phòng'
                ]);
            }

            // Lấy dữ liệu availability từ database
            $startDate = now()->startOfMonth();
            $endDate = now()->addMonths(2)->endOfMonth();
            
            // Query availability data
            $availabilityData = DB::table('room_availability as ra')
                ->join('room_option as ro', 'ra.option_id', '=', 'ro.option_id')
                ->where('ro.room_id', $roomId)
                ->whereBetween('ra.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->select(
                    'ra.date',
                    'ra.total_rooms',
                    'ra.available_rooms',
                    'ro.name as option_name',
                    'ro.price_per_night_vnd'
                )
                ->orderBy('ra.date')
                ->get();

            // Group by date và tính tổng
            $calendarData = [];
            $groupedData = $availabilityData->groupBy('date');
            
            foreach ($groupedData as $date => $dayData) {
                $totalRooms = $dayData->sum('total_rooms');
                $availableRooms = $dayData->sum('available_rooms');
                $occupiedRooms = $totalRooms - $availableRooms;
                
                $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
                
                // Xác định status
                $status = 'available';
                if ($availableRooms == 0) {
                    $status = 'full';
                } elseif ($availableRooms <= ($totalRooms * 0.3)) {
                    $status = 'partial';
                }
                
                $calendarData[] = [
                    'date' => $date,
                    'total_rooms' => $totalRooms,
                    'available_rooms' => $availableRooms,
                    'occupied_rooms' => $occupiedRooms,
                    'active_bookings' => $this->getActiveBookings($roomId, $date),
                    'status' => $status,
                    'occupancy_rate' => round($occupancyRate, 1),
                    'options' => $dayData->map(function($item) {
                        return [
                            'name' => $item->option_name,
                            'available' => $item->available_rooms,
                            'total' => $item->total_rooms,
                            'price' => $item->price_per_night_vnd
                        ];
                    })->toArray()
                ];
            }

            // Tính summary
            $totalDays = count($calendarData);
            $availableDays = collect($calendarData)->where('status', 'available')->count();
            $fullDays = collect($calendarData)->where('status', 'full')->count();
            $partialDays = collect($calendarData)->where('status', 'partial')->count();
            $avgOccupancy = collect($calendarData)->avg('occupancy_rate');

            return response()->json([
                'success' => true,
                'room' => [
                    'id' => $room->room_id,
                    'name' => $room->name,
                    'room_number' => $room->room_number ?? 'N/A',
                    'type' => $room->roomType->name ?? 'Standard Room'
                ],
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d')
                ],
                'calendar_data' => $calendarData,
                'summary' => [
                    'total_days' => $totalDays,
                    'available_days' => $availableDays,
                    'full_days' => $fullDays,
                    'partial_days' => $partialDays,
                    'average_occupancy' => round($avgOccupancy, 1)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Calendar data error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getActiveBookings($roomId, $date)
    {
        return DB::table('booking as b')
            ->join('room_option as ro', 'b.option_id', '=', 'ro.option_id')
            ->where('ro.room_id', $roomId)
            ->where('b.check_in_date', '<=', $date)
            ->where('b.check_out_date', '>', $date)
            ->whereIn('b.status', ['confirmed', 'pending'])
            ->count();
    }


    
}
