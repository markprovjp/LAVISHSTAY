<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Floor; 
use App\Models\BedType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index(){
        // Lấy tổng số phòng (đếm bản ghi trong bảng rooms)
        $totalRooms = Room::count();
        
        // Lấy tổng số loại phòng
        $totalRoomTypes = RoomType::count();
        
        // Lấy số phòng đã được đặt (có booking active)
        $bookedRooms = Room::whereHas('bookings', function ($query) {
            $query->where('booking.status', 'confirmed')
                ->where('booking.check_in_date', '<=', now())
                ->where('booking.check_out_date', '>=', now());
        })->count();
        
        // Lấy số phòng trống
        $availableRooms = Room::where('status', 'available')
            ->whereDoesntHave('bookings', function ($query) {
                $query->where('booking.status', 'confirmed')
                    ->where('booking.check_in_date', '<=', now())
                    ->where('booking.check_out_date', '>=', now());
            })->count();

        // Lấy tất cả các loại phòng với thông tin chi tiết 
        $allrooms = RoomType::with([
            'rooms' => function ($query) {
                $query->orderBy('room_type_id', 'asc');
            },
            'images' => function ($query) {
                $query->where('is_main', true);
            }
        ])
        ->withCount([
            'rooms',
            'bookings as active_bookings_count' => function ($query) {
                $query->where('booking.status', 'confirmed')
                    ->where('booking.check_in_date', '<=', now())
                    ->where('booking.check_out_date', '>=', now());
            }
        ])
        ->paginate(9);

        return view('admin.rooms.index', compact(
            'allrooms', 
            'totalRooms', 
            'totalRoomTypes', 
            'bookedRooms', 
            'availableRooms'
        ));
    }

    public function roomsByType(Request $request, $roomTypeId)
    {
        $roomType = RoomType::where('room_type_id', $roomTypeId)->firstOrFail();
        
        $query = Room::with(['roomType', 'floor', 'bedType']) // Sửa thành bedTypes
        ->where('room_type_id', $roomTypeId);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('floor_id', 'LIKE', "%{$search}%") // Thay floor bằng floor_id
                  ->orWhereHas('floor', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('bedType', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date availability filter
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            
            if ($checkOut->lte($checkIn)) {
                return redirect()->back()->withErrors(['check_out' => 'Ngày trả phòng phải sau ngày nhận phòng']);
            }
            
            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('status', 'confirmed')
                  ->where(function ($q) use ($checkIn, $checkOut) {
                      $q->where('check_in_date', '<=', $checkOut)
                        ->where('check_out_date', '>=', $checkIn);
                  });
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['name', 'floor_id', 'status', 'created_at', 'updated_at']; // Cập nhật allowedSorts
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $rooms = $query->paginate(12)->withQueryString();

        $statusOptions = [
            'available' => 'Trống',
            'occupied' => 'Đang sử dụng', 
            'maintenance' => 'Đang bảo trì',
            'cleaning' => 'Đang dọn dẹp'
        ];

        return view('admin.rooms.rooms', compact('rooms', 'roomType', 'statusOptions'));
    }

    public function show($roomId)
    {
        $room = Room::with(['roomType.amenities'])
            ->where('room_id', $roomId)
            ->firstOrFail();
            
        return view('admin.rooms.show', compact('room'));
    }

    public function create(RoomType $roomType){
        try {
            $statusOptions = [
                'available' => 'Có sẵn',
                'occupied' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'cleaning' => 'Đang dọn dẹp'
            ];

            $floors = Floor::all();
            $bedTypes = BedType::active()->get();

            return view('admin.rooms.create', compact('roomType', 'statusOptions', 'floors', 'bedTypes'));
        } catch (\Exception $e) {
            \Log::error('Error in create method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function store(Request $request, RoomType $roomType){
        try {
            \Log::info('Store method called', ['room_type_id' => $roomType->room_type_id, 'request' => $request->all()]);

            $rules = [
                'name' => 'required|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'floor_id' => 'required|exists:floors,floor_id',
                'bed_type_fixed' => 'required|exists:bed_types,id',
                'status' => 'required|in:available,occupied,maintenance,cleaning',
                'description' => 'nullable|string',
                'last_cleaned' => 'nullable|date',
                'base_price_vnd' => 'required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                \Log::info('New image uploaded');
                $imagePath = $request->file('image')->store('rooms', 'public');
                $imagePath = Storage::url($imagePath);
            }

            $data = [
                'name' => $request->name,
                'image' => $imagePath,
                'floor_id' => $request->floor_id,
                'room_type_id' => $roomType->room_type_id,
                'bed_type_fixed' => $request->bed_type_fixed,
                'status' => $request->status,
                'description' => $request->description,
                'last_cleaned' => $request->last_cleaned,
                'base_price_vnd' => $request->base_price_vnd,
            ];

            $room = Room::create($data);

            \Log::info('Room created successfully', ['room_id' => $room->room_id]);

            return redirect()
                ->route('admin.rooms.show', $room->room_id)
                ->with('success', "Đã tạo phòng {$room->name} thành công!");
        } catch (\Exception $e) {
            \Log::error('Error creating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo phòng: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Room $room){
        try {
            $statusOptions = [
                'available' => 'Có sẵn',
                'occupied' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'cleaning' => 'Đang dọn dẹp'
            ];

            $roomType = $room->roomType;
            $floors = Floor::all(); // Lấy danh sách tất cả tầng
            $bedTypes = BedType::active()->get(); // Chỉ lấy các BedType đang hoạt động

            return view('admin.rooms.edit', compact('room', 'roomType', 'statusOptions', 'floors', 'bedTypes'));
        } catch (\Exception $e) {
            \Log::error('Error in edit method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Room $room){
        try {
            \Log::info('Update method called', ['room_id' => $room->room_id, 'request' => $request->all()]);

            $rules = [
                'name' => 'required|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'floor_id' => 'required|exists:floors,floor_id',
                'bed_type_fixed' => 'required|exists:bed_types,id',
                'status' => 'required|in:available,occupied,maintenance,cleaning',
                'description' => 'nullable|string',
                'last_cleaned' => 'nullable|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $imagePath = $room->image;
            if ($request->hasFile('image')) {
                \Log::info('New image uploaded');
                
                if ($room->image && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
                }
                
                $imagePath = $request->file('image')->store('rooms', 'public');
                $imagePath = Storage::url($imagePath);
            }

            $data = [
                'name' => $request->name,
                'image' => $imagePath,
                'floor_id' => $request->floor_id,
                'bed_type_fixed' => $request->bed_type_fixed,
                'status' => $request->status,
                'description' => $request->description,
                'last_cleaned' => $request->last_cleaned,
            ];

            \Log::info('Updating room with data', $data);

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

    public function destroy(Room $room)
    {
        try {
            \Log::info('Delete method called', ['room_id' => $room->room_id]);

            $roomName = $room->name;
            $roomTypeId = $room->room_type_id;

            if ($room->image && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
            }

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

    public function getCalendarData($roomId)
    {
        try {
            $room = Room::with('roomType')->where('room_id', $roomId)->first();
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phòng với ID: ' . $roomId
                ], 404);
            }

            $startDate = now()->startOfMonth()->subMonth();
            $endDate = now()->addMonths(2)->endOfMonth();
            
            \Log::info('Calendar date range:', [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]);

            $calendarData = [];
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $dateStr = $current->format('Y-m-d');
                $realData = $this->getRealBookingData($roomId, $dateStr);
                
                if (!$realData) {
                    $realData = $this->generateSampleData($dateStr);
                }
                
                $calendarData[] = $realData;
                $current->addDay();
            }

            $summary = $this->calculateSummary($calendarData);

            $response = [
                'success' => true,
                'room' => [
                    'id' => $room->room_id,
                    'name' => $room->name,
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
        $bookings = DB::table('booking as b')
            ->join('room_option as ro', 'b.option_id', '=', 'ro.option_id')
            ->where('ro.room_id', $roomId)
            ->where('b.check_in_date', '<=', $date)
            ->where('b.check_out_date', '>', $date)
            ->whereIn('b.status', ['confirmed', 'pending'])
            ->count();
        
        if ($bookings > 0) {
            $totalRooms = Room::where('room_type_id', function ($query) use ($roomId) {
                $query->select('room_type_id')->from('rooms')->where('room_id', $roomId);
            })->count(); // Lấy tổng số phòng theo room_type
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
        $totalRooms = 10; // Giả định số phòng cố định
        $dayOfWeek = date('N', strtotime($date));
        $isWeekend = in_array($dayOfWeek, [6, 7]);
        
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