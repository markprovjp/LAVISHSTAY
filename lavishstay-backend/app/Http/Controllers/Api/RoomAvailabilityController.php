<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoomAvailabilityService;
use App\Services\PricingService;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomAvailabilityController extends Controller
{
    protected $roomAvailabilityService;
    protected $pricingService;

    public function __construct(
        RoomAvailabilityService $roomAvailabilityService,
        PricingService $pricingService
    ) {
        $this->roomAvailabilityService = $roomAvailabilityService;
        $this->pricingService = $pricingService;
    }

    public function getAvailableRooms(Request $request): JsonResponse
    {
        try {
            Log::info('=== RoomAvailabilityController@getAvailableRooms START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Request data: ', $request->all());

            $validated = $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'guest_count' => 'required|integer|min:1',
                'room_type_id' => 'nullable|integer|exists:room_types,room_type_id'
            ]);

            Log::info('Validated data: ', $validated);

            $checkInDate = $validated['check_in_date'];
            $checkOutDate = $validated['check_out_date'];
            $guestCount = $validated['guest_count'];
            $roomTypeId = $validated['room_type_id'] ?? null;

            // Kiểm tra bảng room tồn tại
            $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
            if (empty($roomTableExists)) {
                $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
                if (empty($roomTableExists)) {
                    Log::error('No room table found');
                    return response()->json([
                        'success' => false,
                        'message' => 'Bảng room không tồn tại trong database'
                    ], 500);
                }
                $roomTable = 'rooms';
                $roomIdColumn = 'id';
            } else {
                $roomTable = 'room';
                $roomIdColumn = 'room_id';
            }

            Log::info('Using table: ' . $roomTable . ', ID column: ' . $roomIdColumn);

            // Kiểm tra dữ liệu cơ bản
            $totalRooms = DB::table($roomTable)->count();
            $totalRoomTypes = DB::table('room_types')->count();
            
            Log::info("Total rooms: $totalRooms, Total room types: $totalRoomTypes");

            if ($totalRooms == 0) {
                Log::warning('No rooms found in database');
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Không có phòng nào trong hệ thống',
                    'debug' => [
                        'total_rooms' => $totalRooms,
                        'total_room_types' => $totalRoomTypes,
                        'table_used' => $roomTable
                    ]
                ]);
            }

            if ($totalRoomTypes == 0) {
                Log::warning('No room types found in database');
                return response()->json([
                    'success' => false,
                    'message' => 'Không có loại phòng nào trong hệ thống'
                ], 500);
            }

            // Build query với logic kiểm tra booking
            $query = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->select([
                    'r.' . $roomIdColumn . ' as room_id',
                    'r.room_type_id',
                    'r.status',
                    'rt.name as room_type_name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area as size',
                    'rt.max_guests',
                    'rt.rating',
                    'rt.room_code'
                ]);

            Log::info('Base query built');

            // Add filters step by step
            try {
                $roomColumns = DB::select("SHOW COLUMNS FROM $roomTable LIKE 'status'");
                if (!empty($roomColumns)) {
                    $query->where('r.status', 'available');
                    Log::info('Added room status filter: available');
                }
            } catch (\Exception $e) {
                Log::warning('Could not check room status column: ' . $e->getMessage());
            }

            try {
                $roomTypeColumns = DB::select("SHOW COLUMNS FROM room_types LIKE 'is_active'");
                if (!empty($roomTypeColumns)) {
                    $query->where('rt.is_active', 1);
                    Log::info('Added room type is_active filter');
                }
            } catch (\Exception $e) {
                Log::warning('Could not check is_active column: ' . $e->getMessage());
            }

            $query->where('rt.max_guests', '>=', $guestCount);
            Log::info("Added guest count filter: >= $guestCount");

            if ($roomTypeId) {
                $query->where('rt.room_type_id', $roomTypeId);
                Log::info("Added room_type_id filter: $roomTypeId");
            }

            // Thêm logic loại trừ phòng đã được đặt
            $query->whereNotIn('r.' . $roomIdColumn, function($subQuery) use ($checkInDate, $checkOutDate) {
                $subQuery->select('br.room_id')
                    ->from('booking_rooms as br')
                    ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                    ->whereIn('b.status', ['pending', 'confirmed'])
                    ->where('br.check_in_date', '<', $checkOutDate)
                    ->where('br.check_out_date', '>', $checkInDate)
                    ->whereNotNull('br.room_id');
            });

            Log::info('Added booking conflict filter');

            // Log the SQL query
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('SQL Query: ' . $sql);
            Log::info('Bindings: ', $bindings);

            // Execute query
            $availableRooms = $query->get();
            Log::info('Query executed. Found rooms: ' . $availableRooms->count());

            if ($availableRooms->isEmpty()) {
                // Debug: Check what rooms exist without filters
                $allRooms = DB::table($roomTable . ' as r')
                    ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                    ->select('r.*', 'rt.name', 'rt.max_guests')
                    ->get();
                
                Log::info('All rooms in database: ', $allRooms->toArray());
                
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Không tìm thấy phòng trống phù hợp với yêu cầu',
                    'debug' => [
                        'search_criteria' => $validated,
                        'total_rooms_in_db' => $allRooms->count(),
                        'sample_rooms' => $allRooms->take(3)->toArray(),
                        'sql_query' => $sql,
                        'bindings' => $bindings
                    ]
                ]);
            }

            // Process results
            $result = [];
            $groupedRooms = $availableRooms->groupBy('room_type_id');

            foreach ($groupedRooms as $roomTypeId => $rooms) {
                $firstRoom = $rooms->first();


               
                // Calculate adjusted price using PricingService
                $adjustedPrice = $this->calculateAdjustedPrice($roomTypeId, $request);
                // Calculate pricing
                // Get amenities based on room type ID
                $allAmenities = [];
                $highlightedAmenities = [];

                $this->getAmenitiesForRoomType($roomTypeId, $allAmenities, $highlightedAmenities);
                $checkIn = \Carbon\Carbon::parse($checkInDate);
                $checkOut = \Carbon\Carbon::parse($checkOutDate);
                $nights = $checkIn->diffInDays($checkOut);
                $totalPrice = $firstRoom->base_price * $nights;
                
                $roomTypeData = [
                    'room_type_id' => $roomTypeId,
                    'room_code' => $firstRoom->room_code,
                    'name' => $firstRoom->room_type_name,
                    'description' => $firstRoom->description,
                    'base_price' => $firstRoom->base_price,
                    'adjusted_price' => $adjustedPrice,
                    'size' => $firstRoom->size,
                    'max_guests' => $firstRoom->max_guests,
                    'rating' => $firstRoom->rating,
                    'images' => $this->getRoomTypeImages($roomTypeId),
                    'main_image' => null,
                    'amenities' => $allAmenities,
                    'highlighted_amenities' => $highlightedAmenities,
                    'available_room_count' => $rooms->count(),
                    'available_rooms' => [],
                    'pricing_summary' => [
                        'nights' => $nights,
                        'price_per_night' => $firstRoom->base_price,
                        'total_price' => $totalPrice,
                        'currency' => 'VND'
                    ]
                ];

                // Set main image
                if (!empty($roomTypeData['images'])) {
                    $mainImage = collect($roomTypeData['images'])->firstWhere('is_main', true);
                    $roomTypeData['main_image'] = $mainImage ?: $roomTypeData['images'][0];
                }

                // Add room details
                foreach ($rooms as $room) {
                    $roomTypeData['available_rooms'][] = [
                        'room_id' => $room->room_id,
                        'room_status' => $room->status
                    ];
                }

                $result[] = $roomTypeData;
            }

            Log::info('=== RoomAvailabilityController@getAvailableRooms SUCCESS ===');

            return response()->json([
                'success' => true,
                'data' => $result,
                'summary' => [
                    'total_room_types' => count($result),
                    'total_available_rooms' => $availableRooms->count(),
                    'search_criteria' => $validated
                ],
                'message' => 'Danh sách phòng trống đã được tải thành công'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in getAvailableRooms ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tìm kiếm phòng trống',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

     private function getRoomTypeImages($roomTypeId): array
    {
        try {
            // Kiểm tra các tên bảng có thể có
            $possibleTables = ['room_type_images', 'room_type_image', 'roomtype_images'];
            $tableName = null;
            
            foreach ($possibleTables as $table) {
                $tableExists = DB::select("SHOW TABLES LIKE '$table'");
                if (!empty($tableExists)) {
                    $tableName = $table;
                    break;
                }
            }

            if (!$tableName) {
                Log::warning('No room type images table found');
                return [];
            }

            Log::info("Using images table: $tableName for room_type_id: $roomTypeId");

            // Kiểm tra cấu trúc bảng
            $columns = DB::select("SHOW COLUMNS FROM $tableName");
            $columnNames = array_map(function($col) {
                return $col->Field;
            }, $columns);
            
            Log::info("Available columns in $tableName: " . implode(', ', $columnNames));

            // Xác định tên cột ID
            $imageIdColumn = 'image_id';
            if (in_array('id', $columnNames)) {
                $imageIdColumn = 'id';
            }

            // Xác định tên cột path
            $pathColumn = 'image_path';
            if (in_array('path', $columnNames)) {
                $pathColumn = 'path';
            } elseif (in_array('url', $columnNames)) {
                $pathColumn = 'url';
            } elseif (in_array('image_url', $columnNames)) {
                $pathColumn = 'image_url';
            }

            $images = DB::table($tableName)
                ->where('room_type_id', $roomTypeId)
                ->get();

            Log::info("Found " . $images->count() . " images for room_type_id: " . $roomTypeId);

            if ($images->isEmpty()) {
                // Debug: Kiểm tra có data nào trong bảng không
                $totalImages = DB::table($tableName)->count();
                Log::info("Total images in table: $totalImages");
                
                if ($totalImages > 0) {
                    $sampleImages = DB::table($tableName)->limit(3)->get();
                    Log::info("Sample images: ", $sampleImages->toArray());
                }
            }

            return $images->map(function ($img) use ($imageIdColumn, $pathColumn) {
                return [
                    'id' => $img->$imageIdColumn,
                    'room_type_id' => $img->room_type_id,
                    'image_url' => asset($img->$pathColumn),
                    'alt_text' => $img->alt_text ?? '',
                    'is_main' => $img->is_main ?? false,
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error getting room type images: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return [];
        }
    }
    private function getRoomTypeHighlightedAmenities($roomTypeId): array
    {
        try {
            $allAmenities = [];
            $highlightedAmenities = [];
            $this->getAmenitiesForRoomType($roomTypeId, $allAmenities, $highlightedAmenities);
            
            Log::info("Found " . count($highlightedAmenities) . " highlighted amenities for room_type_id: " . $roomTypeId);
            
            return $highlightedAmenities;
        } catch (\Exception $e) {
            Log::error('Error getting room type highlighted amenities: ' . $e->getMessage());
            return [];
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

            // Fallback to base price
            $roomType = RoomType::find($roomTypeId);
            return $roomType ? $roomType->base_price : 1200000;
        }
    }

    

    /**
     * Get main image from images array
     */
    private function getMainImage(array $images): ?array
    {
        // Find main image (is_main = true)
        foreach ($images as $img) {
            if ($img['is_main']) {
                return $img;
            }
        }
        
        // If no main image, return first image
        return !empty($images) ? $images[0] : null;
    }

    /**
     * Get room type amenities - FIX: Truyền đúng tham số
     */
    private function getRoomTypeAmenities($roomTypeId): array
    {
        try {
            $allAmenities = [];
            $highlightedAmenities = [];
            $this->getAmenitiesForRoomType($roomTypeId, $allAmenities, $highlightedAmenities);
            
            Log::info("Found " . count($allAmenities) . " amenities for room_type_id: " . $roomTypeId);
            
            return $allAmenities;
        } catch (\Exception $e) {
            Log::error('Error getting room type amenities: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Get amenities for room type (copied from RoomTypeController)
     */
    private function getAmenitiesForRoomType($roomTypeId, &$allAmenities, &$highlightedAmenities)
    {
        try {
            // Kiểm tra các bảng tồn tại
            $roomTypeAmenityExists = DB::select("SHOW TABLES LIKE 'room_type_amenity'");
            $amenitiesExists = DB::select("SHOW TABLES LIKE 'amenities'");

            if (empty($roomTypeAmenityExists)) {
                Log::warning('room_type_amenity table does not exist');
                // Fallback amenities
                $allAmenities = [
                    ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '📶', 'category' => 'basic'],
                    ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
                ];
                $highlightedAmenities = $allAmenities;
                return;
            }

            if (empty($amenitiesExists)) {
                Log::warning('amenities table does not exist');
                // Fallback amenities
                $allAmenities = [
                    ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '📶', 'category' => 'basic'],
                    ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
                ];
                $highlightedAmenities = $allAmenities;
                return;
            }

            // Get all amenities for this room type from database
            $roomTypeAmenities = DB::table('room_type_amenity as rta')
                ->join('amenities as a', 'rta.amenity_id', '=', 'a.amenity_id')
                ->where('rta.room_type_id', $roomTypeId)
                ->where('a.is_active', 1)
                ->select('a.*', 'rta.is_highlighted')
                ->get();

            Log::info("Raw amenities query result for room_type_id $roomTypeId: " . $roomTypeAmenities->count());

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
                Log::warning("No amenities found for room_type_id: $roomTypeId, using fallback");
                $allAmenities = [
                    ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '📶', 'category' => 'basic'],
                    ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
                ];
                $highlightedAmenities = $allAmenities;
            }
        } catch (\Exception $e) {
            // Fallback in case of database error
            Log::error("Error getting amenities for room type {$roomTypeId}: " . $e->getMessage());
            $allAmenities = [
                ['id' => 0, 'name' => 'WiFi miễn phí', 'icon' => '📶', 'category' => 'basic'],
                ['id' => 0, 'name' => 'Điều hòa không khí', 'icon' => '❄️', 'category' => 'basic'],
            ];
            $highlightedAmenities = $allAmenities;
        }
    }

    public function debugImagesAndAmenities(Request $request): JsonResponse
    {
        try {
            $roomTypeId = $request->get('room_type_id', 5);

            // Check tables exist
            $tables = [
                'room_type_images' => DB::select("SHOW TABLES LIKE 'room_type_images'"),
                'room_type_amenity' => DB::select("SHOW TABLES LIKE 'room_type_amenity'"),
                'amenities' => DB::select("SHOW TABLES LIKE 'amenities'")
            ];

            $result = [
                'room_type_id' => $roomTypeId,
                'tables_exist' => []
            ];

            foreach ($tables as $tableName => $exists) {
                $result['tables_exist'][$tableName] = !empty($exists);
            }

            // Check images
            if ($result['tables_exist']['room_type_images']) {
                $images = DB::table('room_type_images')
                    ->where('room_type_id', $roomTypeId)
                    ->get();
                $result['images'] = [
                    'count' => $images->count(),
                    'data' => $images->toArray()
                ];
            }

            // Check amenities
            if ($result['tables_exist']['room_type_amenity'] && $result['tables_exist']['amenities']) {
                $amenities = DB::table('room_type_amenity as rta')
                    ->join('amenities as a', 'rta.amenity_id', '=', 'a.amenity_id')
                    ->where('rta.room_type_id', $roomTypeId)
                    ->select('a.*', 'rta.is_highlighted')
                    ->get();
                $result['amenities'] = [
                    'count' => $amenities->count(),
                    'data' => $amenities->toArray()
                ];
            }

            // Test the actual functions
            $result['function_results'] = [
                'images' => $this->getRoomTypeImages($roomTypeId),
                'amenities' => $this->getRoomTypeAmenities($roomTypeId),
                'highlighted_amenities' => $this->getRoomTypeHighlightedAmenities($roomTypeId)
            ];

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Debug method to check database structure
     */
    public function debugDatabase(): JsonResponse
    {
        try {
            $tables = [
                'room',
                'rooms',
                'room_types',
                'floors',
                'room_option',
                'bed_types',
                'meal_types',
                'deposit_policies',
                'cancellation_policies',
                'room_type_package',
                'booking',
                'booking_rooms',
                'room_type_images',
                'amenities',
                'room_type_amenities'
            ];

            $tableInfo = [];
            foreach ($tables as $table) {
                $exists = DB::select("SHOW TABLES LIKE '$table'");
                $tableInfo[$table] = [
                    'exists' => !empty($exists),
                    'count' => !empty($exists) ? DB::table($table)->count() : 0
                ];

                if (!empty($exists)) {
                    $columns = DB::select("SHOW COLUMNS FROM $table");
                    $tableInfo[$table]['columns'] = array_map(function($col) {
                        return $col->Field;
                    }, $columns);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $tableInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug method to check booking conflicts
     */
    public function debugBookingConflicts(Request $request): JsonResponse
    {
        try {
            $checkInDate = $request->get('check_in_date', '2025-07-01');
            $checkOutDate = $request->get('check_out_date', '2025-07-02');
            $roomTypeId = $request->get('room_type_id', 5);

            // Get all rooms of this type
            $allRooms = DB::table('room as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('rt.room_type_id', $roomTypeId)
                ->select('r.room_id', 'r.status', 'rt.name')
                ->get();

            // Get conflicting bookings
            $conflictingBookings = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('rt.room_type_id', $roomTypeId)
                ->whereIn('b.status', ['pending', 'confirmed'])
                ->where('br.check_in_date', '<', $checkOutDate)
                ->where('br.check_out_date', '>', $checkInDate)
                ->whereNotNull('br.room_id')
                ->select(
                    'br.room_id',
                    'br.check_in_date',
                    'br.check_out_date',
                    'b.status as booking_status',
                    'b.booking_id'
                )
                ->get();

            // Get available rooms using the same logic as main function
            $availableRooms = DB::table('room as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('r.status', 'available')
                ->where('rt.is_active', 1)
                ->where('rt.room_type_id', $roomTypeId)
                ->whereNotIn('r.room_id', function($subQuery) use ($checkInDate, $checkOutDate) {
                    $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['pending', 'confirmed'])
                        ->where('br.check_in_date', '<', $checkOutDate)
                        ->where('br.check_out_date', '>', $checkInDate)
                        ->whereNotNull('br.room_id');
                })
                ->select('r.room_id', 'r.status')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'search_criteria' => [
                        'check_in_date' => $checkInDate,
                        'check_out_date' => $checkOutDate,
                        'room_type_id' => $roomTypeId
                    ],
                    'all_rooms_of_type' => $allRooms->toArray(),
                    'conflicting_bookings' => $conflictingBookings->toArray(),
                    'available_rooms' => $availableRooms->toArray(),
                    'summary' => [
                        'total_rooms_of_type' => $allRooms->count(),
                        'conflicting_rooms' => $conflictingBookings->count(),
                        'available_rooms' => $availableRooms->count()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

