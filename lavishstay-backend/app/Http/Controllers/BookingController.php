<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller {
    /**
     * Gán booking vào user (dùng cho khách vừa đăng ký)
     * Route: POST /api/booking/assign
     */
    public function assignBookingToUser(Request $request)
    {
        $request->validate([
            'bookingCode' => 'required|string',
            'userId' => 'required|integer|exists:users,id',
        ]);

        $booking = \DB::table('booking')->where('booking_code', $request->bookingCode)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy booking với mã này.'
            ], 404);
        }
        // Chỉ cho phép gán nếu booking chưa có user_id
        if ($booking->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking đã được gán user.'
            ], 400);
        }

        // Kiểm tra email của user và booking phải trùng nhau
        $user = \DB::table('users')->where('id', $request->userId)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy user.'
            ], 404);
        }
        if (strtolower(trim($user->email)) !== strtolower(trim($booking->guest_email))) {
            return response()->json([
                'success' => false,
                'message' => 'Email của tài khoản không khớp với email đặt phòng.'
            ], 400);
        }

        // Gán user_id cho booking
        \DB::table('booking')->where('booking_code', $request->bookingCode)
            ->update(['user_id' => $request->userId]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gán booking vào tài khoản thành công.'
        ]);
    }

    public function getAvailableRoomsForDates(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'room_type_id' => 'required|exists:room_types,room_type_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $checkInDate = $request->check_in_date;
            $checkOutDate = $request->check_out_date;
            $roomTypeId = $request->room_type_id;

            Log::info('Getting available rooms for dates', [
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'room_type_id' => $roomTypeId
            ]);

            // Tìm các phòng bị trùng lịch (SAME LOGIC as getAvailableRooms)
            $conflictingRoomIds = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->whereIn('b.status', ['Confirmed', 'Operational'])
                ->whereNotNull('br.room_id')
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('br.check_in_date', '<', $checkOutDate)
                        ->where('br.check_out_date', '>', $checkInDate);
                })
                ->pluck('br.room_id');

            // Lấy danh sách phòng còn trống theo loại phòng
            $availableRooms = DB::table('room')
                ->where('room_type_id', $roomTypeId)
                ->where('status', 'available')
                ->whereNotIn('room_id', $conflictingRoomIds)
                ->orderBy('floor_id', 'asc')
                ->orderBy('name', 'asc')
                ->select(['room_id as id', 'name', 'floor_id as floor', 'room_type_id', 'status'])
                ->get();

            // Get room type info với base price
            $roomType = DB::table('room_types')
                ->where('room_type_id', $roomTypeId)
                ->select(['room_type_id', 'name', 'base_price', 'max_guests'])
                ->first();

            Log::info('Available rooms found for dates', [
                'available_count' => $availableRooms->count(),
                'conflicting_count' => $conflictingRoomIds->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type_id' => $roomTypeId,
                    'room_type_name' => $roomType->name ?? 'N/A',
                    'room_type_base_price' => $roomType->base_price ?? 0,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'available_rooms' => $availableRooms,
                    'total_available' => $availableRooms->count()
                ],
                'message' => 'Available rooms retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting available rooms for dates: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách phòng'
            ], 500);
        }
    }

    public function getPackagesByRoomType(Request $request, $roomTypeId)
    {
        try {
            Log::info('Getting packages for room type', ['room_type_id' => $roomTypeId]);

            // Query từ bảng room_type_package thay vì packages
            $packages = DB::table('room_type_package')
                ->where('room_type_id', $roomTypeId)
                ->orderBy('name', 'asc')
                ->select([
                    'package_id',
                    'room_type_id',
                    'name',
                    'price_modifier_vnd', // Sử dụng price_modifier_vnd thay vì price
                    'include_all_services',
                    'description'
                ])
                ->get();

            Log::info('Packages found', [
                'room_type_id' => $roomTypeId,
                'packages_count' => $packages->count(),
                'packages' => $packages->toArray()
            ]);

            // Transform data để frontend dễ sử dụng
            $transformedPackages = $packages->map(function ($package) {
                return [
                    'package_id' => $package->package_id,
                    'id' => $package->package_id, // Alias cho frontend
                    'name' => $package->name,
                    'price' => $package->price_modifier_vnd, // Map price_modifier_vnd thành price
                    'price_modifier_vnd' => $package->price_modifier_vnd,
                    'include_all_services' => $package->include_all_services,
                    'description' => $package->description,
                    'duration_nights' => 1 // Default duration, có thể customize sau
                ];
            });

            return response()->json([
                'success' => true,
                'packages' => $transformedPackages,
                'total_packages' => $transformedPackages->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting packages by room type: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách gói phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function createNewBooking(Request $request)
    {
        try {
            // LOG: Request data đầu vào
            Log::info('=== START createNewBooking ===', [
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            $validator = Validator::make($request->all(), [
                'guest_name' => 'required|string|max:255',
                'guest_phone' => 'required|string|max:20',
                'guest_email' => 'required|email|max:255',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'room_type_id' => 'required|exists:room_types,room_type_id',
                'package_id' => 'required|exists:room_type_package,package_id',
                'adults' => 'required|integer|min:1|max:10',
                'children' => 'nullable|integer|min:0|max:5',
                'rooms_count' => 'required|integer|min:1|max:5',
                'notes' => 'nullable|string|max:1000',
                'selected_room_ids' => 'nullable|array',
                'selected_room_ids.*' => 'integer|exists:room,room_id'
            ]);

            // LOG: Validation results
            if ($validator->fails()) {
                Log::error('=== VALIDATION FAILED ===', [
                    'errors' => $validator->errors()->toArray(),
                    'failed_rules' => $validator->failed(),
                    'request_data' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors(),
                    'debug_info' => [
                        'failed_fields' => array_keys($validator->errors()->toArray()),
                        'request_keys' => array_keys($request->all())
                    ]
                ], 422);
            }

            Log::info('Validation passed successfully');

            DB::beginTransaction();
            Log::info('Database transaction started');

            // Generate booking code
            $bookingCode = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Ensure unique booking code
            $attempts = 0;
            while (DB::table('booking')->where('booking_code', $bookingCode)->exists()) {
                $bookingCode = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $attempts++;
                if ($attempts > 10) {
                    Log::error('Too many attempts to generate unique booking code');
                    break;
                }
            }
            
            Log::info('Generated booking code', ['booking_code' => $bookingCode, 'attempts' => $attempts]);

            // LOG: Check if package exists
            Log::info('Checking package existence', ['package_id' => $request->package_id]);
            $package = DB::table('room_type_package')->where('package_id', $request->package_id)->first();
            
            if (!$package) {
                Log::error('Package not found', [
                    'package_id' => $request->package_id,
                    'available_packages' => DB::table('room_type_package')->pluck('package_id')->toArray()
                ]);
                
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Gói phòng không tồn tại',
                    'debug_info' => [
                        'requested_package_id' => $request->package_id,
                        'available_packages' => DB::table('room_type_package')->pluck('package_id')->toArray()
                    ]
                ], 404);
            }
            
            Log::info('Package found', ['package' => $package]);

            // LOG: Check if room type exists
            Log::info('Checking room type existence', ['room_type_id' => $request->room_type_id]);
            $roomType = DB::table('room_types')->where('room_type_id', $request->room_type_id)->first();
            
            if (!$roomType) {
                Log::error('Room type not found', [
                    'room_type_id' => $request->room_type_id,
                    'available_room_types' => DB::table('room_types')->pluck('room_type_id')->toArray()
                ]);
                
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Loại phòng không tồn tại',
                    'debug_info' => [
                        'requested_room_type_id' => $request->room_type_id,
                        'available_room_types' => DB::table('room_types')->pluck('room_type_id')->toArray()
                    ]
                ], 404);
            }
            
            Log::info('Room type found', ['room_type' => $roomType]);

            // Calculate pricing: base price + package modifier
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $roomsCount = $request->rooms_count;
            
            $basePricePerNight = $roomType->base_price;
            $packageModifier = $package->price_modifier_vnd;
            $finalPricePerNight = $basePricePerNight + $packageModifier;
            $totalPrice = $finalPricePerNight * $nights * $roomsCount;

            Log::info('Pricing calculated', [
                'check_in' => $request->check_in_date,
                'check_out' => $request->check_out_date,
                'nights' => $nights,
                'rooms_count' => $roomsCount,
                'base_price_per_night' => $basePricePerNight,
                'package_modifier' => $packageModifier,
                'final_price_per_night' => $finalPricePerNight,
                'total_price' => $totalPrice
            ]);

            // SỬA: Create booking với các cột đúng theo database schema
            $bookingData = [
                'booking_code' => $bookingCode,
                'guest_name' => $request->guest_name,
                'guest_phone' => $request->guest_phone,
                'guest_email' => $request->guest_email,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'room_type_id' => $request->room_type_id,
                // SỬA: Loại bỏ adults, children, guest_count vì không có trong bảng booking
                'total_price_vnd' => $totalPrice,
                'status' => 'Confirmed',
                'status' => 'Completed',
                'notes' => $request->notes,
                'user_id' => Auth::id() ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::info('Inserting booking data', ['booking_data' => $bookingData]);
            
            $bookingId = DB::table('booking')->insertGetId($bookingData);
            
            Log::info('Booking created successfully', ['booking_id' => $bookingId]);

            // SỬA: Tạo representative chính cho booking (giống completeBookingAfterPayment)
            $mainRepresentativeId = DB::table('representatives')->insertGetId([
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
                'room_id' => NULL,
                'full_name' => $request->guest_name,
                'phone_number' => $request->guest_phone,
                'email' => $request->guest_email,
                'id_card' => '', // Có thể thêm field này vào form sau
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Main representative created', ['representative_id' => $mainRepresentativeId]);

            // SỬA: Tạo room_option (giống completeBookingAfterPayment)
            $optionId = 'ADMIN-' . $bookingCode;
            $roomOptionData = [
                'option_id' => $optionId,
                'room_id' => NULL,
                'name' => $package->name . ' (Admin Created)',
                'price_per_night_vnd' => $finalPricePerNight,
                'max_guests' => $request->adults + ($request->children ?? 0),
                'min_guests' => $request->adults,
                'urgency_message' => null,
                'most_popular' => 0,
                'recommended' => 0,
                'meal_type' => null,
                'bed_type' => null,
                'recommendation_score' => null,
                'deposit_policy_id' => null,
                'cancellation_policy_id' => null,
                'check_out_policy_id' => null,
                'package_id' => $request->package_id,
                'policy_applied_reason' => 'Tạo bởi admin',
                'policy_applied_date' => $request->check_in_date,
                'policy_snapshot_json' => json_encode([]),
                'adjusted_price' => $finalPricePerNight,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $roomOptionInserted = false;
            $finalOptionId = null;
            
            try {
                DB::table('room_option')->insert($roomOptionData);
                $roomOptionInserted = true;
                $finalOptionId = $optionId;
                Log::info('Room option created successfully', ['option_id' => $optionId]);
            } catch (\Exception $e) {
                Log::error('Error creating room option', [
                    'option_id' => $optionId,
                    'error' => $e->getMessage(),
                    'sql_state' => $e->getCode()
                ]);
                // Nếu insert room_option thất bại, sử dụng NULL cho option_id
                $finalOptionId = null;
            }

            // Cập nhật option_id vào booking nếu tạo thành công
            if ($roomOptionInserted) {
                try {
                    DB::table('booking')->where('booking_id', $bookingId)->update(['option_id' => $optionId]);
                    Log::info('Updated booking with option_id', ['booking_id' => $bookingId, 'option_id' => $optionId]);
                } catch (\Exception $e) {
                    Log::error('Error updating booking with option_id', [
                        'booking_id' => $bookingId,
                        'option_id' => $optionId,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // SỬA: Xử lý selected rooms
            $selectedRoomIds = $request->selected_room_ids ?? [];
            Log::info('Processing selected rooms', [
                'selected_room_ids' => $selectedRoomIds,
                'selected_rooms_count' => count($selectedRoomIds),
                'required_rooms_count' => $roomsCount
            ]);
            
            // Tạo booking_rooms entries
            for ($i = 0; $i < $roomsCount; $i++) {
                $roomId = isset($selectedRoomIds[$i]) ? $selectedRoomIds[$i] : null;
                
                $bookingRoomData = [
                    'booking_id' => $bookingId,
                    'booking_code' => $bookingCode,
                    'room_id' => $roomId, // Có thể là null nếu chưa chọn phòng cụ thể
                    'option_id' => $finalOptionId,
                    'option_name' => $package->name,
                    'option_price' => $finalPricePerNight,
                    'representative_id' => $mainRepresentativeId,
                    'adults' => $request->adults, // SỬA: Lưu trong booking_rooms thay vì booking
                    'children' => $request->children ?? 0, // SỬA: Lưu trong booking_rooms thay vì booking
                    'children_age' => null, // Có thể xử lý children_ages sau
                    'price_per_night' => $finalPricePerNight,
                    'nights' => $nights,
                    'total_price' => $finalPricePerNight * $nights,
                    'check_in_date' => $request->check_in_date,
                    'check_out_date' => $request->check_out_date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                Log::info("Creating booking_room entry {$i}", [
                    'booking_id' => $bookingId,
                    'room_id' => $roomId,
                    'option_id' => $finalOptionId
                ]);
                
                $bookingRoomId = DB::table('booking_rooms')->insertGetId($bookingRoomData);
                
                Log::info("Booking room created successfully", [
                    'booking_room_id' => $bookingRoomId,
                    'room_id' => $roomId
                ]);

                // SỬA: Xử lý children ages nếu có
                if ($request->children_ages && is_array($request->children_ages)) {
                    foreach ($request->children_ages as $childIndex => $age) {
                        if (is_numeric($age) && $age >= 0 && $age <= 17) {
                            DB::table('booking_room_children')->insert([
                                'booking_room_id' => $bookingRoomId,
                                'age' => (int)$age,
                                'child_index' => $childIndex,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            
                            Log::info("Child age recorded", [
                                'booking_room_id' => $bookingRoomId,
                                'child_index' => $childIndex,
                                'age' => $age
                            ]);
                        }
                    }
                }
            }

            DB::commit();
Log::info('Database transaction committed successfully');

// THÔNG BÁO TỰ ĐỘNG KHI TẠO BOOKING MỚI
try {
    Log::info('=== AUTO NOTIFICATION START ===', [
        'booking_id' => $bookingId,
        'booking_code' => $bookingCode,
        'guest_name' => $request->guest_name
    ]);

    // Load booking với tất cả relationships cần thiết
    $booking = Booking::with(['roomType', 'bookingRooms.room', 'representatives'])
        ->find($bookingId);
    
    if (!$booking) {
        Log::error('Booking not found for notification', ['booking_id' => $bookingId]);
        throw new \Exception('Booking not found after creation');
    }

    Log::info('Booking loaded for notification', [
        'booking_id' => $booking->booking_id,
        'guest_name' => $booking->guest_name,
        'room_type' => $booking->roomType ? $booking->roomType->name : 'N/A',
        'booking_rooms_count' => $booking->bookingRooms->count()
    ]);

    // Tạo room relation cho notification
    if ($booking->bookingRooms->isNotEmpty() && $booking->bookingRooms->first()->room) {
        $room = $booking->bookingRooms->first()->room;
        $booking->setRelation('room', $room);
        Log::info('Using actual room for notification', [
            'room_id' => $room->room_id,
            'room_number' => $room->room_number
        ]);
    } else {
        // Fallback to room type name
        $booking->setRelation('room', (object) [
            'room_number' => $booking->roomType ? $booking->roomType->name : 'TBD',
            'id' => null
        ]);
        Log::info('Using room type as fallback for notification', [
            'room_type' => $booking->roomType ? $booking->roomType->name : 'N/A'
        ]);
    }

    // Fire BookingCreated event
    event(new BookingCreated($booking));
    
    Log::info('BookingCreated event fired successfully', [
        'booking_id' => $booking->booking_id,
        'event_class' => 'App\Events\BookingCreated'
    ]);

    Log::info('=== AUTO NOTIFICATION END ===');

} catch (\Exception $e) {
    Log::error('Auto notification failed', [
        'booking_id' => $bookingId,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Không throw exception ở đây vì booking đã được tạo thành công
    // Chỉ log lỗi để không ảnh hưởng đến response
}

// Tiếp tục với response như cũ...
$responseData = [
    'success' => true,
    'message' => 'Đã tạo đặt phòng thành công',
    'data' => [
        'booking_id' => $bookingId,
        'booking_code' => $bookingCode,
        'guest_name' => $request->guest_name,
        'guest_email' => $request->guest_email,
        'check_in_date' => $request->check_in_date,
        'check_out_date' => $request->check_out_date,
        'total_price_vnd' => $totalPrice,
        'status' => 'Confirmed',
        'guest_count' => $request->adults + ($request->children ?? 0),
        'adults' => $request->adults,
        'children' => $request->children ?? 0,
        'rooms_count' => $roomsCount
    ]
];

return response()->json($responseData);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('=== ERROR in createNewBooking ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT br.room_id) as total_rooms'),
                DB::raw('SUM(CASE WHEN br.adults IS NOT NULL THEN br.adults ELSE 0 END) as total_adults_from_rooms'),
                DB::raw('SUM(CASE WHEN br.children IS NOT NULL THEN br.children ELSE 0 END) as total_children_from_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->groupBy('b.booking_id');

        // Apply filters
        if ($request->filled('booking_code')) {
            $query->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
        }

        if ($request->filled('guest_name')) {
            $query->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        if ($request->filled('check_in_date')) {
            $query->whereDate('b.check_in_date', '>=', $request->check_in_date);
        }

        if ($request->filled('check_out_date')) {
            $query->whereDate('b.check_out_date', '<=', $request->check_out_date);
        }

        // Order by latest first
        $query->orderBy('b.created_at', 'desc');

        // Get paginated results
        $bookings = $query->paginate(20);

        // Get statistics
        $statistics = $this->getBookingStatistics();

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'bookings' => $bookings->items(),
                'statistics' => $statistics,
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ]
            ]);
        }

        return view('admin.bookings.index', compact('bookings', 'statistics'));
    }
 /**
     * Check-in booking: chuyển trạng thái sang Operational
     */
    public function checkIn(Request $request, $id)
    {
        $booking = \DB::table('booking')->where('booking_id', $id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }
        if ($booking->status !== 'Confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể check-in booking đã thanh toán (Confirmed)'
            ], 400);
        }
        try {
            \DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Operational',
                'updated_at' => now()
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi check-in: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check-out booking: chuyển trạng thái sang Completed
     */
    public function checkOut(Request $request, $id)
    {
        $booking = \DB::table('booking')->where('booking_id', $id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }
        if ($booking->status !== 'Operational') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể check-out booking đang hoạt động (Operational)'
            ], 400);
        }
        try {
            \DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Completed',
                'updated_at' => now()
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi check-out: ' . $e->getMessage()
            ], 500);
        }
    }
    // Route: GET /api/user/bookings
    public function getUserBookings(Request $request)
    {
        $userId = $request->user()->id ?? $request->get('user_id');
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    
        $bookings = DB::table('booking as b')
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('room_types as rt2', 'b.room_type_id', '=', 'rt2.room_type_id')
            ->leftJoin('representatives as rep', function($join) {
                $join->on('rep.booking_id', '=', 'b.booking_id');
            })
            ->where('b.user_id', $userId)
            ->select([
                'b.booking_id',
                'b.booking_code',
                'b.check_in_date',
                'b.check_out_date',
                'b.status',
                'b.total_price_vnd',
                'b.created_at',
                'b.guest_count',
                'b.guest_name',
                'b.guest_email',
                'b.guest_phone',
                DB::raw('ANY_VALUE(COALESCE(rt.name, rt2.name)) as room_type'),
                // Room-level details are fetched separately (booking_rooms). Use ANY_VALUE for representative fields to satisfy ONLY_FULL_GROUP_BY
                DB::raw('ANY_VALUE(rep.id) as representative_id'),
                DB::raw('ANY_VALUE(rep.full_name) as representative_name'),
                DB::raw('ANY_VALUE(rep.phone_number) as representative_phone'),
                DB::raw('ANY_VALUE(rep.email) as representative_email'),
                DB::raw('ANY_VALUE(rep.id_card) as representative_id_card'),
                DB::raw('ANY_VALUE((SELECT JSON_ARRAYAGG(JSON_OBJECT(
                    "image_id", rti.image_id,
                    "image_path", rti.image_path,
                    "alt_text", rti.alt_text,
                    "is_main", rti.is_main
                )) FROM room_type_image rti WHERE rti.room_type_id = COALESCE(rt.room_type_id, b.room_type_id))) as room_type_images'),
                DB::raw('ANY_VALUE((SELECT JSON_ARRAYAGG(JSON_OBJECT(
                    "amenity_id", a.amenity_id,
                    "name", a.name,
                    "icon", a.icon,
                    "icon_lib", a.icon_lib,
                    "category", a.category,
                    "description", a.description,
                    "is_highlighted", rta.is_highlighted
                )) FROM room_type_amenity rta
                JOIN amenities a ON rta.amenity_id = a.amenity_id
                WHERE rta.room_type_id = COALESCE(rt.room_type_id, b.room_type_id))) as room_type_amenities')
            ])
            ->groupBy('b.booking_id')
            ->orderBy('b.created_at', 'desc')
            ->get();

        // Lấy booking_rooms cho từng booking_id
        $bookingIds = $bookings->pluck('booking_id')->unique()->toArray();
        // Join booking_rooms with room to get room name
        $bookingRoomsRaw = DB::table('booking_rooms as br')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->select('br.*', 'r.name as room_name')
            ->whereIn('br.booking_id', $bookingIds)
            ->get();
        $bookingRooms = $bookingRoomsRaw->groupBy('booking_id');

        // Parse images JSON cho từng booking và prepend backend URL cho image_path
        foreach ($bookings as $booking) {
            $booking->room_type_images = $booking->room_type_images ? json_decode($booking->room_type_images) : [];
            if (is_array($booking->room_type_images)) {
                foreach ($booking->room_type_images as $img) {
                    if (!empty($img->image_path)) {
                        if (!preg_match('/^https?:\/\//', $img->image_path)) {
                            $img->image_path = 'http://localhost:8888/' . ltrim($img->image_path, '/');
                        }
                    }
                }
            }
            $booking->room_type_amenities = $booking->room_type_amenities ? json_decode($booking->room_type_amenities) : [];
            // Gán booking_rooms cho từng booking, mỗi room có thêm room_name
            $booking->booking_rooms = isset($bookingRooms[$booking->booking_id]) ? $bookingRooms[$booking->booking_id]->values()->toArray() : [];
        }

        // Lấy toàn bộ phòng (room) join với room_types
        $allRooms = DB::table('room as r')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->select([
                'r.room_id',
                'r.name',
                'r.room_type_id',
                'rt.name as room_type_name',
                'r.status',
                'r.image',
                'r.floor_id',
                'r.description',
                'r.bed_type_fixed',
                'r.created_at',
                'r.updated_at'
            ])->get();

        Log::info("User bookings fetched " . json_encode($bookings) . " for user ID: " . $userId);
        return response()->json([
            'success' => true,
            'bookings' => $bookings,
            'all_rooms' => $allRooms
        ]);
    }

    /**
     * Show booking details
     */
    public function show(Request $request, $id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names'),
                'p.status as payment_status',
                'p.payment_type',
                'p.transaction_id',
                'p.amount_vnd as payment_amount'
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id', 'p.payment_id', 'p.status', 'p.payment_type', 'p.transaction_id', 'p.amount_vnd')
            ->first();

        if (!$booking) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đặt phòng'
                ], 404);
            }
            abort(404);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'booking' => $booking
            ]);
        }

        return view('admin.bookings.show', compact('booking'));
    }


    /**
     * Get available rooms for a booking
     */
    /**
 * Get available rooms for a booking - using exact logic from getAssignmentPreview
 */
