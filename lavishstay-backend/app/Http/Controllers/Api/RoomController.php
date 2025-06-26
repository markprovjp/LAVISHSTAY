<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Room::with(['roomType']);
            
            // Filter by room type
            if ($request->has('room_type_id') && $request->room_type_id) {
                $query->where('room_type_id', $request->room_type_id);
            }

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('floor', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Price range filter
            if ($request->has('min_price')) {
                $query->where('base_price_vnd', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('base_price_vnd', '<=', $request->max_price);
            }

            // Size range filter
            if ($request->has('min_size')) {
                $query->where('size', '>=', $request->min_size);
            }
            if ($request->has('max_size')) {
                $query->where('size', '<=', $request->max_size);
            }

            // Max guests filter
            if ($request->has('max_guests')) {
                $query->where('max_guests', '>=', $request->max_guests);
            }

            // View filter
            if ($request->has('view')) {
                $query->where('view', $request->view);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            $allowedSorts = ['name', 'base_price_vnd', 'size', 'max_guests', 'rating', 'floor'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->get('per_page', 12);
            $rooms = $query->paginate($perPage);

            $data = $rooms->getCollection()->map(function ($room) {
                return [
                    'id' => $room->room_id,
                    'name' => $room->name,
                    'floor' => $room->floor,
                    'image' => $room->image,
                    'base_price_vnd' => $room->base_price_vnd,
                    'size' => $room->size,
                    'view' => $room->view,
                    'rating' => $room->rating,
                    'lavish_plus_discount' => $room->lavish_plus_discount,
                    'max_guests' => $room->max_guests,
                    'description' => $room->description,
                    'status' => $room->status,
                    'status_color' => $room->status_color,
                    'room_type' => $room->roomType ? [
                        'id' => $room->roomType->room_type_id,
                        'name' => $room->roomType->name,
                        'room_code' => $room->roomType->room_code
                    ] : null,
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'per_page' => $rooms->perPage(),
                    'total' => $rooms->total(),
                    'from' => $rooms->firstItem(),
                    'to' => $rooms->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms by room type
     */
    public function roomsByType(Request $request, $roomTypeId): JsonResponse
    {
        try {
            // Get the room type
            $roomType = RoomType::where('room_type_id', $roomTypeId)->firstOrFail();
            
            // Query rooms of this type
            $query = Room::with(['roomType'])
                ->where('room_type_id', $roomTypeId);

            // Apply filters (same as index method)
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('floor', 'like', "%{$search}%");
                });
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('min_price')) {
                $query->where('base_price_vnd', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('base_price_vnd', '<=', $request->max_price);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            $allowedSorts = ['name', 'base_price_vnd', 'size', 'max_guests', 'rating', 'floor'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $perPage = $request->get('per_page', 12);
            $rooms = $query->paginate($perPage);

            $data = $rooms->getCollection()->map(function ($room) {
                return [
                    'id' => $room->room_id,
                    'name' => $room->name,
                    'floor' => $room->floor,
                    'image' => $room->image,
                    'base_price_vnd' => $room->base_price_vnd,
                    'size' => $room->size,
                    'view' => $room->view,
                    'rating' => $room->rating,
                    'lavish_plus_discount' => $room->lavish_plus_discount,
                    'max_guests' => $room->max_guests,
                    'description' => $room->description,
                    'status' => $room->status,
                    'status_color' => $room->status_color,
                    'room_type' => $room->roomType ? [
                        'id' => $room->roomType->room_type_id,
                        'name' => $room->roomType->name,
                        'room_code' => $room->roomType->room_code
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'room_type' => [
                    'id' => $roomType->room_type_id,
                    'name' => $roomType->name,
                    'room_code' => $roomType->room_code,
                    'description' => $roomType->description
                ],
                'data' => $data,
                'pagination' => [
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'per_page' => $rooms->perPage(),
                    'total' => $rooms->total(),
                    'from' => $rooms->firstItem(),
                    'to' => $rooms->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách phòng theo loại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms by room type slug
     */
    public function roomsByTypeSlug(Request $request, $slug): JsonResponse
    {
        try {
            // Get the room type by slug
            $roomType = RoomType::where('room_code', $slug)
                ->orWhere('slug', $slug)
                ->firstOrFail();
            
            // Query rooms of this type
            $query = Room::with(['roomType'])
                ->where('room_type_id', $roomType->room_type_id);

            // Apply filters (same as index method)
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('floor', 'like', "%{$search}%");
                });
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('min_price')) {
                $query->where('base_price_vnd', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('base_price_vnd', '<=', $request->max_price);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            $allowedSorts = ['name', 'base_price_vnd', 'size', 'max_guests', 'rating', 'floor'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $perPage = $request->get('per_page', 12);
            $rooms = $query->paginate($perPage);

            $data = $rooms->getCollection()->map(function ($room) {
                return [
                    'id' => $room->room_id,
                    'name' => $room->name,
                    'floor' => $room->floor,
                    'image' => $room->image,
                    'base_price_vnd' => $room->base_price_vnd,
                    'size' => $room->size,
                    'view' => $room->view,
                    'rating' => $room->rating,
                    'lavish_plus_discount' => $room->lavish_plus_discount,
                    'max_guests' => $room->max_guests,
                    'description' => $room->description,
                    'status' => $room->status,
                    'status_color' => $room->status_color,
                    'room_type' => $room->roomType ? [
                        'id' => $room->roomType->room_type_id,
                        'name' => $room->roomType->name,
                        'room_code' => $room->roomType->room_code
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'room_type' => [
                    'id' => $roomType->room_type_id,
                    'name' => $roomType->name,
                    'room_code' => $roomType->room_code,
                    'description' => $roomType->description
                ],
                'data' => $data,
                'pagination' => [
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'per_page' => $rooms->perPage(),
                    'total' => $rooms->total(),
                    'from' => $rooms->firstItem(),
                    'to' => $rooms->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách phòng theo loại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified room
     */
    public function show(string $id): JsonResponse
    {
        try {
            $room = Room::with(['roomType.amenities'])
                ->where('room_id', $id)
                ->firstOrFail();

            $data = [
                'id' => $room->room_id,
                'name' => $room->name,
                'floor' => $room->floor,
                'image' => $room->image,
                'base_price_vnd' => $room->base_price_vnd,
                'size' => $room->size,
                'view' => $room->view,
                'rating' => $room->rating,
                'lavish_plus_discount' => $room->lavish_plus_discount,
                'max_guests' => $room->max_guests,
                'description' => $room->description,
                'status' => $room->status,
                'status_color' => $room->status_color,
                'room_type' => $room->roomType ? [
                    'id' => $room->roomType->room_type_id,
                    'name' => $room->roomType->name,
                    'room_code' => $room->roomType->room_code,
                    'description' => $room->roomType->description,
                    'amenities' => $room->roomType->amenities->map(function ($amenity) {
                        return [
                            'id' => $amenity->amenity_id,
                            'name' => $amenity->name,
                            'icon' => $amenity->icon,
                            'is_highlighted' => $amenity->pivot->is_highlighted ?? false
                        ];
                    })
                ] : null,
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phòng',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'room_type_id' => 'required|exists:room_types,room_type_id',
                'name' => 'required|string|max:255',
                'floor' => 'required|integer|min:1|max:50',
                'base_price_vnd' => 'required|numeric|min:0',
                'size' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|max:20',
                'view' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'lavish_plus_discount' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'status' => 'required|in:available,occupied,maintenance,cleaning'
            ]);

            $room = Room::create([
                'room_type_id' => $request->room_type_id,
                'name' => $request->name,
                'floor' => $request->floor,
                'base_price_vnd' => $request->base_price_vnd,
                'size' => $request->size,
                'view' => $request->view,
                'rating' => $request->rating ?? 0,
                'lavish_plus_discount' => $request->lavish_plus_discount ?? 0,
                'max_guests' => $request->max_guests,
                'description' => $request->description,
                'status' => $request->status
            ]);

            // Load room with room type for response
            $room->load('roomType');

            $data = [
                'id' => $room->room_id,
                'name' => $room->name,
                'floor' => $room->floor,
                'base_price_vnd' => $room->base_price_vnd,
                'size' => $room->size,
                'view' => $room->view,
                'rating' => $room->rating,
                'lavish_plus_discount' => $room->lavish_plus_discount,
                'max_guests' => $room->max_guests,
                'description' => $room->description,
                'status' => $room->status,
                'room_type' => [
                    'id' => $room->roomType->room_type_id,
                    'name' => $room->roomType->name,
                    'room_code' => $room->roomType->room_code
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Phòng đã được tạo thành công',
                'data' => $data
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $room = Room::where('room_id', $id)->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255',
                'floor' => 'required|integer|min:1|max:50',
                'base_price_vnd' => 'required|numeric|min:0',
                'size' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|max:20',
                'view' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'lavish_plus_discount' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'status' => 'required|in:available,occupied,maintenance,cleaning'
            ]);

            $room->update([
                'name' => $request->name,
                'floor' => $request->floor,
                'base_price_vnd' => $request->base_price_vnd,
                'size' => $request->size,
                'view' => $request->view,
                'rating' => $request->rating ?? 0,
                'lavish_plus_discount' => $request->lavish_plus_discount ?? 0,
                'max_guests' => $request->max_guests,
                'description' => $request->description,
                'status' => $request->status
            ]);

            $room->load('roomType');

            $data = [
                'id' => $room->room_id,
                'name' => $room->name,
                'floor' => $room->floor,
                'base_price_vnd' => $room->base_price_vnd,
                'size' => $room->size,
                'view' => $room->view,
                'rating' => $room->rating,
                'lavish_plus_discount' => $room->lavish_plus_discount,
                'max_guests' => $room->max_guests,
                'description' => $room->description,
                'status' => $room->status,
                'room_type' => [
                    'id' => $room->roomType->room_type_id,
                    'name' => $room->roomType->name,
                    'room_code' => $room->roomType->room_code
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Phòng đã được cập nhật thành công',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $room = Room::where('room_id', $id)->firstOrFail();

            // Check if room has active bookings
            $hasActiveBookings = $room->bookings()->whereIn('status', ['confirmed', 'pending'])->exists();
            
            if ($hasActiveBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa phòng này vì có booking đang hoạt động'
                ], 400);
            }

            $room->delete();

            return response()->json([
                'success' => true,
                'message' => 'Phòng đã được xóa thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room availability calendar data
     */
    public function getCalendarData(Request $request, $roomId): JsonResponse
    {
        try {
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
