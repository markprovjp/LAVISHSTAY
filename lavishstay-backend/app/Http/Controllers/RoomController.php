<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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


<<<<<<< HEAD
    /**
 * Get room availability calendar data
 */
public function getCalendarData($roomId)
{
    try {
        // Tìm phòng
        $room = Room::with('roomType')->where('room_id', $roomId)->first();
        
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phòng với ID: ' . $roomId
            ], 404);
        }

        // Tạo range 3 tháng: tháng trước, tháng hiện tại, tháng sau
        $startDate = now()->startOfMonth()->subMonth();
        $endDate = now()->addMonths(2)->endOfMonth();
        
        \Log::info('Calendar date range:', [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d')
        ]);

        // Tạo dữ liệu cho mỗi ngày trong khoảng thời gian
        $calendarData = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            
            // Lấy dữ liệu thực từ database (nếu có)
            $realData = $this->getRealBookingData($roomId, $dateStr);
            
            
            // Nếu không có dữ liệu thực, tạo dữ liệu mẫu
            if (!$realData) {
                $realData = $this->generateSampleData($dateStr);
            }
            

            $calendarData[] = $realData;
            $current->addDay();
        }

        // Tính summary
        $summary = $this->calculateSummary($calendarData);

        $response = [
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
            'summary' => $summary
        ];

        \Log::info('Calendar response:', [
            'room_id' => $response['room']['id'],
            'data_count' => count($response['calendar_data']),
            'date_range' => $response['date_range']
        ]);

        return response()->json($response);

    } catch (\Exception $e) {
        \Log::error('Calendar error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Lỗi server: ' . $e->getMessage()
        ], 500);
    }
}

