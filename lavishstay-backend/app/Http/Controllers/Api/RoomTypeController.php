<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RoomTypeController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }
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

            $data = $roomTypes->getCollection()->map(function ($roomType) use ($request) {
                $imagesList = RoomTypeImage::where('room_type_id', $roomType->room_type_id)
                    ->get(['image_id', 'room_type_id', 'image_url', 'image_path', 'alt_text', 'is_main'])
                    ->map(function ($img) {
                        return [
                            'id' => $img->image_id,
                            'room_type_id' => $img->room_type_id,
                            'image_path' => asset($img->image_path), // Remove the extra 'storage' prefix
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

                // Calculate adjusted price using PricingService
                $adjustedPrice = $this->calculateAdjustedPrice($roomType->room_type_id, $request);

                // Get amenities for this room type
                $allAmenities = [];
                $highlightedAmenities = [];
                $this->getAmenitiesForRoomType($roomType->room_type_id, $allAmenities, $highlightedAmenities);

                return [
                    'id' => $roomType->room_type_id,
                    'room_code' => $roomType->room_code,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'total_room' => $roomType->total_room,
                     'images' => $imagesList,
                    'main_image' => $mainImage,
                    'base_price' => $minPrice,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'avg_price' => $avgPrice,
                    'adjusted_price' => $adjustedPrice, // New field with dynamic pricing
                    'size' => $avgSize,
                    'avg_size' => $avgSize,
                    'rating' => $avgRating,
                    'avg_rating' => $avgRating,
                    'max_guests' => $maxGuests,
                    'common_views' => array_values($commonViews),
                    'available_floors' => array_values($floors),
                    'amenities' => $allAmenities,
                    'highlighted_amenities' => $highlightedAmenities,
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
                'message' => 'Có lỗi xảy ra khi lấy danh sách loại phòng ',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function calculateAdjustedPrice($roomTypeId, $request)
    {
        try {
            $roomType = RoomType::find($roomTypeId);
            if (!$roomType) {
                return 1200000; // Fallback price
            }

            $basePrice = $roomType->base_price;
            
            // Use provided date or default to tomorrow
            $targetDate = $request->date ?? $request->check_in_date ?? Carbon::now()->addDay()->format('Y-m-d');
            $date = Carbon::parse($targetDate);

            // Calculate night price using PricingService
            $nightPrice = $this->pricingService->calculateNightPrice(
                $roomTypeId,
                $date,
                $basePrice
            );

            return $nightPrice['adjusted_price'] ?? $basePrice;

        } catch (\Exception $e) {
            Log::error('Error calculating adjusted price: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'date' => $targetDate ?? 'not provided'
            ]);

            // Fallback to base price or hardcoded value
            $roomType = RoomType::find($roomTypeId);
            return $roomType ? $roomType->base_price : 1200000;
        }
    }
/**
     * Calculate pricing for room type over a date range
     */
    private function calculateRoomTypePricing($roomTypeId, $checkInDate, $checkOutDate, $days = 30)
    {
        try {
            $roomType = RoomType::find($roomTypeId);
            $basePrice = $roomType->base_price;

            // Calculate pricing for the specific date range if provided
            if ($checkInDate && $checkOutDate) {
                $pricingResult = $this->pricingService->calculatePrice(
                    $roomTypeId,
                    $checkInDate,
                    $checkOutDate,
                    $basePrice
                );

                $avgPrice = $pricingResult['average_price_per_night'];
                $minPrice = $basePrice; // Base price as minimum
                $maxPrice = $avgPrice; // Use average as max for now

                // Get min/max from breakdown if available
                if (isset($pricingResult['price_breakdown']) && !empty($pricingResult['price_breakdown'])) {
                    $prices = array_column($pricingResult['price_breakdown'], 'adjusted_price');
                    $minPrice = min($prices);
                    $maxPrice = max($prices);
                }

                return [
                    'base_price' => $basePrice,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'avg_price' => round($avgPrice, 0),
                    'pricing_info' => [
                        'total_price' => $pricingResult['total_price'],
                        'nights' => $pricingResult['nights'],
                        'has_adjustments' => !empty($pricingResult['price_breakdown']),
                        'date_range' => [
                            'check_in' => $checkInDate,
                            'check_out' => $checkOutDate
                        ]
                    ]
                ];
            }

            // Calculate pricing for next 30 days to get min/max/avg
            $startDate = Carbon::now()->addDay();
            $endDate = $startDate->copy()->addDays($days - 1);
            
            $prices = [];
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                $nightPrice = $this->pricingService->calculateNightPrice(
                    $roomTypeId,
                    $currentDate,
                    $basePrice
                );
                
                $adjustedPrice = $nightPrice['adjusted_price'] ?? $basePrice;
                $prices[] = $adjustedPrice;
                
                $currentDate->addDay();
            }

            $minPrice = !empty($prices) ? min($prices) : $basePrice;
            $maxPrice = !empty($prices) ? max($prices) : $basePrice;
            $avgPrice = !empty($prices) ? array_sum($prices) / count($prices) : $basePrice;

            return [
                'base_price' => $basePrice,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'avg_price' => round($avgPrice, 0),
                'pricing_info' => [
                    'calculation_period' => $days . ' days',
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'price_range' => $maxPrice - $minPrice,
                    'has_dynamic_pricing' => $maxPrice != $minPrice
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating room type pricing: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate
            ]);

            // Fallback to base price if pricing calculation fails
            $roomType = RoomType::find($roomTypeId);
            $basePrice = $roomType ? $roomType->base_price : 1200000;

            return [
                'base_price' => $basePrice,
                'min_price' => $basePrice,
                'max_price' => $basePrice,
                'avg_price' => $basePrice,
                'pricing_info' => [
                    'error' => 'Pricing calculation failed, using base price',
                    'fallback' => true
                ]
            ];
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

           $imagesList = RoomTypeImage::where('room_type_id', $roomType->room_type_id)
    ->get(['image_id', 'image_url', 'image_path', 'alt_text', 'is_main'])
    ->map(function ($img) {
        return [
            'id' => $img->image_id,
            'image_url' => asset($img->image_path), // Remove the extra 'storage' prefix
            'image_path' => $img->image_path,
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
