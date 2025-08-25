<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            Log::info('RoomController@index called');
            
            // Kiểm tra bảng room tồn tại
            $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
            if (empty($roomTableExists)) {
                $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
                if (empty($roomTableExists)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bảng room không tồn tại',
                        'data' => []
                    ], 404);
                }
                $roomTable = 'rooms';
            } else {
                $roomTable = 'room';
            }

            // Sử dụng DB query thay vì Eloquent để tránh lỗi model
            // Allow frontend to request booking status for a specific date (defaults to today)
            // Frontend often sends a date range as `dateRange.0` / `dateRange.1`. Prefer that if present.
            $statusDate = $request->get('status_date') ?? $request->get('dateRange.0') ?? Carbon::now()->toDateString();

            $rooms = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->select([
                    'r.room_id as id',
                    'r.room_type_id',
                    'r.status',
                    'rt.name as room_type_name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area as size',
                    'rt.max_guests',
                    // include bed type name for frontend display
                    'rt.bed_type as bed_type_name'
                ])
                ->paginate(15);

            // Attach current booking (if any) to each room for the given date so FE can derive effective status
            $roomItems = $rooms->items();
            $roomIds = array_map(function ($r) { return $r->id; }, $roomItems);

            if (!empty($roomIds)) {
        // Find bookings that overlap the status_date (use blocking statuses)
    $blocking = \App\Models\Booking::getBlockingStatusesLower();
    $placeholders = implode(',', array_fill(0, count($blocking), '?'));
    $bookings = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->whereIn('br.room_id', $roomIds)
            ->whereRaw('LOWER(b.status) IN (' . $placeholders . ')', $blocking)
            ->where('br.check_in_date', '<=', $statusDate)
            ->where('br.check_out_date', '>', $statusDate)
            ->whereNotNull('br.room_id')
            ->select([
        'br.room_id',
        'b.booking_id',
        'b.booking_code',
        'b.status as booking_status',
        'br.check_in_date',
        'br.check_out_date'
            ])
            ->orderBy('br.created_at', 'desc')
            ->get();

                $bookingMap = [];
                foreach ($bookings as $bk) {
                    // keep the first (most recent) booking record per room
                    if (!isset($bookingMap[$bk->room_id])) {
                        $bookingMap[$bk->room_id] = $bk;
                    }
                }

                // mutate room items to include booking info
                foreach ($roomItems as &$room) {
                    $bk = $bookingMap[$room->id] ?? null;
                    // Provide a `booking_info` object matching frontend expectations when possible
                    if ($bk) {
                        $room->booking_info = [
                            'check_in' => $bk->check_in_date,
                            'check_out' => $bk->check_out_date,
                            // backend booking status is authoritative
                            'status' => $bk->booking_status,
                            // keep some identifiers for debugging/use
                            'booking_id' => $bk->booking_id,
                            'booking_code' => $bk->booking_code ?? null,
                        ];
                    } else {
                        $room->booking_info = null;
                    }

                    // keep backward-compatible fields as well
                    $room->current_booking = $bk;
                    $room->booking_status = $bk->booking_status ?? null;
                    $room->needs_cleaning = $room->booking_status && strtolower($room->booking_status) === 'cleaning';
                }
                unset($room);
            }

            return response()->json([
                'success' => true,
                'data' => $roomItems,
                'pagination' => [
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'per_page' => $rooms->perPage(),
                    'total' => $rooms->total(),
                    'from' => $rooms->firstItem(),
                    'to' => $rooms->lastItem()
                ],
                'message' => 'Danh sách phòng đã được tải thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('RoomController@index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
    public function show($id): JsonResponse
    {
        try {
            Log::info('RoomController@show called with ID: ' . $id);
            
            // Kiểm tra bảng room tồn tại
            $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
            if (empty($roomTableExists)) {
                $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
                if (empty($roomTableExists)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bảng room không tồn tại',
                    ], 404);
                }
                $roomTable = 'rooms';
            } else {
                $roomTable = 'room';
            }

            // Sử dụng DB query
            $room = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->select([
                    'r.room_id as id',
                    'r.room_type_id',
                    'r.status',
                    'rt.name as room_type_name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area as size',
                    'rt.max_guests',
                    'rt.rating'
                ])
                ->where('r.room_id', $id)
                ->first();

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phòng'
                ], 404);
            }

            // Get room images
            $images = $this->getRoomImages($room->room_type_id);
            
            // Get amenities
            $amenities = $this->getRoomAmenities($room->room_type_id);

            $roomData = [
                'id' => $room->id,
                'room_type_id' => $room->room_type_id,
                'status' => $room->status,
                'room_type' => [
                    'name' => $room->room_type_name,
                    'description' => $room->description,
                    'base_price' => $room->base_price,
                    'size' => $room->size,
                    'max_guests' => $room->max_guests,
                    'rating' => $room->rating
                ],
                'images' => $images,
                'amenities' => $amenities
            ];

            return response()->json([
                'success' => true,
                'data' => $roomData,
                'message' => 'Thông tin phòng đã được tải thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('RoomController@show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải thông tin phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function roomsByType($roomTypeId): JsonResponse
    {
        try {
            Log::info('RoomController@roomsByType called with room_type_id: ' . $roomTypeId);
            
            // Kiểm tra bảng room tồn tại
            $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
            if (empty($roomTableExists)) {
                $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
                if (empty($roomTableExists)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bảng room không tồn tại',
                        'data' => []
                    ], 404);
                }
                $roomTable = 'rooms';
            } else {
                $roomTable = 'room';
            }

            // Kiểm tra room type tồn tại
            $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại phòng',
                    'data' => []
                ], 404);
            }

            // Lấy danh sách phòng theo loại
            $rooms = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->select([
                    'r.room_id as id',
                    'r.room_type_id',
                    'r.status',
                    'rt.name as room_type_name',
                    'rt.description',
                    'rt.base_price',
                    'rt.room_area as size',
                    'rt.max_guests'
                ])
                ->where('r.room_type_id', $roomTypeId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rooms,
                'message' => 'Danh sách phòng theo loại đã được tải thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('RoomController@roomsByType error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách phòng theo loại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function roomsByTypeSlug($slug): JsonResponse
    {
        try {
            Log::info('RoomController@roomsByTypeSlug called with slug: ' . $slug);
            
            // Tìm room type theo slug
            $roomType = DB::table('room_types')->where('slug', $slug)->first();
            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại phòng với slug: ' . $slug,
                    'data' => []
                ], 404);
            }

            return $this->roomsByType($roomType->room_type_id);

        } catch (\Exception $e) {
            Log::error('RoomController@roomsByTypeSlug error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách phòng theo slug',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCalendarData($roomId, Request $request): JsonResponse
    {
        try {
            Log::info('RoomController@getCalendarData called with room_id: ' . $roomId);
            
            $month = $request->get('month', date('Y-m'));
            $startDate = Carbon::parse($month . '-01')->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            // Kiểm tra phòng tồn tại
            $roomTableExists = DB::select("SHOW TABLES LIKE 'room'");
            if (empty($roomTableExists)) {
                $roomTableExists = DB::select("SHOW TABLES LIKE 'rooms'");
                if (empty($roomTableExists)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bảng room không tồn tại'
                    ], 404);
                }
                $roomTable = 'rooms';
                $roomIdColumn = 'id';
            } else {
                $roomTable = 'room';
                $roomIdColumn = 'room_id';
            }

            $room = DB::table($roomTable)->where($roomIdColumn, $roomId)->first();
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phòng'
                ], 404);
            }

            // Tạo calendar data (simplified)
            $calendarData = [];
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                $calendarData[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'available' => true, // Simplified - always available
                    'price' => 1000000, // Default price
                    'is_weekend' => $currentDate->isWeekend()
                ];
                $currentDate->addDay();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'room_id' => $roomId,
                    'month' => $month,
                    'calendar' => $calendarData
                ],
                'message' => 'Dữ liệu lịch phòng đã được tải thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('RoomController@getCalendarData error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải dữ liệu lịch phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function getRoomImages($roomTypeId): array
    {
        try {
            $imagesTableExists = DB::select("SHOW TABLES LIKE 'room_type_images'");
            if (empty($imagesTableExists)) {
                return [];
            }

            $images = DB::table('room_type_images')
                ->where('room_type_id', $roomTypeId)
                ->get();

            return $images->map(function ($img) {
                return [
                    'id' => $img->image_id ?? $img->id,
                    'room_type_id' => $img->room_type_id,
                    'image_url' => asset($img->image_path),
                    'alt_text' => $img->alt_text ?? '',
                    'is_main' => $img->is_main ?? false,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting room images: ' . $e->getMessage());
            return [];
        }
    }

    private function getRoomAmenities($roomTypeId): array
    {
        try {
            $roomTypeAmenitiesExists = DB::select("SHOW TABLES LIKE 'room_type_amenities'");
            $amenitiesExists = DB::select("SHOW TABLES LIKE 'amenities'");

            if (empty($roomTypeAmenitiesExists) || empty($amenitiesExists)) {
                return [];
            }

            $amenities = DB::table('room_type_amenities as rta')
                ->join('amenities as a', 'rta.amenity_id', '=', 'a.amenity_id')
                ->where('rta.room_type_id', $roomTypeId)
                ->select('a.*')
                ->get();

            return $amenities->map(function ($amenity) {
                return [
                    'id' => $amenity->amenity_id,
                    'name' => $amenity->name,
                    'description' => $amenity->description ?? '',
                    'icon' => $amenity->icon ?? '',
                    'category' => $amenity->category ?? ''
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting amenities: ' . $e->getMessage());
            return [];
        }
    }
}
