<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of all room types
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = RoomType::query();
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('room_code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $roomTypes = $query->paginate($perPage);

            $data = $roomTypes->getCollection()->map(function ($roomType) {
                // Parse images from JSON string if exists
                $imagesList = [];
                if (!empty($roomType->images) && is_string($roomType->images)) {
                    try {
                        $imagesList = json_decode($roomType->images, true) ?: [];
                    } catch (\Exception $e) {
                        $imagesList = [];
                    }
                }

                // Get room statistics for this room type
                $availableRoomsCount = 0;
                $totalRoomsCount = 0;
                $minPrice = 1200000;
                $maxPrice = 1200000;
                $avgPrice = 1200000;
                $avgSize = 0;
                $avgRating = 0;
                $maxGuests = 2;
                $commonViews = [];
                $floors = [];

                try {
                    $rooms = \DB::table('room')
                        ->where('room_type_id', $roomType->room_type_id)
                        ->get();

                    $totalRoomsCount = count($rooms);
                    $roomPrices = [];
                    $roomSizes = [];
                    $roomRatings = [];
                    
                    foreach ($rooms as $room) {
                        if ($room->status === 'available') {
                            $availableRoomsCount++;
                        }
                        
                        if (!empty($room->base_price_vnd)) {
                            $roomPrices[] = floatval($room->base_price_vnd);
                        }
                        
                        if (!empty($room->size)) {
                            $roomSizes[] = floatval($room->size);
                        }
                        
                        if (!empty($room->rating)) {
                            $roomRatings[] = floatval($room->rating);
                        }
                        
                        if (!empty($room->max_guests) && $room->max_guests > $maxGuests) {
                            $maxGuests = $room->max_guests;
                        }
                        
                        if (!empty($room->view)) {
                            $commonViews[] = $room->view;
                        }
                        
                        if (!empty($room->floor)) {
                            $floors[] = $room->floor;
                        }
                    }
                    
                    if (!empty($roomPrices)) {
                        $minPrice = min($roomPrices);
                        $maxPrice = max($roomPrices);
                        $avgPrice = round(array_sum($roomPrices) / count($roomPrices));
                    }
                    
                    if (!empty($roomSizes)) {
                        $avgSize = round(array_sum($roomSizes) / count($roomSizes));
                    }
                    
                    if (!empty($roomRatings)) {
                        $avgRating = round(array_sum($roomRatings) / count($roomRatings), 1);
                    }
                    
                    $commonViews = array_unique($commonViews);
                    $floors = array_unique($floors);
                    sort($floors);
                    
                } catch (\Exception $e) {
                    // Continue without room data if error
                }

                return [
                    'id' => $roomType->room_type_id,
                    'room_code' => $roomType->room_code,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'total_room' => $roomType->total_room,
                    'images' => $imagesList,
                    'main_image' => !empty($imagesList) ? $imagesList[0] : null,
                    'base_price' => $minPrice,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'avg_price' => $avgPrice,
                    'size' => $avgSize,
                    'avg_size' => $avgSize,
                    'rating' => $avgRating,
                    'avg_rating' => $avgRating,
                    'max_guests' => $maxGuests,
                    'common_views' => array_values($commonViews),
                    'available_floors' => array_values($floors),
                    'rooms_count' => $totalRoomsCount,
                    'available_rooms_count' => $availableRoomsCount,
                    'created_at' => $roomType->created_at,
                    'updated_at' => $roomType->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $roomTypes->currentPage(),
                    'last_page' => $roomTypes->lastPage(),
                    'per_page' => $roomTypes->perPage(),
                    'total' => $roomTypes->total(),
                    'from' => $roomTypes->firstItem(),
                    'to' => $roomTypes->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách loại phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified room type
     */
    public function show(string $id): JsonResponse
    {
        try {
            $roomType = RoomType::where('room_type_id', $id)
                ->firstOrFail();

            // Parse images from JSON string if exists
            $imagesList = [];
            if (!empty($roomType->images) && is_string($roomType->images)) {
                try {
                    $imagesList = json_decode($roomType->images, true) ?: [];
                } catch (\Exception $e) {
                    $imagesList = [];
                }
            }

            // Get comprehensive information from both room_types and room tables
            $availableRoomsCount = 0;
            $totalRoomsCount = 0;
            $minPrice = 1200000;
            $maxPrice = 1200000;
            $avgPrice = 1200000;
            $avgSize = 0;
            $avgRating = 0;
            $maxGuests = 2;
            $commonViews = [];
            $floors = [];

            try {
                $roomsQuery = \DB::table('room')
                    ->where('room_type_id', $roomType->room_type_id)
                    ->get();

                $totalRoomsCount = count($roomsQuery);
                $roomPrices = [];
                $roomSizes = [];
                $roomRatings = [];
                
                foreach ($roomsQuery as $room) {
                    if ($room->status === 'available') {
                        $availableRoomsCount++;
                    }
                    
                    // Collect pricing data
                    if (!empty($room->base_price_vnd)) {
                        $roomPrices[] = floatval($room->base_price_vnd);
                    }
                    
                    // Collect size data
                    if (!empty($room->size)) {
                        $roomSizes[] = floatval($room->size);
                    }
                    
                    // Collect rating data
                    if (!empty($room->rating)) {
                        $roomRatings[] = floatval($room->rating);
                    }
                    
                    // Collect max guests data
                    if (!empty($room->max_guests) && $room->max_guests > $maxGuests) {
                        $maxGuests = $room->max_guests;
                    }
                    
                    // Collect view data
                    if (!empty($room->view)) {
                        $commonViews[] = $room->view;
                    }
                    
                    // Collect floor data
                    if (!empty($room->floor)) {
                        $floors[] = $room->floor;
                    }
                }
                
                // Calculate pricing statistics
                if (!empty($roomPrices)) {
                    $minPrice = min($roomPrices);
                    $maxPrice = max($roomPrices);
                    $avgPrice = round(array_sum($roomPrices) / count($roomPrices));
                }
                
                // Calculate size statistics
                if (!empty($roomSizes)) {
                    $avgSize = round(array_sum($roomSizes) / count($roomSizes));
                }
                
                // Calculate rating statistics
                if (!empty($roomRatings)) {
                    $avgRating = round(array_sum($roomRatings) / count($roomRatings), 1);
                }
                
                // Process views and floors
                $commonViews = array_unique($commonViews);
                $floors = array_unique($floors);
                sort($floors);
                
            } catch (\Exception $e) {
                error_log("Error fetching rooms: " . $e->getMessage());
            }

            $data = [
                'id' => $roomType->room_type_id,
                'room_code' => $roomType->room_code,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'total_room' => $roomType->total_room,
                'images' => $imagesList,
                'main_image' => !empty($imagesList) ? $imagesList[0] : null,
                'base_price' => $minPrice,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'avg_price' => $avgPrice,
                'size' => $avgSize,
                'avg_size' => $avgSize,
                'rating' => $avgRating,
                'avg_rating' => $avgRating,
                'max_guests' => $maxGuests,
                'common_views' => array_values($commonViews),
                'available_floors' => array_values($floors),
                'amenities' => [],
                'highlighted_amenities' => [],
                // Summary statistics only - no individual room details
                'rooms_count' => $totalRoomsCount,
                'available_rooms_count' => $availableRoomsCount,
                'created_at' => $roomType->created_at,
                'updated_at' => $roomType->updated_at
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy loại phòng',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created room type
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'room_code' => 'required|string|max:255|unique:room_types,room_code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'total_room' => 'nullable|integer|min:0',
            ]);

            $roomType = RoomType::create([
                'room_code' => $request->room_code,
                'name' => $request->name,
                'description' => $request->description,
                'total_room' => $request->total_room
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loại phòng đã được tạo thành công',
                'data' => [
                    'id' => $roomType->room_type_id,
                    'room_code' => $roomType->room_code,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'total_room' => $roomType->total_room
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo loại phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified room type
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $roomType = RoomType::where('room_type_id', $id)->firstOrFail();

            $request->validate([
                'room_code' => 'required|string|max:255|unique:room_types,room_code,' . $id . ',room_type_id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'total_room' => 'nullable|integer|min:0',
            ]);

            $roomType->update([
                'room_code' => $request->room_code,
                'name' => $request->name,
                'description' => $request->description,
                'total_room' => $request->total_room
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loại phòng đã được cập nhật thành công',
                'data' => [
                    'id' => $roomType->room_type_id,
                    'room_code' => $roomType->room_code,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'total_room' => $roomType->total_room
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật loại phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room type
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $roomType = RoomType::where('room_type_id', $id)->firstOrFail();

            if ($roomType->rooms()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa loại phòng này vì có phòng đang sử dụng nó'
                ], 400);
            }

            $roomType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loại phòng đã được xóa thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa loại phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
