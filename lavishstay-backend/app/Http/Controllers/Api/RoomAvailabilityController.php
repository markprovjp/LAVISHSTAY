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
            ]);

            Log::info('Validated data: ', $validated);

            $checkInDate = $validated['check_in_date'];
            $checkOutDate = $validated['check_out_date'];

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
                ->join('bed_types as bt', 'r.bed_type_fixed', '=', 'bt.id') // Thêm JOIN với bed_types
                ->select([
                    'r.' . $roomIdColumn . ' as room_id',
                    'r.room_type_id',
                    'r.status',
                    'r.name as room_name', // Cột mới
                    'r.bed_type_fixed', // Cột mới
                    'bt.type_name as bed_type_name', // Lấy tên loại giường từ bed_types
                    'rt.name as room_type_name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area as size',
                    'rt.max_guests',
                    'rt.rating',
                    'rt.room_code'
                ]);

            Log::info('Base query built');

            // Thêm bộ lọc
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

            // Loại trừ phòng đã được đặt
            $query->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($checkInDate, $checkOutDate) {
                $subQuery->select('br.room_id')
                    ->from('booking_rooms as br')
                    ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                    ->whereIn('b.status', ['pending', 'confirmed'])
                    ->where('br.check_in_date', '<', $checkOutDate)
                    ->where('br.check_out_date', '>', $checkInDate)
                    ->whereNotNull('br.room_id');
            });

            Log::info('Added booking conflict filter');

            // Log truy vấn SQL
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('SQL Query: ' . $sql);
            Log::info('Bindings: ', $bindings);

            // Thực thi truy vấn
            $availableRooms = $query->get();
            Log::info('Query executed. Found rooms: ' . $availableRooms->count());

            if ($availableRooms->isEmpty()) {
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

            // Xử lý kết quả
            $result = [];
            $groupedRooms = $availableRooms->groupBy('room_type_id');

            foreach ($groupedRooms as $roomTypeId => $rooms) {
                $firstRoom = $rooms->first();

                // Tính giá điều chỉnh
                $adjustedPrice = $this->calculateAdjustedPrice($roomTypeId, $request);
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
                    'room_name' => $firstRoom->room_name, // Thêm tên phòng
                    'bed_type_name' => $firstRoom->bed_type_name, // Thêm loại giường
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

                // Thiết lập ảnh chính
                if (!empty($roomTypeData['images'])) {
                    $mainImage = collect($roomTypeData['images'])->firstWhere('is_main', true);
                    $roomTypeData['main_image'] = $mainImage ?: $roomTypeData['images'][0];
                }

                foreach ($rooms as $room) {
                    $roomTypeData['available_rooms'][] = [
                        'room_id' => $room->room_id,
                        'room_name' => $room->room_name, // Thêm tên phòng
                        'bed_type_name' => $room->bed_type_name, // Thêm loại giường
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
            $columnNames = array_map(function ($col) {
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
                    $tableInfo[$table]['columns'] = array_map(function ($col) {
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
                ->whereNotIn('r.room_id', function ($subQuery) use ($checkInDate, $checkOutDate) {
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


























    /**
     * Get available room packages for customers
     * Returns room packages with calculated rooms needed and total pricing
     */
    public function getAvailablePackages(Request $request): JsonResponse
    {
        try {
            Log::info('=== RoomAvailabilityController@getAvailablePackages START ===');
            Log::info('Request data: ', $request->all());

            $validated = $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'guest_count' => 'required|integer|min:1',
            ]);

            $checkInDate = $validated['check_in_date'];
            $checkOutDate = $validated['check_out_date'];
            $guestCount = $validated['guest_count'];

            // Calculate rooms needed (2 guests per room)
            $roomsNeeded = ceil($guestCount / 2);

            Log::info("Search criteria: check_in={$checkInDate}, check_out={$checkOutDate}, guests={$guestCount}, rooms_needed={$roomsNeeded}");

            // Calculate nights for pricing
            $checkIn = \Carbon\Carbon::parse($checkInDate);
            $checkOut = \Carbon\Carbon::parse($checkOutDate);
            $nights = $checkIn->diffInDays($checkOut);

            // Get available room types with sufficient rooms
            $availableRoomTypes = $this->getAvailableRoomTypesForPackages($checkInDate, $checkOutDate, $roomsNeeded);

            if ($availableRoomTypes->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Không tìm thấy loại phòng nào đủ yêu cầu cho số khách và thời gian này',
                    'summary' => [
                        'total_packages' => 0,
                        'search_criteria' => $validated,
                        'rooms_needed' => $roomsNeeded,
                        'nights' => $nights
                    ]
                ]);
            }

            // Get packages for available room types
            $packages = $this->getRoomTypePackagesWithServices($availableRoomTypes->pluck('room_type_id')->toArray());

            if ($packages->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Không tìm thấy gói dịch vụ nào cho các loại phòng có sẵn',
                    'summary' => [
                        'total_packages' => 0,
                        'search_criteria' => $validated,
                        'rooms_needed' => $roomsNeeded,
                        'nights' => $nights
                    ]
                ]);
            }

            // Build result with full information
            $result = [];
            $packagesByRoomType = $packages->groupBy('room_type_id');

            foreach ($availableRoomTypes as $roomType) {
                $roomTypeId = $roomType->room_type_id;
                $roomTypePackages = $packagesByRoomType->get($roomTypeId, collect());

                if ($roomTypePackages->isEmpty()) {
                    continue;
                }

                // Get adjusted price for this room type
                $adjustedPrice = $this->calculateAdjustedPrice($roomTypeId, $request);

                // Get additional room type information
                $images = $this->getRoomTypeImages($roomTypeId);
                $allAmenities = [];
                $highlightedAmenities = [];
                $this->getAmenitiesForRoomType($roomTypeId, $allAmenities, $highlightedAmenities);

                // Process each package for this room type
                $packageOptions = [];
                foreach ($roomTypePackages as $package) {
                    // Calculate total price: (adjusted_price + package_modifier) * rooms_needed * nights
                    $pricePerRoomPerNight = $adjustedPrice + $package->price_modifier_vnd;
                    $totalPackagePrice = $pricePerRoomPerNight * $roomsNeeded * $nights;

                    $packageOptions[] = [
                        'package_id' => $package->package_id,
                        'package_name' => $package->package_name,
                        'package_description' => $package->package_description,
                        'price_modifier_vnd' => $package->price_modifier_vnd,
                        'price_per_room_per_night' => $pricePerRoomPerNight,
                        'total_package_price' => $totalPackagePrice,
                        'services' => $this->getPackageServices($package->package_id),
                        'pricing_breakdown' => [
                            'base_price_per_night' => $roomType->base_price,
                            'adjusted_price_per_night' => $adjustedPrice,
                            'package_modifier' => $package->price_modifier_vnd,
                            'final_price_per_room_per_night' => $pricePerRoomPerNight,
                            'rooms_needed' => $roomsNeeded,
                            'nights' => $nights,
                            'total_price' => $totalPackagePrice,
                            'currency' => 'VND'
                        ]
                    ];
                }

                // Sort packages by total price (ascending)
                usort($packageOptions, function ($a, $b) {
                    return $a['total_package_price'] <=> $b['total_package_price'];
                });

                // Set main image
                $mainImage = null;
                if (!empty($images)) {
                    $mainImage = collect($images)->firstWhere('is_main', true) ?: $images[0];
                }

                $result[] = [
                    'room_type_id' => $roomTypeId,
                    'room_type_name' => $roomType->room_type_name,
                    'bed_type_name' => $roomType->bed_type_names,
                    'room_code' => $roomType->room_code,
                    'bed_type_name' => $roomType->bed_type_names,
                    'description' => $roomType->description,
                    'size' => $roomType->size,
                    'max_guests' => $roomType->max_guests,
                    'rating' => $roomType->rating,
                    'base_price' => $roomType->base_price,
                    'adjusted_price' => $adjustedPrice,
                    'available_rooms' => $roomType->available_rooms,
                    'rooms_needed' => $roomsNeeded,
                    'images' => $images,
                    'main_image' => $mainImage,
                    'amenities' => $allAmenities,
                    'highlighted_amenities' => $highlightedAmenities,
                    'package_options' => $packageOptions,
                    'cheapest_package_price' => $packageOptions[0]['total_package_price'] ?? 0,
                    'search_criteria' => [
                        'guest_count' => $guestCount,
                        'check_in_date' => $checkInDate,
                        'check_out_date' => $checkOutDate,
                        'nights' => $nights
                    ]
                ];
            }

            // Sort result by cheapest package price
            usort($result, function ($a, $b) {
                return $a['cheapest_package_price'] <=> $b['cheapest_package_price'];
            });

            Log::info('=== RoomAvailabilityController@getAvailablePackages SUCCESS ===');
            Log::info('Found ' . count($result) . ' room types with packages');

            return response()->json([
                'success' => true,
                'data' => $result,
                'summary' => [
                    'total_room_types' => count($result),
                    'total_packages' => array_sum(array_map(function ($rt) {
                        return count($rt['package_options']);
                    }, $result)),
                    'search_criteria' => $validated,
                    'rooms_needed' => $roomsNeeded,
                    'nights' => $nights
                ],
                'message' => 'Danh sách gói phòng có sẵn đã được tải thành công'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in getAvailablePackages: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in getAvailablePackages ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tìm kiếm gói phòng',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    /**
     * Get available room types with sufficient rooms for packages
     */
  private function getAvailableRoomTypesForPackages($checkInDate, $checkOutDate, $roomsNeeded)
{
    try {
        $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
        if (empty($roomTableExists)) {
            $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
            if (empty($roomTableExists)) {
                throw new \Exception('No room table found');
            }
            $roomTable = 'rooms';
            $roomIdColumn = 'id';
        } else {
            $roomTable = 'room';
            $roomIdColumn = 'room_id';
        }

        // Build the query using raw SQL similar to your provided query
        $sql = "
        WITH booking_summary AS (
            SELECT 
                room_type_id,
                SUM(quantity) AS booked_quantity
            FROM booking
            WHERE 
                status IN ('pending', 'confirmed')
                AND check_in_date < ?
                AND check_out_date > ?
            GROUP BY room_type_id
        ),
        available_rooms AS (
            SELECT 
                rt.room_type_id,
                rt.name AS room_type_name,
                rt.room_code,
                rt.description,
                rt.base_price,
                rt.room_area as size,
                rt.max_guests,
                rt.rating,
                COUNT(r.{$roomIdColumn}) AS total_rooms,
                COALESCE(bs.booked_quantity, 0) AS booked_rooms,
                COUNT(r.{$roomIdColumn}) - COALESCE(bs.booked_quantity, 0) AS available_rooms,
                GROUP_CONCAT(DISTINCT bt.type_name ORDER BY bt.type_name SEPARATOR ', ') AS bed_type_names
            FROM room_types rt
            JOIN {$roomTable} r ON r.room_type_id = rt.room_type_id AND r.status = 'available'
            LEFT JOIN bed_types bt ON bt.id = r.bed_type_fixed
            LEFT JOIN booking_summary bs ON rt.room_type_id = bs.room_type_id
            WHERE rt.is_active = 1
            GROUP BY rt.room_type_id, rt.name, rt.room_code, rt.description, rt.base_price, rt.room_area, rt.max_guests, rt.rating, bs.booked_quantity
            HAVING COUNT(r.{$roomIdColumn}) - COALESCE(bs.booked_quantity, 0) >= ?
        )
        SELECT * FROM available_rooms
        ORDER BY room_type_id
        ";

        $availableRoomTypes = DB::select($sql, [$checkOutDate, $checkInDate, $roomsNeeded]);

        Log::info("Found " . count($availableRoomTypes) . " room types with at least {$roomsNeeded} available rooms");

        return collect($availableRoomTypes);
    } catch (\Exception $e) {
        Log::error('Error getting available room types for packages: ' . $e->getMessage());
        throw $e;
    }
}

    /**
     * Get room type packages with services
     */
    private function getRoomTypePackagesWithServices($roomTypeIds)
    {
        try {
            if (empty($roomTypeIds)) {
                return collect();
            }

            $packages = DB::table('room_type_package as rtp')
                ->join('room_types as rt', 'rt.room_type_id', '=', 'rtp.room_type_id')
                ->whereIn('rtp.room_type_id', $roomTypeIds)
                ->where('rtp.is_active', 1)
                ->select([
                    'rtp.package_id',
                    'rtp.room_type_id',
                    'rtp.name as package_name',
                    'rtp.description as package_description',
                    'rtp.price_modifier_vnd',
                    'rt.base_price'
                ])
                ->orderBy('rtp.room_type_id')
                ->orderBy('rtp.price_modifier_vnd')
                ->get();

            Log::info("Found " . $packages->count() . " packages for room types: " . implode(', ', $roomTypeIds));

            return $packages;
        } catch (\Exception $e) {
            Log::error('Error getting room type packages: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get services for a specific package
     */
    private function getPackageServices($packageId)
    {
        try {
            $services = DB::table('room_type_package_services as rtps')
                ->join('services as s', 's.service_id', '=', 'rtps.service_id')
                ->where('rtps.package_id', $packageId)
                ->where('s.is_active', 1)
                ->select([
                    's.service_id',
                    's.name',
                    's.description',
                    's.price_vnd',
                    's.unit',
                    's.is_active',
                ])
                ->orderBy('s.name')
                ->get();

            return $services->map(function ($service) {
                return [
                    'service_id' => $service->service_id,
                    'name' => $service->name,
                        'description' => $service->description,
                    'category' => $service->category ?: 'general'
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error("Error getting services for package {$packageId}: " . $e->getMessage());
            return [];
        }
    }
}