/**
 * Get available rooms for a booking - with enhanced debugging
 */
    public function getAvailableRooms(Request $request, $bookingId)
    {
        try {
            Log::info('=== START getAvailableRooms ===', [
                'booking_id' => $bookingId,
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'request_headers' => $request->headers->all()
            ]);

            $booking = DB::table('booking')->where('booking_id', $bookingId)->first();
            if (!$booking) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
            }

            $roomTypeId = $booking->room_type_id;
            $checkInDate = $booking->check_in_date;
            $checkOutDate = $booking->check_out_date;

            Log::info('Booking details retrieved', [
                'booking_id' => $bookingId,
                'room_type_id' => $roomTypeId,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'booking_status' => $booking->status
            ]);

            // Tìm các phòng bị trùng lịch (copy chính xác từ getAssignmentPreview)
            $conflictingRoomIds = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->whereIn('b.status', ['Confirmed', 'Operational'])
                ->where('b.booking_id', '!=', $bookingId)
                ->whereNotNull('br.room_id')
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('br.check_in_date', '<', $checkOutDate)
                        ->where('br.check_out_date', '>', $checkInDate);
                })
                ->pluck('br.room_id');

            Log::info('Conflicting rooms found', [
                'conflicting_count' => $conflictingRoomIds->count(),
                'conflicting_ids' => $conflictingRoomIds->toArray()
            ]);

            // Lấy thông tin loại phòng
            $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
            if (!$roomType) {
                Log::error('Room type not found', ['room_type_id' => $roomTypeId]);
                return response()->json([
                    'success' => false,
                    'message' => "Room type '{$roomTypeId}' not found.",
                    'available_rooms' => []
                ], 404);
            }

            Log::info('Room type details', [
                'room_type_id' => $roomTypeId,
                'room_type_name' => $roomType->name
            ]);

            // Lấy danh sách phòng còn trống theo loại phòng
            $availableRooms = DB::table('room')
                ->where('room_type_id', $roomTypeId)
                ->whereNotIn('room_id', $conflictingRoomIds)
                ->orderBy('floor_id', 'asc')
                ->orderBy('name', 'asc')
                ->select(['room_id as id', 'name', 'floor_id as floor', 'room_type_id', 'status'])
                ->get();

            Log::info('Available rooms query completed', [
                'available_count' => $availableRooms->count(),
                'first_few_rooms' => $availableRooms->take(3)->toArray()
            ]);

            $responseData = [
                'success' => true,
                'data' => [
                    'booking_id' => (int) $bookingId,
                    'booking_code' => $booking->booking_code,
                    'guest_name' => $booking->guest_name,
                    'room_type_id' => (int) $roomTypeId,
                    'room_type_name' => $roomType->name,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'booking_status' => $booking->status,
                    'available_rooms' => $availableRooms->values()->toArray(),
                    'total_available' => $availableRooms->count()
                ],
                'message' => 'Available rooms retrieved successfully'
            ];

            Log::info('Response data prepared', [
                'response_keys' => array_keys($responseData),
                'data_keys' => array_keys($responseData['data']),
                'rooms_count' => $availableRooms->count()
            ]);

            $response = response()->json($responseData);
            
            Log::info('=== END getAvailableRooms SUCCESS ===', [
                'booking_id' => $bookingId,
                'status_code' => 200,
                'response_size' => strlen($response->getContent())
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('=== ERROR in getAvailableRooms ===', [
                'booking_id' => $bookingId,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while getting available rooms.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
 * Assign room to booking - using same logic as getAvailableRooms
 */
public function assignRoom(Request $request, $id)
{
    $request->validate([
        'room_id' => 'required|exists:room,room_id'
    ]);

    try {
        Log::info('=== START assignRoom ===', [
            'booking_id' => $id,
            'room_id' => $request->room_id
        ]);

        $booking = DB::table('booking')->where('booking_id', $id)->first();
        $room = DB::table('room')->where('room_id', $request->room_id)->first();

        if (!$booking || !$room) {
            Log::error('Booking or room not found', [
                'booking_found' => !!$booking,
                'room_found' => !!$room,
                'booking_id' => $id,
                'room_id' => $request->room_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Booking hoặc phòng không tồn tại'
            ], 404);
        }

        // Check if booking has room_type_id
        if (!$booking->room_type_id) {
            Log::error('Booking has no room_type_id', [
                'booking_id' => $id,
                'booking_data' => $booking
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Booking này không có thông tin loại phòng. Vui lòng liên hệ admin để khắc phục.'
            ], 400);
        }

        // Check if room matches booking's room type
        if ($room->room_type_id != $booking->room_type_id) {
            Log::error('Room type mismatch', [
                'booking_room_type' => $booking->room_type_id,
                'room_room_type' => $room->room_type_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Phòng này không phù hợp với loại phòng đã đặt'
            ], 400);
        }

        $checkInDate = $booking->check_in_date;
        $checkOutDate = $booking->check_out_date;

        // Check if room is available using SAME logic as getAvailableRooms
        $conflictingBooking = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->where('br.room_id', $room->room_id)
            ->whereIn('b.status', ['Confirmed', 'Operational']) // Same status check as getAvailableRooms
            ->where('b.booking_id', '!=', $booking->booking_id)
            ->whereNotNull('br.room_id')
            ->where(function ($query) use ($checkInDate, $checkOutDate) {
                // SAME date overlap logic as getAvailableRooms
                $query->where('br.check_in_date', '<', $checkOutDate)
                      ->where('br.check_out_date', '>', $checkInDate);
            })
            ->exists();

        if ($conflictingBooking) {
            Log::warning('Room conflict detected', [
                'room_id' => $room->room_id,
                'booking_id' => $id,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Phòng này đã được đặt trong thời gian này'
            ], 400);
        }

        DB::beginTransaction();

        // Get room type for pricing
        $roomType = DB::table('room_types')->where('room_type_id', $booking->room_type_id)->first();
        if (!$roomType) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin loại phòng'
            ], 400);
        }

        // Remove existing room assignments for this booking
        $deletedRows = DB::table('booking_rooms')->where('booking_id', $booking->booking_id)->delete();
        Log::info('Removed existing room assignments', ['deleted_rows' => $deletedRows]);

        // Calculate nights and total price
        $checkIn = Carbon::parse($booking->check_in_date);
        $checkOut = Carbon::parse($booking->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $pricePerNight = $roomType->base_price; // Use room type price, not room price
        $totalPrice = $pricePerNight * $nights;

        // Assign new room
        $insertData = [
            'booking_id' => $booking->booking_id,
            'booking_code' => $booking->booking_code,
            'room_id' => $room->room_id,
            'adults' => $booking->guest_count ?? 1,
            'children' => $booking->children ?? 0,
            'children_age' => $booking->children_age,
            'price_per_night' => $pricePerNight,
            'nights' => $nights,
            'total_price' => $totalPrice,
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $booking->check_out_date,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('booking_rooms')->insert($insertData);
        Log::info('Room assigned successfully', $insertData);

        // Update booking status if it was pending
        if ($booking->status === 'Pending') {
            DB::table('booking')->where('booking_id', $booking->booking_id)
                ->update(['status' => 'Confirmed']);
            Log::info('Booking status updated to Confirmed');
        }

        DB::commit();

        Log::info('=== END assignRoom SUCCESS ===', [
            'booking_id' => $id,
            'room_id' => $request->room_id,
            'nights' => $nights,
            'total_price' => $totalPrice
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gán phòng thành công',
            'data' => [
                'booking_id' => $booking->booking_id,
                'room_id' => $room->room_id,
                'room_name' => $room->name,
                'nights' => $nights,
                'price_per_night' => $pricePerNight,
                'total_price' => $totalPrice
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('=== ERROR in assignRoom ===', [
            'booking_id' => $id,
            'room_id' => $request->room_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi gán phòng: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Confirm booking
     */
    public function confirm(Request $request, $id)
    {
        $booking = DB::table('booking')->where('booking_id', $id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        if ($booking->status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận đặt phòng đang chờ xử lý'
            ], 400);
        }

        try {
            DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Confirmed',
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận đặt phòng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác nhận đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = DB::table('booking')->where('booking_id', $id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        if (in_array($booking->status, ['Cancelled', 'Completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đặt phòng này'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update booking status
            DB::table('booking')->where('booking_id', $id)->update([
                'status' => 'Cancelled',
                'updated_at' => now()
            ]);

            // Free up the rooms
            DB::table('booking_rooms')->where('booking_id', $booking->booking_id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đặt phòng thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking analytics data - FIXED với tên bảng đúng
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // Booking trends
        $bookingTrends = DB::table('booking')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue trends - FIXED: Tính từ bảng payment với status completed
        $revenueTrends = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('DATE(p.created_at) as date, SUM(p.amount_vnd) as revenue')
                        ->where('p.created_at', '>=', $startDate)
            ->where('p.status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = DB::table('booking')
            ->selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get();

        // Top room types - FIXED: Tính revenue từ payment completed
        $topRoomTypes = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('payment as p', function($join) {
                $join->on('b.booking_id', '=', 'p.booking_id')
                     ->where('p.status', '=', 'completed');
            })
            ->selectRaw('
                rt.name, 
                COUNT(DISTINCT b.booking_id) as bookings_count, 
                COALESCE(SUM(p.amount_vnd), 0) as total_revenue
            ')
            ->where('b.created_at', '>=', $startDate)
            ->where('b.status', '!=', 'Cancelled')
            ->groupBy('rt.room_type_id', 'rt.name')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        // Total statistics - FIXED: Tính từ payment completed
        $totalStats = [
            'total_bookings' => DB::table('booking')->where('created_at', '>=', $startDate)->count(),
            'total_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $startDate)
                ->sum('p.amount_vnd'),
            'pending_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Pending')
                ->count(),
            'confirmed_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Confirmed')
                ->count(),
            'cancelled_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Cancelled')
                ->count(),
            'completed_bookings' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'Completed')
                ->count(),
        ];

        // Average booking value - FIXED: Tính từ payment completed
        $avgBookingValue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $startDate)
            ->avg('p.amount_vnd');

        // Revenue by payment method
        $revenueByPaymentMethod = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('p.payment_type, SUM(p.amount_vnd) as revenue, COUNT(*) as count')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $startDate)
            ->groupBy('p.payment_type')
            ->orderBy('revenue', 'desc')
            ->get();

        // Monthly revenue comparison (so với tháng trước)
        $currentMonthRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereMonth('p.created_at', Carbon::now()->month)
            ->whereYear('p.created_at', Carbon::now()->year)
            ->sum('p.amount_vnd');

        $lastMonthRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereMonth('p.created_at', Carbon::now()->subMonth()->month)
            ->whereYear('p.created_at', Carbon::now()->subMonth()->year)
            ->sum('p.amount_vnd');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'booking_trends' => $bookingTrends,
                'revenue_trends' => $revenueTrends,
                'status_distribution' => $statusDistribution,
                'top_room_types' => $topRoomTypes,
                'total_stats' => $totalStats,
                'avg_booking_value' => round($avgBookingValue ?? 0, 0),
                'revenue_by_payment_method' => $revenueByPaymentMethod,
                'revenue_growth' => round($revenueGrowth, 2),
                'current_month_revenue' => $currentMonthRevenue,
                'last_month_revenue' => $lastMonthRevenue,
                'period' => $period
            ]
        ]);
    }

    /**
     * Export bookings to Excel
     */
    public function export(Request $request)
    {
        $query = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->groupBy('b.booking_id');

        // Apply same filters as index
        if ($request->filled('booking_code')) {
            $query->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
        }

        if ($request->filled('guest_name')) {
            $query->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        if ($request->filled('check_in_date')) {
            $query->whereDate('b.check_in_date', '>=', $request->check_in_date);
        }

        if ($request->filled('check_out_date')) {
            $query->whereDate('b.check_out_date', '<=', $request->check_out_date);
        }

        $bookings = $query->orderBy('b.created_at', 'desc')->get();

        // Create CSV content
        $csvContent = "Mã đặt phòng,Tên khách,Email,Điện thoại,Check-in,Check-out,Số phòng,Tên phòng,Loại phòng,Tổng tiền,Trạng thái,Ngày đặt\n";
        
        foreach ($bookings as $booking) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s\n",
                $booking->booking_code,
                $booking->guest_name,
                $booking->guest_email,
                $booking->guest_phone,
                $booking->check_in_date,
                $booking->check_out_date,
                $booking->total_rooms ?? 1,
                $booking->room_names ?? 'Chưa chọn phòng',
                $booking->room_type_names ?? 'N/A',
                number_format($booking->total_price_vnd ?? 0),
                $this->getStatusText($booking->status),
                Carbon::parse($booking->created_at)->format('d/m/Y H:i')
            );
        }

        $filename = 'bookings_' . date('Y-m-d_H-i-s') . '.csv';

        // Prepend UTF-8 BOM so Excel on Windows recognizes UTF-8 and displays Vietnamese characters correctly
        $bom = "\xEF\xBB\xBF";
        $payload = $bom . $csvContent;

        return response($payload)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($payload));
    }

    /**
     * Print booking details
     */
    public function print($id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names'),
                DB::raw('COUNT(DISTINCT r.room_id) as total_rooms'),
                DB::raw('GROUP_CONCAT(DISTINCT br.option_name) as option_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id')
            ->first();

        if (!$booking) {
            abort(404);
        }

        return view('admin.bookings.print', compact('booking'));
    }

    /**
     * Get booking statistics - FIXED với tên bảng đúng
     */
    private function getBookingStatistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_bookings' => DB::table('booking')->count(),
            'pending_bookings' => DB::table('booking')->where('status', 'Pending')->count(),
            'confirmed_bookings' => DB::table('booking')->where('status', 'Confirmed')->count(),
            'cancelled_bookings' => DB::table('booking')->where('status', 'Cancelled')->count(),
            'completed_bookings' => DB::table('booking')->where('status', 'Completed')->count(),
            'total_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->sum('p.amount_vnd'),
            'today_bookings' => DB::table('booking')->whereDate('created_at', $today)->count(),
            'this_month_bookings' => DB::table('booking')->whereDate('created_at', '>=', $thisMonth)->count(),
            'this_month_revenue' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->whereDate('b.created_at', '>=', $thisMonth)
                ->sum('p.amount_vnd'),
            'average_booking_value' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->avg('p.amount_vnd'),
        ];
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        $statusMap = [
            'Pending' => 'Chờ xác nhận',
            'Confirmed' => 'Đã xác nhận',
            'Operational' => 'Đang hoạt động',
            'Completed' => 'Hoàn thành',
            'Cancelled' => 'Đã hủy',
            'Cancelled With Penalty' => 'Đã hủy có phạt',
            'Unsuccessful' => 'Không thành công'
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * Bulk update booking status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái {$updatedCount} đặt phòng thành công"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk confirm bookings
     */
    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id'
        ]);

        try {
            DB::beginTransaction();

            $confirmedCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Confirmed',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'confirmed_count' => $confirmedCount,
                'message' => "Đã xác nhận thành công {$confirmedCount} đặt phòng"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                                'message' => 'Có lỗi xảy ra khi xác nhận hàng loạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk cancel bookings
     */
    public function bulkCancel(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:booking,booking_id'
        ]);

        try {
            DB::beginTransaction();

            $cancelledCount = DB::table('booking')
                ->whereIn('booking_id', $request->booking_ids)
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->update([
                    'status' => 'Cancelled',
                    'updated_at' => now()
                ]);

            // Free up the rooms for cancelled bookings
            DB::table('booking_rooms')
                ->whereIn('booking_id', $request->booking_ids)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'cancelled_count' => $cancelledCount,
                'message' => "Đã hủy thành công {$cancelledCount} đặt phòng"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy hàng loạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking statistics for AJAX
     */
    public function getStats()
    {
        $statistics = $this->getBookingStatistics();
        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Send booking confirmation email
     */
    public function sendConfirmationEmail($id)
    {
        $booking = DB::table('booking as b')
            ->select([
                'b.*',
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                DB::raw('GROUP_CONCAT(DISTINCT rt.name) as room_type_names')
            ])
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->where('b.booking_id', $id)
            ->groupBy('b.booking_id')
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        try {
            // Send confirmation email
            // Mail::to($booking->guest_email)->send(new BookingConfirmationMail($booking));

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi email xác nhận thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new booking modal
     */
    public function showNewBookingModal()
    {
        // Get available rooms
        $rooms = DB::table('room as r')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->select([
                'r.room_id',
                'r.name',
                // `room_number` column does not exist in current schema; use `name` as the room code
                'rt.name as room_type_name',
                'r.max_guests',
                'r.price_per_night'
            ])
            ->where('r.status', 'available')
            ->get();

        return response()->json([
            'success' => true,
            'rooms' => $rooms
        ]);
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_count' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'room_id' => 'required|exists:room,room_id'
        ]);

        try {
            DB::beginTransaction();

            // Generate booking code
            $bookingCode = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Get room details
            $room = DB::table('room')->where('room_id', $request->room_id)->first();
            
            // Calculate nights and total price
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price_per_night * $nights;

            // Create booking
            $bookingId = DB::table('booking')->insertGetId([
                'booking_code' => $bookingCode,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_price_vnd' => $totalPrice,
                'guest_count' => $request->guest_count,
                'status' => 'Pending',
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'children' => $request->children ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create booking room
            DB::table('booking_rooms')->insert([
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
                'room_id' => $request->room_id,
                'adults' => $request->guest_count,
                'children' => $request->children ?? 0,
                'price_per_night' => $room->price_per_night,
                'nights' => $nights,
                'total_price' => $totalPrice,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create booking
            $booking = Booking::create($request);

            // Load relationships for notification
            $booking->load('room');

            // Fire event to trigger notification
            event(new BookingCreated($booking));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo đặt phòng thành công',
                'booking_code' => $bookingCode
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đặt phòng: ' . $e->getMessage()
            ], 500);
        }
    }
}

