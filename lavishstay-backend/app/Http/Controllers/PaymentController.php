<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\BookingRoomChildren;
use App\Models\Payment;
use App\Models\Room;
use App\Services\CouponService;
use App\Services\SmartRoomAssignmentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Mail\BookingConfirmation;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{


    public function settings()
    {
        try {
            // Get all payment settings organized by groups
            $settings = [
                'vietqr' => $this->getVietQRSettings(),
                'cpay' => $this->getCPaySettings(),
                'vnpay' => $this->getVNPaySettings(),
                'pay_at_hotel' => $this->getPayAtHotelSettings(),
                'general' => $this->getGeneralSettings()
            ];

            // Get statistics for the dashboard cards
            $statistics = [
                'total_settings' => PaymentSetting::count(),
                'active_settings' => PaymentSetting::where('is_active', true)->count(),
                'last_updated' => PaymentSetting::latest('updated_at')->first()?->updated_at,
                'enabled_methods' => $this->getEnabledPaymentMethods()
            ];

            return view('admin.payment.settings', compact('settings', 'statistics'));
            
        } catch (\Exception $e) {
            Log::error('Failed to load payment settings page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Không thể tải trang cấu hình thanh toán.');
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                // VietQR
                'vietqr_bank_id' => 'required|string|max:20',
                'vietqr_account_no' => 'required|string|max:50|regex:/^\d+$/',
                'vietqr_account_name' => 'required|string|max:255',
                'vietqr_template' => 'required|in:print,compact,qr_only',
                'vietqr_enabled' => 'boolean',
                
                // CPay
                'cpay_timeout' => 'required|integer|min:5|max:300',
                'cpay_enabled' => 'boolean',
                
                // VNPay
                'vnpay_enabled' => 'boolean',
                
                // Pay at Hotel
                'pay_at_hotel_enabled' => 'boolean',
                
                // General
                'general_default_payment_method' => 'required|in:vietqr,vnpay,pay_at_hotel',
                'general_api_base_url' => 'required|url',
                'general_payment_timeout' => 'required|integer|min:300|max:3600',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.');
            }

            $validated = $validator->validated();

            // Prepare settings array
            $settingsToUpdate = [
                // VietQR Settings
                ['key' => 'vietqr.bank_id', 'value' => $validated['vietqr_bank_id'], 'type' => 'string'],
                ['key' => 'vietqr.account_no', 'value' => $validated['vietqr_account_no'], 'type' => 'string'],
                ['key' => 'vietqr.account_name', 'value' => $validated['vietqr_account_name'], 'type' => 'string'],
                ['key' => 'vietqr.template', 'value' => $validated['vietqr_template'], 'type' => 'string'],
                ['key' => 'vietqr.enabled', 'value' => $request->has('vietqr_enabled') ? '1' : '0', 'type' => 'boolean'],

                // CPay Settings
                ['key' => 'cpay.timeout', 'value' => $validated['cpay_timeout'], 'type' => 'number'],
                ['key' => 'cpay.enabled', 'value' => $request->has('cpay_enabled') ? '1' : '0', 'type' => 'boolean'],

                // VNPay Settings
                ['key' => 'vnpay.enabled', 'value' => $request->has('vnpay_enabled') ? '1' : '0', 'type' => 'boolean'],

                // Pay at Hotel Settings
                ['key' => 'pay_at_hotel.enabled', 'value' => $request->has('pay_at_hotel_enabled') ? '1' : '0', 'type' => 'boolean'],

                // General Settings
                ['key' => 'general.default_payment_method', 'value' => $validated['general_default_payment_method'], 'type' => 'string'],
                ['key' => 'general.api_base_url', 'value' => $validated['general_api_base_url'], 'type' => 'string'],
                ['key' => 'general.payment_timeout', 'value' => $validated['general_payment_timeout'], 'type' => 'number'],
            ];

            // Update each setting
            foreach ($settingsToUpdate as $settingData) {
                PaymentSetting::updateOrCreate(
                    ['key' => $settingData['key']],
                    [
                        'value' => $settingData['value'],
                        'type' => $settingData['type'],
                        'group_name' => $this->getGroupFromKey($settingData['key']),
                        'description' => $this->getDescriptionFromKey($settingData['key']),
                        'is_encrypted' => $this->shouldEncrypt($settingData['key']),
                        'is_active' => true
                    ]
                );
            }

            // Clear cache
            PaymentSetting::clearCache();

            // Log the update
            Log::info('Payment settings updated via admin panel', [
                'user_id' => auth()->id(),
                'updated_settings' => array_column($settingsToUpdate, 'key'),
                'ip' => $request->ip()
            ]);

            return redirect()->back()->with('success', 'Cập nhật cấu hình thanh toán thành công!');

        } catch (\Exception $e) {
            Log::error('Failed to update payment settings', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật cấu hình. Vui lòng thử lại.');
        }
    }


    public function testVietQRFromAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bank_id' => 'required|string|max:20',
                'account_no' => 'required|string|max:50',
                'account_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Test generate QR URL
            $testAmount = 10000;
            $testContent = 'Test connection - ' . now()->format('H:i:s');
            $encodedContent = urlencode($testContent);
            $encodedAccountName = urlencode($validated['account_name']);
            
            $qrUrl = "https://img.vietqr.io/image/{$validated['bank_id']}-{$validated['account_no']}-print.png?amount={$testAmount}&addInfo={$encodedContent}&accountName={$encodedAccountName}";

            // Try to fetch the QR image to verify
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $qrUrl,
                CURLOPT_NOBODY => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_USERAGENT => 'LavishStay Payment System',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true
            ]);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($result === false || !empty($error)) {
                throw new \Exception("cURL Error: {$error}");
            }

            if ($httpCode === 200) {
                Log::info('VietQR test successful via admin panel', [
                    'bank_id' => $validated['bank_id'],
                    'account_no' => $validated['account_no'],
                    'test_url' => $qrUrl,
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kết nối VietQR thành công! QR code có thể được tạo.',
                    'test_url' => $qrUrl
                ]);
            } else {
                throw new \Exception("HTTP {$httpCode}: Không thể tạo QR code. Vui lòng kiểm tra thông tin tài khoản.");
            }
        } catch (\Exception $e) {
            Log::error('VietQR test failed via admin panel', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Test VietQR thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset settings to default from admin panel
     */
    public function resetToDefaultsFromAdmin()
    {
        try {
            // Define default settings
            $defaultSettings = [
                ['key' => 'vietqr.bank_id', 'value' => 'MBBank', 'type' => 'string'],
                ['key' => 'vietqr.account_no', 'value' => '0335920306', 'type' => 'string'],
                ['key' => 'vietqr.account_name', 'value' => 'NGUYEN VAN QUYEN', 'type' => 'string'],
                ['key' => 'vietqr.template', 'value' => 'print', 'type' => 'string'],
                ['key' => 'vietqr.enabled', 'value' => '1', 'type' => 'boolean'],
                ['key' => 'cpay.timeout', 'value' => '30', 'type' => 'number'],
                ['key' => 'cpay.enabled', 'value' => '1', 'type' => 'boolean'],
                ['key' => 'vnpay.enabled', 'value' => '0', 'type' => 'boolean'],
                ['key' => 'pay_at_hotel.enabled', 'value' => '1', 'type' => 'boolean'],
                ['key' => 'general.default_payment_method', 'value' => 'vietqr', 'type' => 'string'],
                ['key' => 'general.api_base_url', 'value' => 'http://localhost:8888/api', 'type' => 'string'],
                ['key' => 'general.payment_timeout', 'value' => '900', 'type' => 'number'],
            ];

            foreach ($defaultSettings as $setting) {
                PaymentSetting::updateOrCreate(
                    ['key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'type' => $setting['type'],
                        'group_name' => $this->getGroupFromKey($setting['key']),
                        'description' => $this->getDescriptionFromKey($setting['key']),
                        'is_encrypted' => $this->shouldEncrypt($setting['key']),
                        'is_active' => true
                    ]
                );
            }

            PaymentSetting::clearCache();

            Log::info('Payment settings reset to defaults via admin panel', [
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()->with('success', 'Đã khôi phục cấu hình mặc định thành công!');

        } catch (\Exception $e) {
            Log::error('Failed to reset payment settings via admin panel', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Không thể khôi phục cấu hình mặc định.');
        }
    }

    // Private helper methods
    private function getVietQRSettings(): array
    {
        return [
            'bank_id' => PaymentSetting::get('vietqr.bank_id', 'MBBank'),
            'account_no' => PaymentSetting::get('vietqr.account_no', '0335920306'),
            'account_name' => PaymentSetting::get('vietqr.account_name', 'NGUYEN VAN QUYEN'),
            'template' => PaymentSetting::get('vietqr.template', 'print'),
            'enabled' => PaymentSetting::get('vietqr.enabled', true)
        ];
    }

    private function getCPaySettings(): array
    {
        return [
            'timeout' => PaymentSetting::get('cpay.timeout', 30),
            'enabled' => PaymentSetting::get('cpay.enabled', true)
        ];
    }

    private function getVNPaySettings(): array
    {
        return [
            'enabled' => PaymentSetting::get('vnpay.enabled', false)
        ];
    }

    private function getPayAtHotelSettings(): array
    {
        return [
            'enabled' => PaymentSetting::get('pay_at_hotel.enabled', true)
        ];
    }

    private function getGeneralSettings(): array
    {
        return [
            'default_payment_method' => PaymentSetting::get('general.default_payment_method', 'vietqr'),
            'api_base_url' => PaymentSetting::get('general.api_base_url', 'http://localhost:8888/api'),
            'payment_timeout' => PaymentSetting::get('general.payment_timeout', 900)
        ];
    }

    private function getGroupFromKey(string $key): string
    {
        return explode('.', $key)[0];
    }

    private function getDescriptionFromKey(string $key): string
    {
        $descriptions = [
            'vietqr.bank_id' => 'Mã ngân hàng cho VietQR',
            'vietqr.account_no' => 'Số tài khoản ngân hàng',
            'vietqr.account_name' => 'Tên chủ tài khoản',
            'vietqr.template' => 'Template QR code',
            'vietqr.enabled' => 'Bật/tắt thanh toán VietQR',
            'cpay.timeout' => 'Timeout cho API CPay (giây)',
            'cpay.enabled' => 'Bật/tắt kiểm tra thanh toán CPay',
            'vnpay.enabled' => 'Bật/tắt thanh toán VNPay',
            'pay_at_hotel.enabled' => 'Bật/tắt thanh toán tại khách sạn',
            'general.default_payment_method' => 'Phương thức thanh toán mặc định',
            'general.api_base_url' => 'Base URL cho API',
            'general.payment_timeout' => 'Thời gian timeout thanh toán (giây)'
        ];

        return $descriptions[$key] ?? 'Cấu hình hệ thống';
    }

    private function shouldEncrypt(string $key): bool
    {
        $encryptedKeys = [
            'cpay.google_script_url',
            'vnpay.merchant_id',
            'vnpay.hash_secret'
        ];

        return in_array($key, $encryptedKeys);
    }

    private function getEnabledPaymentMethods(): array
    {
        $methods = [];
        
        if (PaymentSetting::get('vietqr.enabled', true)) {
            $methods[] = 'VietQR';
        }
        
        if (PaymentSetting::get('vnpay.enabled', false)) {
            $methods[] = 'VNPay';
        }
        
        if (PaymentSetting::get('pay_at_hotel.enabled', true)) {
            $methods[] = 'Thanh toán tại khách sạn';
        }
        
        return $methods;
    }
    
    /**
     * Admin view
     */
    public function adminIndex()
    {
        return view('admin.bookings.trading.index');
    }

    /**
     * Tạo booking mới từ frontend (phiên bản tối ưu - tạo tối thiểu data trước khi thanh toán)
     */
   public function createBooking(Request $request)
{
    Log::info('Create Booking Request Received:', $request->all());
    Log::info('Rooms data received:', ['rooms' => $request->input('rooms', [])]);
    Log::info('Total guests from request:', ['total_guests' => $request->input('total_guests')]);

    $validator = Validator::make($request->all(), [
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
        'total_guests' => 'required|integer|min:1',
        'total_price' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:vietqr,pay_at_hotel',
        'rooms' => 'required|array|min:1',
        'rooms.*.room_id' => 'required|integer',
        'rooms.*.room_type_id' => 'required|integer',
        'rooms.*.adults' => 'required|integer|min:1',
        'rooms.*.children' => 'integer|min:0',
        'coupon_code' => 'nullable|string|max:50', // Thêm validation cho coupon_code
    ]);

    if ($validator->fails()) {
        Log::error('Booking validation failed', $validator->errors()->toArray());
        return response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ.',
            'errors' => $validator->errors()
        ], 422);
    }

        DB::beginTransaction();
    try {
        $checkInDate = Carbon::parse($request->input('check_in'));
        $checkOutDate = Carbon::parse($request->input('check_out'));
        $nights = $checkInDate->diffInDays($checkOutDate);
        // Recalculate rooms total on server side from rooms payload to avoid FE/BE mismatch
        $calculatedRoomsTotal = 0;
        $roomsPayload = $request->input('rooms', []);
        // Detect if this is a package booking where package_id is present and same for all rooms
        $allHaveSamePackage = false;
        $firstPackageId = null;
        if (!empty($roomsPayload)) {
            $firstPackageId = $roomsPayload[0]['package_id'] ?? null;
            $allHaveSamePackage = $firstPackageId !== null;
            if ($allHaveSamePackage) {
                foreach ($roomsPayload as $r) {
                    if (($r['package_id'] ?? null) != $firstPackageId) {
                        $allHaveSamePackage = false;
                        break;
                    }
                }
            }
        }

        if ($allHaveSamePackage && $firstPackageId) {
            // For package bookings assume frontend provided total_price is authoritative for the package
            // Use provided total_price if available, otherwise fall back to the first room_price
            $providedTotalPrice = (float)$request->input('total_price');
            if ($providedTotalPrice > 0) {
                $calculatedRoomsTotal = $providedTotalPrice;
            } else {
                $firstRoom = $roomsPayload[0] ?? null;
                $calculatedRoomsTotal = $firstRoom ? (float)($firstRoom['room_price'] ?? 0) : 0;
            }
        } else {
            // Normal booking: sum per-room (room_price + option_price) * nights
            foreach ($roomsPayload as $roomData) {
                $roomPrice = isset($roomData['room_price']) ? (float)$roomData['room_price'] : 0;
                $optionPrice = isset($roomData['option_price']) ? (float)$roomData['option_price'] : 0;
                $calculatedRoomsTotal += ($roomPrice + $optionPrice) * $nights;
            }
        }

        $providedTotalPrice = (float)$request->input('total_price');
        if ($calculatedRoomsTotal > 0 && abs($calculatedRoomsTotal - $providedTotalPrice) > 1) {
            Log::warning('Client provided total_price differs from server-calculated rooms total', [
                'provided_total' => $providedTotalPrice,
                'calculated_rooms_total' => $calculatedRoomsTotal
            ]);
        }

        // Use server-calculated rooms total as canonical original total when available
        $originalTotalPrice = $calculatedRoomsTotal > 0 ? $calculatedRoomsTotal : $providedTotalPrice;
        $finalTotalPrice = $originalTotalPrice;

        // 1. Kiểm tra tính khả dụng phòng (giữ nguyên logic kiểm tra)
        $requestedRoomTypes = [];
        foreach ($request->input('rooms') as $roomData) {
            $room = Room::with('roomType')->find($roomData['room_id']);
            if ($room) {
                if (!isset($requestedRoomTypes[$room->room_type_id])) {
                    $requestedRoomTypes[$room->room_type_id] = [
                        'count' => 0,
                        'name' => $room->roomType ? $room->roomType->name : 'Room Type ' . $room->room_type_id
                    ];
                }
                $requestedRoomTypes[$room->room_type_id]['count']++;
            }
        }

        Log::info('Booking notes:', ['notes' => $request->input('notes')]);
        // Validate per-room guest counts against room type capacity to avoid impossible single-room requests
        foreach ($request->input('rooms') as $idx => $roomData) {
            $adults = isset($roomData['adults']) ? intval($roomData['adults']) : 1;
            $children = isset($roomData['children']) ? intval($roomData['children']) : 0;
            $requestedGuests = $adults + $children;

            $room = Room::with('roomType')->find($roomData['room_id']);
            $maxGuests = null;
            if ($room && isset($room->roomType) && isset($room->roomType->max_guests)) {
                $maxGuests = intval($room->roomType->max_guests);
            } elseif (isset($roomData['room_type_id'])) {
                $rt = \App\Models\RoomType::find($roomData['room_type_id']);
                if ($rt && isset($rt->max_guests)) $maxGuests = intval($rt->max_guests);
            }

            if ($maxGuests !== null && $requestedGuests > $maxGuests) {
                DB::rollBack();
                Log::warning('Requested guests exceed room type capacity', ['room_index' => $idx, 'requested' => $requestedGuests, 'max' => $maxGuests, 'room_data' => $roomData]);
                return response()->json([
                    'success' => false,
                    'message' => "Số khách cho một phòng vượt quá sức chứa tối đa cho loại phòng (yêu cầu: {$requestedGuests}, tối đa: {$maxGuests}).",
                    'errors' => ["rooms.{$idx}" => ["Requested guests ({$requestedGuests}) exceed max guests ({$maxGuests}) for this room type."]]
                ], 422);
            }
        }

        // 3. Tạo booking record tối thiểu (chỉ thông tin cơ bản)
        $userId = null;
        // Nếu đã đăng nhập, lấy user id từ request (middleware hoặc FE truyền lên)
        if ($request->user()) {
            $userId = $request->user()->id;
            Log::info('User is authenticated, user_id set from request->user()', ['user_id' => $userId]);
        } elseif ($request->has('user_id')) {
            $userId = $request->input('user_id');
            Log::info('User_id received from request input', ['user_id' => $userId]);
        } else {
            // If not authenticated, try to find existing user by email or create a guest user
            $email = $request->input('customer_email');
            $name = $request->input('customer_name');
            $phone = $request->input('customer_phone');

            if ($email) {
                $existingUser = \App\Models\User::where('email', $email)->first();
                if ($existingUser) {
                    $userId = $existingUser->id;
                    Log::info('Found existing user by email for booking', ['email' => $email, 'user_id' => $userId]);
                } else {
                    // Create a guest user with random password and assign 'guest' role
                    $randomPassword = Str::random(12);
                    $newUser = \App\Models\User::create([
                        'name' => $name ?? 'Khách hàng',
                        'email' => $email,
                        'phone' => $phone,
                        'password' => Hash::make($randomPassword),
                    ]);
                    if ($newUser) {
                        try {
                            $newUser->assignRole('guest');
                        } catch (\Exception $e) {
                            // If role assignment fails, log but continue
                            Log::warning('Failed to assign guest role to new user: ' . $e->getMessage(), ['user_id' => $newUser->id]);
                        }
                        $userId = $newUser->id;
                        Log::info('Created guest user for booking', ['email' => $email, 'user_id' => $userId]);
                    }
                }
            } else {
                Log::info('No user email provided, booking will remain guest without user account');
            }
        }

        $booking = Booking::create([
            'booking_code' => '', // Will be updated after creation
            'guest_name' => $request->input('customer_name'),
            'guest_email' => $request->input('customer_email'),
            'guest_phone' => $request->input('customer_phone'),
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'guest_count' => $request->input('total_guests'),
            'total_price_vnd' => $finalTotalPrice, // Sẽ được cập nhật sau khi áp dụng coupon
            'status' => 'pending', // Vẫn pending cho đến khi thanh toán
            'notes' => $request->input('notes'),
            'room_type_id' => intval($request->input('room_type_id', 0)) ?: null,
            'user_id' => $userId,
            'room_id' => null, // Sẽ được cập nhật sau khi thanh toán
            'option_id' => null, // Sẽ cập nhật sau khi có booking_code
        ]);
        Log::info('Booking created, user_id in booking:', ['user_id' => $booking->user_id, 'booking_id' => $booking->booking_id]);

        // 2. Kiểm tra availability
        foreach ($requestedRoomTypes as $roomTypeId => $details) {
            $requestedCount = $details['count'];
            $totalRoomsOfType = Room::where('room_type_id', $roomTypeId)->count();
            $bookedCount = Booking::where('booking.status', 'confirmed')
                ->join('booking_rooms', 'booking.booking_id', '=', 'booking_rooms.booking_id')
                ->join('room', 'room.room_id', '=', 'booking_rooms.room_id')
                ->where('room.room_type_id', $roomTypeId)
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('booking.check_in_date', '<', $checkOutDate)
                          ->where('booking.check_out_date', '>', $checkInDate);
                })
                ->count();

            $availableCount = $totalRoomsOfType - $bookedCount;
            if ($availableCount < $requestedCount) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Rất tiếc, chúng tôi không còn đủ phòng loại '{$details['name']}' cho khoảng thời gian bạn chọn. Chỉ còn {$availableCount} phòng trống.",
                ], 409);
            }
        }

        // ==== THÊM PHẦN XỬ LÝ COUPON ====
        $couponApplied = null;
        $couponDiscount = 0;
        
        // Xử lý mã giảm giá nếu có
        if ($request->filled('coupon_code')) {
            $couponService = app(CouponService::class);
            $user = $request->user();

            // Extract totals robustly (support different naming styles from frontend)
            $totalsInput = $request->input('totals', $request->input('Totals', []));
            $roomsTotalCandidate = null;
            if (is_array($totalsInput)) {
                $roomsTotalCandidate = $totalsInput['roomsTotal'] ?? $totalsInput['rooms_total'] ?? null;
            }
            $roomsTotalCandidate = $roomsTotalCandidate ?? $request->input('roomsTotal') ?? $request->input('rooms_total') ?? null;

            // If frontend provides rooms total, prefer that as the base amount; otherwise fall back
            $baseAmount = $roomsTotalCandidate ?? $originalTotalPrice;

            Log::info('Applying coupon: using base amount for calculation', [
                'rooms_total_candidate' => $roomsTotalCandidate,
                'base_amount' => $baseAmount,
                'original_total_price_from_request' => $originalTotalPrice,
                'booking_total_before' => $booking->total_price_vnd,
                'coupon_code' => $request->input('coupon_code')
            ]);

            // If frontend already applied coupon (roomsTotal provided and matches calculation), avoid re-applying
            $couponModel = \App\Models\Coupon::where('code', strtoupper($request->input('coupon_code')))->first();
            if ($couponModel && $roomsTotalCandidate !== null) {
                $expected = $couponModel->applyToAmount((float)$roomsTotalCandidate);
                // Allow small rounding variance (1 VND)
                if (abs($expected['new_total'] - (float)$originalTotalPrice) <= 1) {
                    Log::info('Frontend appears to have already applied coupon; creating redemption using provided totals', [
                        'rooms_total' => $roomsTotalCandidate,
                        'expected_new_total' => $expected['new_total'],
                        'provided_final_total' => $originalTotalPrice,
                    ]);

                    // Ensure booking has the raw base amount so the service creates a correct redemption
                    $booking->total_price_vnd = $roomsTotalCandidate;

                    $couponResult = $couponService->applyCouponToBooking(
                        $booking,
                        $request->input('coupon_code'),
                        $user
                    );
                } else {
                    // Normal path: use baseAmount and apply
                    $booking->total_price_vnd = $baseAmount;
                    $couponResult = $couponService->applyCouponToBooking(
                        $booking,
                        $request->input('coupon_code'),
                        $user
                    );
                }
            } else {
                // No coupon model or no rooms total provided, apply normally using baseAmount
                $booking->total_price_vnd = $baseAmount;
                $couponResult = $couponService->applyCouponToBooking(
                    $booking,
                    $request->input('coupon_code'),
                    $user
                );
            }
            
            if (!$couponResult['success']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $couponResult['message'],
                    'error_type' => 'coupon_error'
                ], 422);
            }
            
            $couponApplied = $couponResult['redemption'];
            $couponDiscount = $couponResult['discount_amount'];
            $finalTotalPrice = $couponResult['new_total'];
            
            // Cập nhật booking với total price mới (đã được cập nhật tự động trong CouponService)
            $booking = $booking->fresh(); // Reload booking data
            
            Log::info('Coupon applied successfully', [
                'coupon_code' => $request->input('coupon_code'),
                'original_price' => $originalTotalPrice,
                'discount' => $couponDiscount,
                'final_price' => $finalTotalPrice
            ]);
        }

        Log::info('Booking notes:', ['notes' => $request->input('notes')]);
        
        // Generate booking code
        $bookingCode = 'LVS' . $booking->booking_id . now()->format('His');
        $booking->booking_code = $bookingCode;
        $booking->save();
        Log::info('Booking updated with booking_code:', ['booking_code' => $bookingCode, 'booking_id' => $booking->booking_id]);

        // Generate option_id for later use (do NOT set in booking yet)
        $optionId = 'BOOK-' . $bookingCode;

        // 4. Lưu thông tin rooms vào session/cache để xử lý sau khi thanh toán thành công
        // Sinh option_id duy nhất cho booking
        $roomsData = [
            'rooms' => $request->input('rooms'),
            'customer_id_card' => $request->input('customer_id_card', ''),
            'payment_method' => $request->input('payment_method'),
            'nights' => $nights,
            'option_id' => $optionId, // Lưu option_id để dùng sau khi thanh toán
            'coupon_applied' => $couponApplied ? [
                'id' => $couponApplied->id,
                'coupon_code' => $couponApplied->coupon->code,
                'discount_amount' => $couponDiscount
            ] : null // Lưu thông tin coupon để hiển thị
        ];
        Log::info('Rooms data cached with option_id:', ['option_id' => $optionId, 'booking_code' => $bookingCode]);
        // Lưu vào cache với key là booking_code để xử lý sau
        cache()->put("booking_rooms_data_{$bookingCode}", $roomsData, now()->addHours(24));

 
        // Normalize payment method from frontend to DB enum values
        $rawMethod = $request->input('payment_method');
        $methodMap = [
            'pay_at_hotel' => 'at_hotel',
            'vietqr' => 'vietqr',
            'vnpay' => 'vnpay',
            'qr' => 'qr_code',
            'deposit' => 'deposit',
            'full' => 'full'
        ];

        $normalizedPaymentType = $methodMap[$rawMethod] ?? $rawMethod;
        Log::info('Normalized payment method for DB insert', ['raw' => $rawMethod, 'normalized' => $normalizedPaymentType]);

        $payment = Payment::create([
            'booking_id' => $booking->booking_id,
            'amount_vnd' => $booking->total_price_vnd, // Sử dụng giá đã giảm
            'payment_type' => $normalizedPaymentType,
            'status' => 'pending',
        ]);

        // 6. Nếu là pay_at_hotel, tự động xử lý đầy đủ booking
        if ($request->input('payment_method') === 'pay_at_hotel') {
            $this->completeBookingAfterPayment($bookingCode);
        }

        DB::commit();

        Log::info('Booking created successfully with minimal data:', [
            'booking_code' => $bookingCode,
            'booking_id' => $booking->booking_id,
            'payment_method' => $request->input('payment_method'),
            'rooms_cached' => count($request->input('rooms')),
            'coupon_applied' => $couponApplied ? $couponApplied->coupon->code : null,
            'original_price' => $originalTotalPrice,
            'final_price' => $booking->total_price_vnd
        ]);

        $responseData = [
            'success' => true,
            'message' => 'Đặt phòng thành công!',
            'booking_code' => $bookingCode,
            'booking_id' => $booking->booking_id,
            'total_price' => $booking->total_price_vnd,
        ];

        // Thêm thông tin coupon vào response nếu có
        if ($couponApplied) {
            $responseData['coupon'] = [
                'code' => $couponApplied->coupon->code,
                'discount_amount' => $couponDiscount,
                'original_total' => $originalTotalPrice,
                'final_total' => $booking->total_price_vnd,
                'savings' => $couponDiscount
            ];
        }

        return response()->json($responseData, 201);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating booking: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi trong quá trình đặt phòng. Vui lòng thử lại.',
            'error_details' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Tạo URL thanh toán VNPay (Sẽ được điều chỉnh hoặc loại bỏ)
     */
    public function createVNPayPayment($booking)
    {
        // ... (Logic VNPay cũ, sẽ không được gọi trong luồng pay_at_hotel)
    }

    /**
     * Xử lý callback từ VNPay (Sẽ được điều chỉnh hoặc loại bỏ)
     */
    public function handleVNPayReturn(Request $request)
    {
        // ... (Logic VNPay cũ)
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay (Sẽ được điều chỉnh hoặc loại bỏ)
     */
    public function handleVNPayIPN(Request $request)
    {
        // ... (Logic VNPay cũ)
    }

    /**
     * Admin: Lấy danh sách booking chờ thanh toán
     */    public function getPendingPayments(Request $request)
    {
        try {
            $query = Payment::with(['room', 'roomType']);

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('payment_status', $request->status);
            }

            // Filter by date
            if ($request->has('date') && $request->date !== '') {
                $query->whereDate('created_at', $request->date);
            }

            // Search
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting pending payments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting pending payments'
            ], 500);
        }
    }

    /**
     * Admin: Confirm payment manually
     */
    public function confirmPayment(Request $request, $bookingId)
    {
        try {
            DB::beginTransaction();

            // Find the booking
            $booking = Booking::findOrFail($bookingId);

            if ($booking->status === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already confirmed'
                ]);
            }

            // Update booking status to confirmed
            $booking->update([
                'status' => 'confirmed'
            ]);

            // Update payment status to completed
            $payment = Payment::where('booking_id', $booking->booking_id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed'
                ]);
            }

            DB::commit();

            // Gửi email xác nhận đặt phòng
            $this->sendBookingConfirmationEmail($booking->booking_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error confirming payment'
            ], 500);
        }
    }

    /**
     * Admin: Get booking history with filters
     */
    public function getBookingHistory(Request $request)
    {
        try {
            $query = Payment::query();

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('payment_status', $request->status);
            }

            // Filter by payment method
            if ($request->has('payment_method') && $request->payment_method !== '') {
                $query->where('payment_method', $request->payment_method);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->start_date !== '') {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date !== '') {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Search
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking history'
            ], 500);
        }
    }

    /**
     * Admin: Get booking statistics
     */
    public function getBookingStats(Request $request)
    {
        try {
            $today = Carbon::today();
            $thisWeek = Carbon::now()->startOfWeek();
            $thisMonth = Carbon::now()->startOfMonth();

            $stats = [
                'today' => [
                    'total' => Payment::whereDate('created_at', $today)->count(),
                    'confirmed' => Payment::whereDate('created_at', $today)->where('payment_status', 'confirmed')->count(),
                    'pending' => Payment::whereDate('created_at', $today)->where('payment_status', 'pending')->count(),
                    'revenue' => Payment::whereDate('created_at', $today)->where('payment_status', 'confirmed')->sum('total_amount')
                ],
                'this_week' => [
                    'total' => Payment::where('created_at', '>=', $thisWeek)->count(),
                    'confirmed' => Payment::where('created_at', '>=', $thisWeek)->where('payment_status', 'confirmed')->count(),
                    'pending' => Payment::where('created_at', '>=', $thisWeek)->where('payment_status', 'pending')->count(),
                    'revenue' => Payment::where('created_at', '>=', $thisWeek)->where('payment_status', 'confirmed')->sum('total_amount')
                ],
                'this_month' => [
                    'total' => Payment::where('created_at', '>=', $thisMonth)->count(),
                    'confirmed' => Payment::where('created_at', '>=', $thisMonth)->where('payment_status', 'confirmed')->count(),
                    'pending' => Payment::where('created_at', '>=', $thisMonth)->where('payment_status', 'pending')->count(),
                    'revenue' => Payment::where('created_at', '>=', $thisMonth)->where('payment_status', 'confirmed')->sum('total_amount')
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking stats'
            ], 500);
        }
    }

    /**
     * Get booking details by booking code
     */    public function getBookingDetails($bookingCode)
    {
        try {
            $booking = Payment::with(['room', 'roomType'])
                            ->where('booking_code', $bookingCode)
                            ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->customer_name,
                    'customer_email' => $booking->customer_email,
                    'customer_phone' => $booking->customer_phone,
                    'room' => $booking->room,
                    'room_type' => $booking->roomType,
                    'quantity' => $booking->quantity,
                    'total_amount' => $booking->total_amount,
                    'payment_method' => $booking->payment_method,
                    'payment_status' => $booking->payment_status,
                    'check_in' => $booking->check_in,
                    'check_out' => $booking->check_out,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at,
                    'payment_confirmed_at' => $booking->payment_confirmed_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking details'
            ], 500);
        }
    }

    /**
     * Admin: Get confirmed bookings (auto-approved after payment)
     */
    public function getConfirmedBookings(Request $request)
    {
        try {
            $query = Payment::where('booking_status', 'confirmed');

            // Filter by date
            if ($request->has('date') && $request->date !== '') {
                $query->whereDate('payment_confirmed_at', $request->date);
            }

            // Search
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('payment_confirmed_at', 'desc')
                            ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting confirmed bookings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting confirmed bookings'
            ], 500);
        }
    }

   

    /**
     * Check payment status (Frontend polling)
     */
    public function checkPaymentStatus($bookingId)
    {
        try {
            $booking = Payment::where('booking_code', $bookingId)
                           ->orWhere('id', $bookingId)
                           ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment_status' => $booking->payment_status,
                'booking_status' => $booking->booking_status ?? 'pending',
                'payment_confirmed_at' => $booking->payment_confirmed_at,
                'booking_code' => $booking->booking_code
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status'
            ], 500);
        }
    }

    /**
     * Cập nhật phương thức thanh toán cho booking
     */
    public function updatePaymentMethod(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'payment_method' => 'required|in:vietqr,vnpay,pay_at_hotel'
            ]);

            $bookingCode = $request->booking_code;
            $paymentMethod = $request->payment_method;

            Log::info("Updating payment method for booking: {$bookingCode} to {$paymentMethod}");

            // Find booking by booking_code
            $booking = DB::table('bookings')
                ->where('booking_code', $bookingCode)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Update payment method
            DB::table('bookings')
                ->where('booking_code', $bookingCode)
                ->update([
                    'payment_method' => $paymentMethod,
                    'updated_at' => now()
                ]);

            // If payment method is pay_at_hotel, auto-approve the booking
            if ($paymentMethod === 'pay_at_hotel') {
                DB::table('bookings')
                    ->where('booking_code', $bookingCode)
                    ->update([
                        'booking_status' => 'approved',
                        'updated_at' => now()
                    ]);

                Log::info("Booking {$bookingCode} auto-approved for pay_at_hotel method");
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment method updated successfully',
                'booking_code' => $bookingCode,
                'payment_method' => $paymentMethod
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating payment method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment method'
            ], 500);
        }
    }

    /**
     * Create VNPay payment URL (API endpoint)
     */
    public function createVNPayPaymentAPI(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'amount' => 'required|numeric|min:1000',
                'payment_method' => 'required|in:vnpay'
            ]);

            Log::info("Creating VNPay payment URL for API", [
                'booking_code' => $request->booking_code,
                'amount' => $request->amount
            ]);

            // Find booking by booking_code
            $booking = DB::table('bookings')
                ->where('booking_code', $request->booking_code)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Create VNPay payment URL
            $vnpayUrl = $this->createVNPayPayment((object)[
                'booking_code' => $request->booking_code,
                'total_amount' => $request->amount
            ]);

            // Update booking payment method
            DB::table('bookings')
                ->where('booking_code', $request->booking_code)
                ->update([
                    'payment_method' => 'vnpay',
                    'updated_at' => now()
                ]);

            Log::info("VNPay payment URL created successfully", [
                'booking_code' => $request->booking_code,
                'vnpay_url' => $vnpayUrl
            ]);

            return response()->json([
                'success' => true,
                'vnpay_url' => $vnpayUrl,
                'booking_code' => $request->booking_code
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating VNPay payment URL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating VNPay payment URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create VietQR payment
     */
    public function createVietQRPayment(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'amount' => 'required|numeric|min:1000',
            ]);

            Log::info("Creating VietQR payment for booking", [
                'booking_code' => $request->booking_code,
                'amount' => $request->amount
            ]);

            // Find booking by booking_code
            $booking = Booking::where('booking_code', $request->booking_code)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Generate QR code data
            $qrData = [
                'bank_id' => 'VCB', // Vietcombank
                'account_no' => '1234567890', // Tài khoản ngân hàng của khách sạn
                'template' => 'compact',
                'amount' => $request->amount,
                'description' => 'Thanh toan dat phong ' . $request->booking_code,
                'account_name' => 'LAVISHSTAY HOTEL'
            ];

            // Create VietQR URL
            $vietQRUrl = $this->generateVietQRUrl($qrData);

            // Update booking payment method
            $booking->update([
                'payment_method' => 'vietqr',
                'updated_at' => now()
            ]);

            // Update payment record
            $payment = Payment::where('booking_id', $booking->booking_id)->first();
            if ($payment) {
                $payment->update([
                    'payment_type' => 'vietqr',
                    'status' => 'pending',
                    'updated_at' => now()
                ]);
            }

            Log::info("VietQR payment created successfully", [
                'booking_code' => $request->booking_code,
                'vietqr_url' => $vietQRUrl
            ]);

            return response()->json([
                'success' => true,
                'vietqr_url' => $vietQRUrl,
                'qr_data' => $qrData,
                'booking_code' => $request->booking_code,
                'amount' => $request->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating VietQR payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating VietQR payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate VietQR URL
     */
    public function generateVietQRUrl($qrData)
    {
        $baseUrl = 'https://img.vietqr.io/image/';
        $bankId = $qrData['bank_id'];
        $accountNo = $qrData['account_no'];
        $template = $qrData['template'];
        $amount = $qrData['amount'];
        $description = urlencode($qrData['description']);
        $accountName = urlencode($qrData['account_name']);

        return "{$baseUrl}{$bankId}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$description}&accountName={$accountName}";
    }

    /**
     * Verify VietQR payment (webhook or manual check)
     */
    public function verifyVietQRPayment(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'transaction_id' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            DB::beginTransaction();

            // Find booking
            $booking = Booking::where('booking_code', $request->booking_code)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Kiểm tra và hoàn thành tạo booking nếu có cached data
            $roomsData = cache()->get("booking_rooms_data_{$request->booking_code}");
            
            if ($roomsData) {
                // Có cached data, hoàn thành tạo booking (tạo tất cả data cần thiết)
                $this->completeBookingAfterPayment($request->booking_code);
            } else {
                Log::warning("No cached rooms data found for booking: {$request->booking_code}, skip completing booking");
            }

            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'updated_at' => now()
            ]);

            // Update payment status
            $payment = Payment::where('booking_id', $booking->booking_id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $request->transaction_id,
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'booking_code' => $request->booking_code
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error verifying VietQR payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment'
            ], 500);
        }
    }

    /**
     * Get booking details with rooms and options
     */
    public function getBookingWithRooms($bookingCode)
    {
        try {
            Log::info('getBookingWithRooms called', ['booking_code' => $bookingCode]);
            // Lấy thông tin booking chính
            $booking = Booking::where('booking_code', $bookingCode)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Lấy thông tin các phòng đã đặt với option
            $bookingRooms = DB::table('booking_rooms as br')
                ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
                ->leftJoin('room_option as ro', 'br.option_id', '=', 'ro.option_id')
                ->leftJoin('representatives as rep', 'br.booking_id', '=', 'rep.booking_id')
                ->where('br.booking_id', $booking->booking_id)
                ->select([
                    'br.*',
                    'r.name as room_name',
                    'r.description as room_description',
                    'ro.name as selected_option_name',
                    'ro.price_per_night_vnd as selected_option_price',
                    // use existing policy id fields from room_option model (avoid selecting non-existent columns)
                    'ro.cancellation_policy_id as cancellation_policy_id',
                    'ro.deposit_policy_id as deposit_policy_id',
                    'rep.full_name as representative_name',
                    'rep.phone_number as representative_phone',
                    'rep.email as representative_email'
                ])
                ->get();

            // Lấy thông tin payment
            $payment = Payment::where('booking_id', $booking->booking_id)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'rooms' => $bookingRooms,
                    'payment' => $payment,
                    'summary' => [
                        'total_rooms' => $bookingRooms->count(),
                        'total_guests' => $bookingRooms->sum('adults') + $bookingRooms->sum('children'),
                        'total_amount' => $booking->total_price_vnd,
                        'payment_status' => $payment ? $payment->status : 'unknown'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking details with rooms: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking details'
            ], 500);
        }
    }
   
    /**
     * Gửi email xác nhận đặt phòng
     */
    public function sendBookingConfirmationEmail($bookingId)
    {
        try {
            Log::info('Starting to send booking confirmation email for booking ID: ' . $bookingId);
            
            // Lấy thông tin booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                Log::error('Booking not found for ID: ' . $bookingId);
                return false;
            }

            // Lấy thông tin booking rooms
            $bookingRooms = DB::table('booking_rooms as br')
                ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
                ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('br.booking_id', $bookingId)
                ->select([
                    'br.*',
                    'r.name as room_name',
                    'rt.name as room_type_name'
                ])
                ->get();

            // Lấy thông tin representative (người đại diện)
            $representative = DB::table('representatives')
                ->where('booking_id', $bookingId)
                ->first();

            if (!$representative) {
                Log::warning('No representative found for booking ID: ' . $bookingId . ', using booking guest info');
            }

            // Địa chỉ email nhận
            $recipientEmail = $representative ? $representative->email : $booking->guest_email;
            
            if (!$recipientEmail) {
                Log::error('No email address found for booking ID: ' . $bookingId);
                return false;
            }

            Log::info('Sending booking confirmation email to: ' . $recipientEmail);

            // Gửi email
            Mail::to($recipientEmail)->send(new BookingConfirmation($booking, $bookingRooms, $representative));

            Log::info('Booking confirmation email sent successfully for booking ID: ' . $bookingId);
            return true;

        } catch (\Exception $e) {
            Log::error('Error sending booking confirmation email for booking ID ' . $bookingId . ': ' . $e->getMessage());
        }
    }
    /**
     * Test email functionality - Route: /api/test-email/{bookingId}
     */
    public function testEmail($bookingId)
    {
        try {
            Log::info('Testing email for booking ID: ' . $bookingId);
            
            $result = $this->sendBookingConfirmationEmail($bookingId);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email test thành công! Email đã được gửi.',
                    'booking_id' => $bookingId
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email test thất bại. Vui lòng kiểm tra log để biết chi tiết.',
                    'booking_id' => $bookingId
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error in email test: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi trong quá trình test email: ' . $e->getMessage(),
                'booking_id' => $bookingId
            ], 500);
        }
    }

    /**
     * Helper method to extract price from various formats
     */
    public function extractPrice($price)
    {
        if (empty($price)) {
            return 0;
        }
        
        if (is_array($price) || is_object($price)) {
            // Handle price object/array format like { vnd: 1000000 }
            $priceArray = (array) $price;
            if (isset($priceArray['vnd'])) {
                return (float) $priceArray['vnd'];
            } elseif (isset($priceArray['value'])) {
                return (float) $priceArray['value'];
            } else {
                // Try to get the first numeric value from the array/object
                foreach ($priceArray as $value) {
                    if (is_numeric($value)) {
                        return (float) $value;
                    }
                }
                return 0;
            }
        }
        
        return (float) $price;
    }

    /**
     * Helper method to process children ages
     */
    public function processChildrenAge($childrenAges)
    {
        if (empty($childrenAges)) {
            return json_encode([]);
        }
        
        if (is_array($childrenAges)) {
            // If it's already an array, encode it
            return json_encode($childrenAges);
        }
        
        if (is_string($childrenAges)) {
            // Check if it's already a JSON string
            $decoded = json_decode($childrenAges, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // It's valid JSON, return as is
                return $childrenAges;
            }
            
            // Try to parse it as a comma-separated list
            $ages = explode(',', $childrenAges);
            if (count($ages) > 0) {
                // Trim each age and convert to number if possible
                $processedAges = array_map(function($age) {
                    $trimmed = trim($age);
                    return is_numeric($trimmed) ? (int)$trimmed : $trimmed;
                }, $ages);
                return json_encode($processedAges);
            }
        }
        
        // If we couldn't process it, return an empty array
        return json_encode([]);
    }

    /**
     * Check CPay payment status (called by frontend to verify payments)
     */
    public function checkCPayPayment(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            $bookingCode = $request->booking_code;
            $expectedAmount = $request->amount;

            Log::info("Checking CPay payment for booking: {$bookingCode}, amount: {$expectedAmount}");

            // Find booking by booking_code
            $booking = Booking::where('booking_code', $bookingCode)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking không tồn tại'
                ], 404);
            }

            // Check if payment already exists and is completed
            $payment = Payment::where('booking_id', $booking->booking_id)->first();

            if ($payment && $payment->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Giao dịch đã được xác nhận trước đó',
                    'transaction' => [
                        'id' => $payment->id,
                        'booking_code' => $bookingCode,
                        'amount' => $payment->amount_vnd,
                        'status' => 'completed',
                        'payment_method' => 'vietqr',
                        'created_at' => $payment->created_at,
                        'updated_at' => $payment->updated_at
                    ]
                ]);
            }

            // Here you would integrate with actual CPay API to check for transactions
            // For now, we'll simulate checking and return not found since we don't have real CPay integration
            
            // Simulate checking CPay transactions (replace with actual CPay API call)
            $cPayTransaction = $this->checkCPayAPI($bookingCode, $expectedAmount);

            if ($cPayTransaction) {
                // Transaction found in CPay, update our records
                DB::beginTransaction();
                
                try {
                    // Kiểm tra xem có rooms data cached không trước khi complete booking
                    $roomsData = cache()->get("booking_rooms_data_{$bookingCode}");
                    
                    if ($roomsData) {
                        // Có cached data, hoàn thành booking trước
                        $this->completeBookingAfterPayment($bookingCode);
                    } else {
                        Log::warning("No cached rooms data found for booking: {$bookingCode}, skip completing booking");
                    }

                    // Update booking status
                    $booking->update([
                        'status' => 'confirmed'
                    ]);

                    // Update payment status
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'transaction_id' => $cPayTransaction['transaction_id'],
                            'updated_at' => now()
                        ]);
                    } else {
                        // Create payment record if doesn't exist
                        $payment = Payment::create([
                            'booking_id' => $booking->booking_id,
                            'amount_vnd' => $expectedAmount,
                            'payment_type' => 'vietqr',
                            'status' => 'completed',
                            'transaction_id' => $cPayTransaction['transaction_id']
                        ]);
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Giao dịch thanh toán đã được xác nhận',
                        'transaction' => [
                            'id' => $payment->id,
                            'booking_code' => $bookingCode,
                            'amount' => $payment->amount_vnd,
                            'status' => 'completed',
                            'payment_method' => 'vietqr',
                            'transaction_id' => $cPayTransaction['transaction_id'],
                            'created_at' => $payment->created_at,
                            'updated_at' => $payment->updated_at
                        ]
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            // Transaction not found
            return response()->json([
                'success' => false,
                'message' => 'Chưa tìm thấy giao dịch thanh toán. Vui lòng đợi vài phút và thử lại.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking CPay payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi kiểm tra thanh toán'
            ], 500);
        }
    }

    /**
     * Call actual CPay Google Apps Script API
     */
    public function checkCPayAPI($bookingCode, $expectedAmount)
    {
        try {
            Log::info("Calling CPay Google Apps Script API for booking: {$bookingCode}, amount: {$expectedAmount}");
            
            // URL của Google Apps Script đã deploy
            $cPayApiUrl = env('CPAY_GOOGLE_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbx8VhqXhSp0oY1uPrcM9nEr3iZZE2b8u8nXq7dKYX3UXQ0PmDe5Yh4sYNfU-QNGRgDN/exec');
            
            // Prepare request data
            $requestData = [
                'action' => 'checkPayment',
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ];
            
            Log::info("CPay API Request", $requestData);
            
            // Make HTTP request to Google Apps Script with SSL verification disabled for development
            $httpClient = Http::timeout(30);
            
            // Disable SSL verification for local development
            if (env('APP_ENV') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false,  // Disable SSL verification for local dev
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ]);
                Log::info("CPay API: SSL verification disabled for local development");
            }
            
            $response = $httpClient->asForm()->post($cPayApiUrl, $requestData);
            Log::info("CPay API Response Status: " . $response->status());
            Log::info("CPay API HTTP Status: " . $response->status());
            
            if (!$response->successful()) {
                Log::error("CPay API HTTP Error: " . $response->status() . " - " . $response->body());
                throw new \Exception("CPay API returned HTTP " . $response->status());
            }
            
            $responseData = $response->json();
            Log::info("CPay API Response", $responseData ?? ['raw_body' => $response->body()]);
            
            // Check if payment was found
            if ($responseData['status'] === 'success' && !empty($responseData['data'])) {
                $transaction = $responseData['data'][0]; // Get first matching transaction
                
                Log::info("✅ CPay payment found", $transaction);
                
                return [
                    'transaction_id' => 'CPAY_' . $bookingCode . '_' . time(),
                    'amount' => $transaction['amount'],
                    'status' => 'success',
                    'bank_name' => $transaction['bank'] ?? 'Unknown Bank',
                    'account_number' => $transaction['account'] ?? '',
                    'transaction_time' => now(),
                    'description' => $transaction['content'] ?? "Thanh toan dat phong {$bookingCode}",
                    'reference_code' => $transaction['reference_code'] ?? '',
                    'cpay_data' => $transaction // Store original CPay data
                ];
            } else {
                Log::info("❌ CPay payment not found for booking: {$bookingCode}");
                return null;
            }
            
        } catch (\Exception $e) {
            Log::error('Error calling CPay API: ' . $e->getMessage());
            
            // Fallback for development environment - Always try fallback in local
            // if (env('APP_ENV') === 'local') {
            //     Log::info("Using fallback auto-confirm for development due to error: " . $e->getMessage());
            //     return [
            //         'transaction_id' => 'DEV_' . $bookingCode . '_' . time(),
            //         'amount' => $expectedAmount,
            //         'status' => 'success',
            //         'bank_name' => 'MB Bank',
            //         'account_number' => '0335920306',
            //         'transaction_time' => now(),
            //         'description' => "DEV: Thanh toan dat phong {$bookingCode}",
            //         'reference_code' => 'DEV_REF_' . $bookingCode,
            //         'cpay_data' => [
            //             'fallback' => true,
            //             'error' => $e->getMessage(),
            //             'timestamp' => now()->toISOString()
            //         ]
            //     ];
            // }
            
            return null;
        }
    }

    /**
     * Debug endpoint để test CPay API
     */
    public function debugCPayAPI(Request $request)
    {
        try {
            $bookingCode = $request->get('booking_code', 'LVS78084846');
            $expectedAmount = $request->get('amount', 22000);

            Log::info("🔧 DEBUG: Testing CPay API", [
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount,
                'app_env' => env('APP_ENV'), 
                'cpay_auto_confirm' => env('CPAY_AUTO_CONFIRM'),
                'cpay_url' => env('CPAY_GOOGLE_SCRIPT_URL')
            ]);

            $result = $this->checkCPayAPI($bookingCode, $expectedAmount);

            return response()->json([
                'success' => true,
                'cpay_result' => $result,
                'environment' => [
                    'app_env' => env('APP_ENV'),
                    'cpay_auto_confirm' => env('CPAY_AUTO_CONFIRM'),
                    'cpay_url' => env('CPAY_GOOGLE_SCRIPT_URL')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Debug CPay API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Hoàn thành việc tạo booking sau khi thanh toán thành công
     * Method này sẽ tạo tất cả data cần thiết (representatives, room_options, booking_rooms, etc.)
     */
    public function completeBookingAfterPayment($bookingCode)
    {
        try {
            Log::info("Completing booking creation after payment for: {$bookingCode}");

            // Ensure flags are initialized to avoid undefined variable errors in logs
            $usedSmartAssignment = false;


            // Lấy thông tin booking
            $booking = Booking::where('booking_code', $bookingCode)->first();
            if (!$booking) {
                throw new \Exception("Booking not found: {$bookingCode}");
            }

            // Lấy rooms data từ cache
            $roomsData = cache()->get("booking_rooms_data_{$bookingCode}");
            if (!$roomsData) {
                Log::warning("No cached rooms data found for booking: {$bookingCode}");
                return; // Không throw exception, chỉ log warning
            }

            $rooms = $roomsData['rooms'] ?? [];
            $customerIdCard = $roomsData['customer_id_card'] ?? '';
            $paymentMethod = $roomsData['payment_method'] ?? 'vietqr';
            $nights = $roomsData['nights'] ?? 1;

            if (empty($rooms)) {
                Log::warning("Empty rooms data for booking: {$bookingCode}");
                return;
            }

            $checkInDate = $booking->check_in_date;
            $checkOutDate = $booking->check_out_date;

            // Tạo representative chính cho booking
            $mainRepresentativeId = DB::table('representatives')->insertGetId([
                'booking_id' => $booking->booking_id,
                'booking_code' => $bookingCode,
                'room_id' => NULL,
                'full_name' => $booking->guest_name,
                'phone_number' => $booking->guest_phone,
                'email' => $booking->guest_email,
                'id_card' => $customerIdCard,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo 1 room_option duy nhất cho booking (dùng dữ liệu từ phòng đầu tiên)
            $optionId = 'BOOK-' . $bookingCode;
            $firstRoom = $rooms[0];
            $cancellationPolicyId = $firstRoom['policies']['cancellation']['policy_id'] ?? null;
            $depositPolicyId = $firstRoom['policies']['deposit']['policy_id'] ?? null;
            $checkOutPolicyId = $firstRoom['policies']['check_out']['policy_id'] ?? null;
            
            // Validate foreign key references - set to null if they don't exist
            $mealType = $firstRoom['meal_type'] ?? null;
            $bedType = $firstRoom['bed_type'] ?? null;
            $packageId = $firstRoom['package_id'] ?? null;
            
            // Validate that foreign key IDs actually exist in their respective tables
            if ($mealType && !DB::table('meal_types')->where('id', $mealType)->exists()) {
                Log::warning("Invalid meal_type ID: {$mealType}, setting to null");
                $mealType = null;
            }
            
            if ($bedType && !DB::table('bed_types')->where('id', $bedType)->exists()) {
                Log::warning("Invalid bed_type ID: {$bedType}, setting to null");
                $bedType = null;
            }
            
            if ($packageId && !DB::table('room_type_package')->where('package_id', $packageId)->exists()) {
                Log::warning("Invalid package_id: {$packageId}, setting to null");
                $packageId = null;
            }
            
            if ($depositPolicyId && !DB::table('deposit_policies')->where('policy_id', $depositPolicyId)->exists()) {
                Log::warning("Invalid deposit_policy_id: {$depositPolicyId}, setting to null");
                $depositPolicyId = null;
            }
            
            if ($cancellationPolicyId && !DB::table('cancellation_policies')->where('policy_id', $cancellationPolicyId)->exists()) {
                Log::warning("Invalid cancellation_policy_id: {$cancellationPolicyId}, setting to null");
                $cancellationPolicyId = null;
            }
            
            if ($checkOutPolicyId && !DB::table('check_out_policies')->where('policy_id', $checkOutPolicyId)->exists()) {
                Log::warning("Invalid check_out_policy_id: {$checkOutPolicyId}, setting to null");
                $checkOutPolicyId = null;
            }
            
            $roomOptionData = [
                'option_id' => $optionId,
                'room_id' => NULL,
                'name' => $firstRoom['option_name'] ?? ('Gói đặt phòng ' . $bookingCode),
                'price_per_night_vnd' => $this->extractPrice($firstRoom['option_price'] ?? $firstRoom['room_price'] ?? 0),
                'max_guests' => ($firstRoom['adults'] ?? 1) + ($firstRoom['children'] ?? 0),
                'min_guests' => $firstRoom['adults'] ?? 1,
                'urgency_message' => $firstRoom['urgency_message'] ?? null,
                'most_popular' => $firstRoom['most_popular'] ?? 0,
                'recommended' => $firstRoom['recommended'] ?? 0,
                'meal_type' => $mealType,
                'bed_type' => $bedType,
                'recommendation_score' => $firstRoom['recommendation_score'] ?? null,
                'deposit_policy_id' => $depositPolicyId,
                'cancellation_policy_id' => $cancellationPolicyId,
                'check_out_policy_id' => $checkOutPolicyId,
                'package_id' => $packageId,
                'policy_applied_reason' => 'Áp dụng sau khi thanh toán thành công',
                'policy_applied_date' => $checkInDate,
                'policy_snapshot_json' => json_encode($firstRoom['policies'] ?? []),
                'adjusted_price' => $this->extractPrice($firstRoom['option_price'] ?? $firstRoom['room_price'] ?? 0),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            Log::info("[completeBookingAfterPayment] Insert room_option duy nhất cho booking:", [
                'booking_code' => $bookingCode,
                'option_id' => $roomOptionData['option_id'],
                'meal_type' => $roomOptionData['meal_type'],
                'bed_type' => $roomOptionData['bed_type'],
                'package_id' => $roomOptionData['package_id'],
                'deposit_policy_id' => $roomOptionData['deposit_policy_id'],
                'cancellation_policy_id' => $roomOptionData['cancellation_policy_id'],
                'check_out_policy_id' => $roomOptionData['check_out_policy_id'],
            ]);
            $roomOptionInserted = false;
            $finalOptionId = null;
            
            try {
                DB::table('room_option')->insert($roomOptionData);
                $roomOptionInserted = true;
                $finalOptionId = $optionId;
                Log::info("[completeBookingAfterPayment] ĐÃ INSERT room_option thành công:", [
                    'option_id' => $optionId,
                    'package_id' => $roomOptionData['package_id'],
                    'deposit_policy_id' => $roomOptionData['deposit_policy_id'],
                    'cancellation_policy_id' => $roomOptionData['cancellation_policy_id'],
                    'check_out_policy_id' => $roomOptionData['check_out_policy_id'],
                ]);
            } catch (\Exception $e) {
                Log::error("[completeBookingAfterPayment] LỖI khi insert room_option:", [
                    'option_id' => $optionId,
                    'package_id' => $roomOptionData['package_id'],
                    'deposit_policy_id' => $roomOptionData['deposit_policy_id'],
                    'cancellation_policy_id' => $roomOptionData['cancellation_policy_id'],
                    'check_out_policy_id' => $roomOptionData['check_out_policy_id'],
                    'error' => $e->getMessage(),
                    'sql_state' => $e->getCode()
                ]);
                // Nếu insert room_option thất bại, sử dụng NULL cho option_id
                $finalOptionId = null;
            }

            // Cập nhật option_id vào bảng booking SAU KHI đã insert room_option thành công
            if ($roomOptionInserted) {
                try {
                    $booking->option_id = $optionId;
                    $booking->save();
                    Log::info("[completeBookingAfterPayment] ĐÃ UPDATE booking.option_id thành công", [
                        'booking_id' => $booking->booking_id,
                        'option_id' => $optionId
                    ]);
                } catch (\Exception $e) {
                    Log::error("[completeBookingAfterPayment] LỖI khi update booking.option_id:", [
                        'booking_id' => $booking->booking_id,
                        'option_id' => $optionId,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // ===== SMART ROOM ASSIGNMENT =====
            // Gán phòng thông minh dựa trên yêu cầu của khách
            $smartRoomService = new SmartRoomAssignmentService();
            $specialRequests = [];
            
            // Lấy special requests từ booking notes hoặc room data
            if (!empty($booking->notes)) {
                $specialRequests[] = $booking->notes;
            }
            
            // Phân tích từ room data để tìm yêu cầu đặc biệt
            foreach ($rooms as $roomData) {
                if (isset($roomData['special_requests'])) {
                    $specialRequests[] = $roomData['special_requests'];
                }
            }

            // Thực hiện smart assignment
            $assignedRoomIds = $smartRoomService->assignRoomsSmartly(
                $rooms,
                $checkInDate->format('Y-m-d'),
                $checkOutDate->format('Y-m-d'),
                $specialRequests,
                // pass booking total if available so assignment can use it for VIP detection
                isset($booking->total_price_vnd) ? (float)$booking->total_price_vnd : null
            );

            // Flag to indicate whether smart assignment actually assigned any rooms
            $usedSmartAssignment = false;

            Log::info("[completeBookingAfterPayment] Smart room assignment result:", [
                'booking_code' => $bookingCode,
                'assigned_rooms' => $assignedRoomIds,
                'total_requested' => count($rooms),
                'total_assigned' => count($assignedRoomIds)
            ]);

            // Biến để track occupancy updates
            $occupancyUpdates = [];
            $successfulBookingRooms = [];
            
            // Xử lý từng phòng với smart assignment
            foreach ($rooms as $roomIndex => $roomData) {
                Log::info("[completeBookingAfterPayment] Room {$roomIndex} data for booking {$bookingCode}:", $roomData);

                // Sử dụng room_id từ smart assignment nếu có, fallback về room_id gốc
                $assignedRoomId = $assignedRoomIds[$roomIndex] ?? $roomData['room_id'] ?? null;
                
                if (!$assignedRoomId) {
                    Log::warning("[completeBookingAfterPayment] No room assigned for room index {$roomIndex}");
                    continue;
                }

                $room = Room::find($assignedRoomId);
                if (!$room) {
                    Log::error("[completeBookingAfterPayment] Invalid assigned room ID: " . $assignedRoomId);
                    continue; // Skip invalid room instead of throwing exception
                }

                // Xử lý representative cho room này
                $representativeId = $mainRepresentativeId;
                if (isset($roomData['guest_name']) && !empty($roomData['guest_name']) && 
                    $roomData['guest_name'] !== $booking->guest_name) {
                    // Tạo representative riêng cho room này
                    $representativeId = DB::table('representatives')->insertGetId([
                        'booking_id' => $booking->booking_id,
                        'booking_code' => $bookingCode,
                        'room_id' => $assignedRoomId, // Sử dụng assigned room ID
                        'full_name' => $roomData['guest_name'],
                        'phone_number' => $roomData['guest_phone'] ?? $booking->guest_phone,
                        'email' => $roomData['guest_email'] ?? $booking->guest_email,
                        'id_card' => $roomData['guest_id_card'] ?? $customerIdCard,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Tạo booking_room record với assigned room ID
                Log::info("[completeBookingAfterPayment] Creating booking_room with assigned room ID: " . $assignedRoomId);
                // Compute option/total correctly when booking used a package shared across rooms
                $roomsCount = count($rooms);
                $firstRoomOptionTotal = $this->extractPrice($firstRoom['option_price'] ?? $firstRoom['room_price'] ?? 0);
                $isPackageShared = $packageId !== null && collect($rooms)->every(function ($r) use ($packageId) {
                    return isset($r['package_id']) && $r['package_id'] == $packageId;
                });

                $basePricePerNight = $this->extractPrice($roomData['room_price'] ?? 0);

                if ($isPackageShared) {
                    // split the package option total evenly across rooms
                    $perRoomOptionPrice = $roomsCount > 0 ? ($firstRoomOptionTotal / $roomsCount) : 0;
                    $optionPriceForThisRoom = round($perRoomOptionPrice, 2);
                } else {
                    $optionPriceForThisRoom = $this->extractPrice($roomData['option_price'] ?? 0);
                }

                $pricePerNight = $basePricePerNight;
                $totalPriceForThisRoom = ($pricePerNight + $optionPriceForThisRoom) * $nights;

                $bookingRoomData = [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $bookingCode,
                    'room_id' => $assignedRoomId, // Gán room_id ngay khi tạo
                    'assigned_by' => isset($assignedRoomIds[$roomIndex]) ? 'auto' : 'manual',
                    'auto_assigned' => isset($assignedRoomIds[$roomIndex]) ? 1 : 0,
                    'option_id' => $finalOptionId,
                    'option_name' => $roomData['option_name'] ?? 'Custom Package',
                    'option_price' => $optionPriceForThisRoom,
                    'representative_id' => $representativeId,
                    'adults' => $roomData['adults'] ?? 1,
                    'children' => $roomData['children'] ?? 0,
                    'children_age' => null,
                    'price_per_night' => $pricePerNight,
                    'nights' => $nights,
                    'total_price' => $totalPriceForThisRoom,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                try {
                    $bookingRoomId = DB::table('booking_rooms')->insertGetId($bookingRoomData);
                    Log::info("[completeBookingAfterPayment] Successfully created booking_room with smart assignment:", [
                        'booking_room_id' => $bookingRoomId,
                        'assigned_room_id' => $assignedRoomId,
                        'original_room_id' => $roomData['room_id'] ?? 'not_provided',
                        'smart_assigned' => isset($assignedRoomIds[$roomIndex]),
                        'option_id' => $finalOptionId
                    ]);
                    
                    // Track successful booking room for occupancy update
                    $successfulBookingRooms[] = [
                        'booking_room_id' => $bookingRoomId,
                        'room_type_id' => $room->room_type_id,
                        'room_id' => $assignedRoomId,
                        'smart_assigned' => isset($assignedRoomIds[$roomIndex])
                    ];

                    // mark that smart assignment was used for at least one room
                    if (isset($assignedRoomIds[$roomIndex])) {
                        $usedSmartAssignment = true;
                    }

                    // Track occupancy updates by room_type_id
                    if (!isset($occupancyUpdates[$room->room_type_id])) {
                        $occupancyUpdates[$room->room_type_id] = 0;
                    }
                    $occupancyUpdates[$room->room_type_id]++;
                } catch (\Exception $e) {
                    Log::error("[completeBookingAfterPayment] Error creating booking_room with smart assignment:", [
                        'error' => $e->getMessage(),
                        'assigned_room_id' => $assignedRoomId,
                        'booking_room_data' => $bookingRoomData,
                        'sql_state' => $e->getCode()
                    ]);
                    throw $e; // Re-throw để không tiếp tục xử lý room này
                }

                // Tạo booking_room_children records nếu có
                if (isset($roomData['children_age']) && is_array($roomData['children_age']) && !empty($roomData['children_age'])) {
                    foreach ($roomData['children_age'] as $childIndex => $childData) {
                        $age = is_array($childData) ? ($childData['age'] ?? 0) : (int)$childData;
                        
                        BookingRoomChildren::create([
                            'booking_room_id' => $bookingRoomId,
                            'age' => $age,
                            'child_index' => $childIndex,
                        ]);
                    }
                }

                Log::info("Completed room {$roomIndex} for booking {$bookingCode}:", [
                    'booking_room_id' => $bookingRoomId,
                    'room_id' => $roomData['room_id'],
                    'representative_id' => $representativeId
                ]);
            }

            // Cập nhật booking status
            // $booking->update([
            //     'status' => $paymentMethod === 'pay_at_hotel' ? 'confirmed' : 'pending_payment',
            //     'room_id' => NULL
            // ]);

            // ===== CẬP NHẬT OCCUPANCY CHO SAME-DAY BOOKING =====
            // Chỉ cập nhật nếu check-in date là hôm nay hoặc trong tương lai gần
            $checkInCarbon = Carbon::parse($checkInDate);
            $today = Carbon::today();
            
            if ($checkInCarbon->isSameDay($today) || $checkInCarbon->isFuture()) {
                try {
                    $occupancyService = new \App\Services\RoomOccupancyService();
                    
                    foreach ($occupancyUpdates as $roomTypeId => $roomsCount) {
                        $updateSuccess = $occupancyService->updateOccupancyForSameDayBooking(
                            $roomTypeId, 
                            $roomsCount, 
                            $checkInDate
                        );
                        
                        if ($updateSuccess) {
                            Log::info("[completeBookingAfterPayment] Successfully updated occupancy for same-day booking:", [
                                'booking_code' => $bookingCode,
                                'room_type_id' => $roomTypeId,
                                'rooms_count' => $roomsCount,
                                'check_in_date' => $checkInDate
                            ]);
                        } else {
                            Log::warning("[completeBookingAfterPayment] Failed to update occupancy for same-day booking:", [
                                'booking_code' => $bookingCode,
                                'room_type_id' => $roomTypeId,
                                'rooms_count' => $roomsCount,
                                'check_in_date' => $checkInDate
                            ]);
                        }
                    }
                    
                    Log::info("[completeBookingAfterPayment] Occupancy update completed for booking:", [
                        'booking_code' => $bookingCode,
                        'total_room_types_updated' => count($occupancyUpdates),
                        'occupancy_updates' => $occupancyUpdates
                    ]);
                    
                } catch (\Exception $e) {
                    // Log error but don't fail the entire booking process
                    Log::error("[completeBookingAfterPayment] Error updating occupancy for same-day booking:", [
                        'booking_code' => $bookingCode,
                        'error' => $e->getMessage(),
                        'occupancy_updates' => $occupancyUpdates
                    ]);
                }
            } else {
                Log::info("[completeBookingAfterPayment] Skipping occupancy update - not same-day booking:", [
                    'booking_code' => $bookingCode,
                    'check_in_date' => $checkInDate,
                    'today' => $today->toDateString()
                ]);
            }
            // Xóa cache data vì đã xử lý xong
            cache()->forget("booking_rooms_data_{$bookingCode}");

            // Gửi email xác nhận
            $this->sendBookingConfirmationEmail($booking->booking_id);

            Log::info("Successfully completed booking creation with smart room assignment for: {$bookingCode}", [
                'total_rooms_assigned' => count($assignedRoomIds),
                'smart_assignment_used' => $usedSmartAssignment,
                'special_requests_analyzed' => !empty($specialRequests),
                'successful_booking_rooms' => array_map(function($room) {
                    return [
                        'room_id' => $room['room_id'],
                        'smart_assigned' => $room['smart_assigned'] ?? false
                    ];
                }, $successfulBookingRooms)
            ]);

        } catch (\Exception $e) {
            Log::error("Error completing booking after payment for {$bookingCode}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            // Không throw exception để không làm fail toàn bộ payment process
            return;
        }
    }

    /**
     * API endpoint để hoàn thành booking sau khi thanh toán thành công (gọi từ frontend)
     */
    public function completeBookingAfterSuccessfulPayment(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
            ]);

            $bookingCode = $request->booking_code;

            DB::beginTransaction();

            // Gọi method để hoàn thành booking
            $this->completeBookingAfterPayment($bookingCode);

            // Cập nhật payment status
            $booking = Booking::where('booking_code', $bookingCode)->first();
            if ($booking) {
                $payment = Payment::where('booking_id', $booking->booking_id)->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'completed'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking đã được hoàn thành sau thanh toán thành công',
                'booking_code' => $bookingCode
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing booking after payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi hoàn thành booking sau thanh toán'
            ], 500);
        }
    }
}