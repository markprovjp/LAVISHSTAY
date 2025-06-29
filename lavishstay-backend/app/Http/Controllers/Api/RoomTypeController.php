<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
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
            $query = RoomType::with('images');
            
      
            $roomTypes = $query->paginate();

            $data = $roomTypes->getCollection()->map(function ($roomType) {
                $imagesList = $roomType->images->map(function ($img) {
                    return [
                        'id' => $img->image_id,
                        'room_type_id' => $img->room_type_id,
                        'image_url' => asset($img->image_path),
                        'alt_text' => $img->alt_text,
                        'is_main' => $img->is_main,
                    ];
                })->toArray();

// Lấy ảnh chính (is_main = true), nếu không có thì lấy ảnh đầu tiên
$mainImage = null;
foreach ($imagesList as $img) {
    if ($img['is_main']) {
        $mainImage = $img;
        break;
    }
}
if (!$mainImage && !empty($imagesList)) {
    $mainImage = $imagesList[0];
}

           
         

         

                // Get amenities for this room type
                $allAmenities = [];
                $highlightedAmenities = [];
                $this->getAmenitiesForRoomType($roomType->room_type_id, $allAmenities, $highlightedAmenities);

                return [
                    'id' => $roomType->room_type_id,
                    'room_code' => $roomType->room_code,
                    'name' => $roomType->name,
                    'description' => $roomType->description,

                     'images' => $imagesList,
                    'main_image' => $mainImage,
                    'base_price' => $roomType->base_price,
                    'size' => $roomType->room_area,

                    'rating' => $roomType->rating,

                    'max_guests' => $roomType->max_guests,

                    'amenities' => $allAmenities,
                    'highlighted_amenities' => $highlightedAmenities,
   
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
                'message' => 'Co loi khi lay danh sach loai phong',
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
            $roomType = RoomType::with('images')->where('room_type_id', $id)
                ->firstOrFail();

           $imagesList = $roomType->images->map(function ($img) {
                return [
                    'id' => $img->image_id,
                    'image_url' => asset($img->image_path),
                    'alt_text' => $img->alt_text,
                    'is_main' => $img->is_main,
                ];
            })->toArray();

            // Lấy ảnh chính (is_main = true), nếu không có thì lấy ảnh đầu tiên
            $mainImage = null;
            foreach ($imagesList as $img) {
                if ($img['is_main']) {
                    $mainImage = $img;
                    break;
                }
            }
            if (!$mainImage && !empty($imagesList)) {
                $mainImage = $imagesList[0];
            }



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

            // Get amenities based on room type ID
            $allAmenities = [];
            $highlightedAmenities = [];
            
            $this->getAmenitiesForRoomType($roomType->room_type_id, $allAmenities, $highlightedAmenities);

            $data = [
                'id' => $roomType->room_type_id,
                'room_code' => $roomType->room_code,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'total_room' => $roomType->total_room,
                'images' => $imagesList,
                    'main_image' => $mainImage,
                'base_price' => $roomType->base_price,
                    'size' => $roomType->room_area,
                    'rating' => $roomType->rating,
                    'max_guests' => $roomType->max_guests,
                    'view' => $roomType->view,
                'amenities' => $allAmenities,
                'highlighted_amenities' => $highlightedAmenities,
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
     * Get amenities for specific room type based on room type ID
     */
    private function getAmenitiesForRoomType($roomTypeId, &$allAmenities, &$highlightedAmenities)
    {
        try {
            // Get all amenities for this room type from database
            $roomTypeAmenities = \DB::table('room_type_amenity as rta')
                ->join('amenities as a', 'rta.amenity_id', '=', 'a.amenity_id')
                ->where('rta.room_type_id', $roomTypeId)
                ->where('a.is_active', 1)
                ->select('a.*', 'rta.is_highlighted')
                ->get();

            $allAmenities = [];
            $highlightedAmenities = [];

            foreach ($roomTypeAmenities as $amenity) {
                $amenityData = [
                    'id' => $amenity->amenity_id,
                    'name' => trim($amenity->name),
                    'icon' => $amenity->icon ?: '🏨',
                    'category' => $amenity->category ?: 'general',
                    'description' => $amenity->description
                ];

                $allAmenities[] = $amenityData;

                if ($amenity->is_highlighted) {
                    $highlightedAmenities[] = $amenityData;
                }
            }

            // If no amenities found in database, fallback to basic default amenities
            if (empty($allAmenities)) {
                $allAmenities = [
                    ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '�', 'category' => 'basic'],
                    ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
                ];
                $highlightedAmenities = $allAmenities;
            }

        } catch (\Exception $e) {
            // Fallback in case of database error
            $allAmenities = [
                ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '📶', 'category' => 'basic'],
                ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
            ];
            $highlightedAmenities = $allAmenities;
            error_log("Error getting amenities for room type {$roomTypeId}: " . $e->getMessage());
        }
    }
}