private function getRealBookingData($roomId, $date)
{
    // Thử lấy dữ liệu thực từ database
    $bookings = DB::table('booking as b')
        ->join('room_option as ro', 'b.option_id', '=', 'ro.option_id')
        ->where('ro.room_id', $roomId)
        ->where('b.check_in_date', '<=', $date)
        ->where('b.check_out_date', '>', $date)
        ->whereIn('b.status', ['confirmed', 'pending'])
        ->count();
    
    // Nếu có dữ liệu booking thì return, không thì return null
    if ($bookings > 0) {
        $totalRooms = 10; // Giả sử mỗi room type có 10 phòng
        $occupiedRooms = min($bookings, $totalRooms);
=======
    public function create(RoomType $roomType)
    {
        $viewOptions = [
            'Hướng biển',
            'Hướng núi', 
            'Hướng thành phố',
            'Hướng vườn',
            'Hướng hồ bơi',
            'Hướng sân golf'
        ];

        $statusOptions = [
            'available' => 'Có sẵn',
            'occupied' => 'Đã đặt',
            'maintenance' => 'Bảo trì',
            'cleaning' => 'Đang dọn dẹp'
        ];

        // $hotels = Hotel::all();

        // Get existing rooms to suggest next available
        $existingRooms = Room::where('room_type_id', $roomType->room_type_id)
            ->orderBy('name')
            ->get(['name', 'floor']);

        return view('admin.rooms.create', compact('roomType', 'viewOptions', 'statusOptions', 'existingRooms'));
    }

    /**
     * Store new room
     */
    public function store(Request $request, RoomType $roomType)
    {
        try {
            \Log::info('Store method called', ['request' => $request->all()]);

            $rules = [
                'name' => 'required|string|max:255',
                'floor' => 'required|integer|min:1|max:50',
                'base_price_vnd' => 'required|numeric|min:0',
                'size' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|max:20',
                'view' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'lavish_plus_discount' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'status' => 'required|in:available,occupied,maintenance,cleaning',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            // // Chỉ validate hotel_id nếu có bảng hotel
            // if (\Schema::hasTable('hotel')) {
            //     $rules['hotel_id'] = 'required|exists:hotel,hotel_id';
            // }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                \Log::info('Image uploaded');
                $imagePath = $request->file('image')->store('rooms', 'public');
                $imagePath = Storage::url($imagePath);
            }

            // Prepare data
            $data = [
                'room_type_id' => $roomType->room_type_id,
                'name' => $request->name,
                'floor' => $request->floor,
                'image' => $imagePath,
                'base_price_vnd' => $request->base_price_vnd,
                'size' => $request->size,
                'view' => $request->view,
                'rating' => $request->rating ?? 0,
                'lavish_plus_discount' => $request->lavish_plus_discount ?? 0,
                'max_guests' => $request->max_guests,
                'description' => $request->description,
                'status' => $request->status
            ];

            // // Chỉ thêm hotel_id nếu có
            // if ($request->has('hotel_id') && $request->hotel_id) {
            //     $data['hotel_id'] = $request->hotel_id;
            // }

            \Log::info('Creating room with data', $data);

            // Create room
            $room = Room::create($data);

            \Log::info('Room created successfully', ['room_id' => $room->room_id]);

            return redirect()
                ->route('admin.rooms.by-type', $roomType->room_type_id)
                ->with('success', "Đã thêm phòng {$room->name} thành công!");

        } catch (\Exception $e) {
            \Log::error('Error creating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thêm phòng: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function edit(Room $room)
    {
        try {
            $viewOptions = [
                'Hướng biển',
                'Hướng núi', 
                'Hướng thành phố',
                'Hướng vườn',
                'Hướng hồ bơi',
                'Hướng sân golf'
            ];

            $statusOptions = [
                'available' => 'Có sẵn',
                'occupied' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'cleaning' => 'Đang dọn dẹp'
            ];

            // Get hotels
            $hotels = collect([]);
            if (\Schema::hasTable('hotels')) {
                $hotels = Hotel::all();
            }

            // Get room type
            $roomType = $room->roomType;

            return view('admin.rooms.edit', compact('room', 'roomType', 'viewOptions', 'statusOptions', 'hotels'));
        } catch (\Exception $e) {
            Log::error('Error in edit method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Update room
     */
    public function update(Request $request, Room $room)
    {
        try {
            \Log::info('Update method called', ['room_id' => $room->room_id, 'request' => $request->all()]);

            $rules = [
                'name' => 'required|string|max:255',
                'floor' => 'required|integer|min:1|max:50',
                'base_price_vnd' => 'required|numeric|min:0',
                'size' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|max:20',
                'view' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'lavish_plus_discount' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'status' => 'required|in:available,occupied,maintenance,cleaning',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            // // Chỉ validate hotel_id nếu có bảng hotels
            // if (\Schema::hasTable('hotels')) {
            //     $rules['hotel_id'] = 'required|exists:hotels,hotel_id';
            // }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle image upload
            $imagePath = $room->image; // Keep existing image by default
            if ($request->hasFile('image')) {
                \Log::info('New image uploaded');
                
                // Delete old image if exists
                if ($room->image && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
                }
                
                $imagePath = $request->file('image')->store('rooms', 'public');
                $imagePath = Storage::url($imagePath);
            }

            // Prepare data
            $data = [
                'name' => $request->name,
                'floor' => $request->floor,
                'image' => $imagePath,
                'base_price_vnd' => $request->base_price_vnd,
                'size' => $request->size,
                'view' => $request->view,
                'rating' => $request->rating ?? 0,
                'lavish_plus_discount' => $request->lavish_plus_discount ?? 0,
                'max_guests' => $request->max_guests,
                'description' => $request->description,
                'status' => $request->status
            ];

            // // Chỉ thêm hotel_id nếu có
            // if ($request->has('hotel_id') && $request->hotel_id) {
            //     $data['hotel_id'] = $request->hotel_id;
            // }

            \Log::info('Updating room with data', $data);

            // Update room
            $room->update($data);

            \Log::info('Room updated successfully', ['room_id' => $room->room_id]);

            return redirect()
                ->route('admin.rooms.show', $room->room_id)
                ->with('success', "Đã cập nhật phòng {$room->name} thành công!");

        } catch (\Exception $e) {
            \Log::error('Error updating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật phòng: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
 * Delete room
 */
    public function destroy(Room $room)
    {
        try {
            \Log::info('Delete method called', ['room_id' => $room->room_id]);

            $roomName = $room->name;
            $roomTypeId = $room->room_type_id;

            // Delete image if exists
            if ($room->image && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
            }

            // Delete room
            $room->delete();

            \Log::info('Room deleted successfully', ['room_name' => $roomName]);

            return redirect()
                ->route('admin.rooms.by-type', $roomTypeId)
                ->with('success', "Đã xóa phòng {$roomName} thành công!");

        } catch (\Exception $e) {
            \Log::error('Error deleting room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'room_id' => $room->room_id
            ]);
            
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa phòng: ' . $e->getMessage());
        }
    }





    /**
     * Get room availability calendar data
     */
    public function getCalendarData($roomId)
    {
        try {
            // Tìm phòng
            $room = Room::with('roomType')->where('room_id', $roomId)->first();
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phòng với ID: ' . $roomId
                ], 404);
            }

            // Tạo range 3 tháng: tháng trước, tháng hiện tại, tháng sau
            $startDate = now()->startOfMonth()->subMonth();
            $endDate = now()->addMonths(2)->endOfMonth();
            
            \Log::info('Calendar date range:', [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]);

            // Tạo dữ liệu cho mỗi ngày trong khoảng thời gian
            $calendarData = [];
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $dateStr = $current->format('Y-m-d');
                
                // Lấy dữ liệu thực từ database (nếu có)
                $realData = $this->getRealBookingData($roomId, $dateStr);
                
                
                // Nếu không có dữ liệu thực, tạo dữ liệu mẫu
                if (!$realData) {
                    $realData = $this->generateSampleData($dateStr);
                }
                

                $calendarData[] = $realData;
                $current->addDay();
            }

            // Tính summary
            $summary = $this->calculateSummary($calendarData);

            $response = [
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
                'summary' => $summary
            ];

            \Log::info('Calendar response:', [
                'room_id' => $response['room']['id'],
                'data_count' => count($response['calendar_data']),
                'date_range' => $response['date_range']
            ]);

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Calendar error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getRealBookingData($roomId, $date)
    {
        // Thử lấy dữ liệu thực từ database
        $bookings = DB::table('booking as b')
            ->join('room_option as ro', 'b.option_id', '=', 'ro.option_id')
            ->where('ro.room_id', $roomId)
            ->where('b.check_in_date', '<=', $date)
            ->where('b.check_out_date', '>', $date)
            ->whereIn('b.status', ['confirmed', 'pending'])
            ->count();
        
        // Nếu có dữ liệu booking thì return, không thì return null
        if ($bookings > 0) {
            $totalRooms = 10; // Giả sử mỗi room type có 10 phòng
            $occupiedRooms = min($bookings, $totalRooms);
            $availableRooms = $totalRooms - $occupiedRooms;
            $occupancyRate = ($occupiedRooms / $totalRooms) * 100;
            
            $status = 'available';
            if ($availableRooms == 0) {
                $status = 'full';
            } elseif ($availableRooms <= ($totalRooms * 0.3)) {
                $status = 'partial';
            }
            
            return [
                'date' => $date,
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'occupied_rooms' => $occupiedRooms,
                'active_bookings' => $bookings,
                'status' => $status,
                'occupancy_rate' => round($occupancyRate, 1)
            ];
        }
        
        return null;
    }

    private function generateSampleData($date)
    {
        // Tạo dữ liệu mẫu ngẫu nhiên nhưng có logic
        $totalRooms = 10;
        $dayOfWeek = date('N', strtotime($date)); // 1=Monday, 7=Sunday
        $isWeekend = in_array($dayOfWeek, [6, 7]); // Saturday, Sunday
        
        // Cuối tuần thường đông hơn
        if ($isWeekend) {
            $occupiedRooms = rand(6, 10);
        } else {
            $occupiedRooms = rand(2, 8);
        }
        
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = ($occupiedRooms / $totalRooms) * 100;
        
        $status = 'available';
        if ($availableRooms == 0) {
            $status = 'full';
<<<<<<< HEAD
        } elseif ($availableRooms <= ($totalRooms * 0.3)) {
=======
        } elseif ($availableRooms <= 3) {
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
            $status = 'partial';
        }
        
        return [
            'date' => $date,
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $occupiedRooms,
<<<<<<< HEAD
            'active_bookings' => $bookings,
=======
            'active_bookings' => rand(0, $occupiedRooms),
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
            'status' => $status,
            'occupancy_rate' => round($occupancyRate, 1)
        ];
    }
<<<<<<< HEAD
    
    return null;
}

private function generateSampleData($date)
{
    // Tạo dữ liệu mẫu ngẫu nhiên nhưng có logic
    $totalRooms = 10;
    $dayOfWeek = date('N', strtotime($date)); // 1=Monday, 7=Sunday
    $isWeekend = in_array($dayOfWeek, [6, 7]); // Saturday, Sunday
    
    // Cuối tuần thường đông hơn
    if ($isWeekend) {
        $occupiedRooms = rand(6, 10);
    } else {
        $occupiedRooms = rand(2, 8);
    }
    
    $availableRooms = $totalRooms - $occupiedRooms;
    $occupancyRate = ($occupiedRooms / $totalRooms) * 100;
    
    $status = 'available';
    if ($availableRooms == 0) {
        $status = 'full';
    } elseif ($availableRooms <= 3) {
        $status = 'partial';
    }
    
    return [
        'date' => $date,
        'total_rooms' => $totalRooms,
        'available_rooms' => $availableRooms,
        'occupied_rooms' => $occupiedRooms,
        'active_bookings' => rand(0, $occupiedRooms),
        'status' => $status,
        'occupancy_rate' => round($occupancyRate, 1)
    ];
}

private function calculateSummary($calendarData)
{
    $totalDays = count($calendarData);
    $availableDays = 0;
    $fullDays = 0;
    $partialDays = 0;
    $totalOccupancy = 0;
    
    foreach ($calendarData as $day) {
        switch ($day['status']) {
            case 'available':
                $availableDays++;
                break;
            case 'full':
                $fullDays++;
                break;
            case 'partial':
                $partialDays++;
                break;
        }
        $totalOccupancy += $day['occupancy_rate'];
    }
    
    return [
        'total_days' => $totalDays,
        'available_days' => $availableDays,
        'full_days' => $fullDays,
        'partial_days' => $partialDays,
        'average_occupancy' => $totalDays > 0 ? round($totalOccupancy / $totalDays, 1) : 0
    ];
}
=======

    private function calculateSummary($calendarData)
    {
        $totalDays = count($calendarData);
        $availableDays = 0;
        $fullDays = 0;
        $partialDays = 0;
        $totalOccupancy = 0;
        
        foreach ($calendarData as $day) {
            switch ($day['status']) {
                case 'available':
                    $availableDays++;
                    break;
                case 'full':
                    $fullDays++;
                    break;
                case 'partial':
                    $partialDays++;
                    break;
            }
            $totalOccupancy += $day['occupancy_rate'];
        }
        
        return [
            'total_days' => $totalDays,
            'available_days' => $availableDays,
            'full_days' => $fullDays,
            'partial_days' => $partialDays,
            'average_occupancy' => $totalDays > 0 ? round($totalOccupancy / $totalDays, 1) : 0
        ];
    }
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc

    
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
