<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{


    /**
     * Admin view
     */
    public function adminIndex()
    {
        return view('admin.bookings.trading.index');
    }

    /**
     * Tạo booking mới từ frontend (phiên bản nâng cấp cho nhiều phòng)
     */
   public function createBooking(Request $request)
{
    Log::info('Create Booking Request Received:', $request->all());

    $validator = Validator::make($request->all(), [
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
        'total_guests' => 'required|integer|min:1',
        'total_price' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:vietqr,pay_at_hotel',
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

        // 1. Aggregate requested rooms by room type
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

        // 2. Check availability for each room type
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

        // 3. Create main booking record
        $booking = Booking::create([
            'booking_code' => '', // Will be updated after creation
            'guest_name' => $request->input('customer_name'),
            'guest_email' => $request->input('customer_email'),
            'guest_phone' => $request->input('customer_phone'),
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'guest_count' => $request->input('total_guests'),
            'total_price_vnd' => $request->input('total_price'),
            'status' => 'pending',
            'notes' => $request->input('notes', ''),
            'user_id' => null,
            'room_id' => null,
            'adults' => array_sum(array_column($request->input('rooms'), 'adults')),
            'children' => array_sum(array_column($request->input('rooms'), 'children')),
            'children_age' => json_encode(array_column($request->input('rooms'), 'children_age') ?? []),
        ]);

        // Generate booking code after creation
        $bookingCode = 'LVS' . $booking->booking_id . now()->format('His');
        $booking->booking_code = $bookingCode;
        $booking->save();

        // 4. Create booking_rooms records for each room
        $rooms = $request->input('rooms', []);
        $representativeId = null; // Will store the representative ID after creating representative
        
        foreach ($rooms as $roomIndex => $roomData) {
            $room = Room::find($roomData['room_id']);
            if (!$room) {
                throw new \Exception("Invalid room ID provided: " . $roomData['room_id']);
            }

            // Tạo option_id duy nhất cho booking này
            $optionId = 'BOOK-' . $bookingCode . '-R' . $roomData['room_id'] . '-' . ($roomIndex + 1);

            // Tạo room_option mới cho booking này
            DB::table('room_option')->insert([
                'option_id' => $optionId,
                'room_id' => $roomData['room_id'],
                'name' => $roomData['option_name'] ?? 'Custom Package for Booking ' . $bookingCode,
                'price_per_night_vnd' => $this->extractPrice($roomData['option_price'] ?? $roomData['room_price']),
                'max_guests' => ($roomData['adults'] ?? 1) + ($roomData['children'] ?? 0),
                'min_guests' => $roomData['adults'] ?? 1,
                'cancellation_policy_type' => 'free', // Default policy
                'cancellation_penalty' => 0,
                'cancellation_description' => 'Free cancellation until check-in date',
                'free_until' => Carbon::parse($checkInDate)->subHours(24), // Free cancellation until 24h before check-in
                'payment_policy_type' => $request->input('payment_method') === 'pay_at_hotel' ? 'pay_at_hotel' : 'pay_now',
                'payment_description' => $request->input('payment_method') === 'pay_at_hotel' ? 'Pay at hotel during check-in' : 'Pay online before arrival',
                'urgency_message' => null,
                'most_popular' => 0,
                'recommended' => 0,
                'meal_type' => null,
                'bed_type' => null,
                'recommendation_score' => null,
                'deposit_percentage' => null,
                'deposit_fixed_amount_vnd' => null,
                'deposit_policy_id' => null,
                'cancellation_policy_id' => null,
                'package_id' => null,
                'adjusted_price' => $this->extractPrice($roomData['option_price'] ?? $roomData['room_price']),
            ]);

            // Create booking room record với option_id mới tạo
            $bookingRoomId = DB::table('booking_rooms')->insertGetId([
                'booking_id' => $booking->booking_id,
                'booking_code' => $bookingCode,
                'room_id' => $roomData['room_id'],
                'option_id' => $optionId, // Sử dụng option_id vừa tạo
                'option_name' => $roomData['option_name'] ?? 'Custom Package',
                'option_price' => $this->extractPrice($roomData['option_price'] ?? $roomData['room_price']),
                'representative_id' => null, // Sẽ update sau khi tạo representative
                'adults' => $roomData['adults'] ?? 1,
                'children' => $roomData['children'] ?? 0,
                'children_age' => $this->processChildrenAge($roomData['children_age'] ?? []),
                'price_per_night' => $roomData['room_price'],
                'nights' => $nights,
                'total_price' => $roomData['room_price'] * $nights,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Created room_option and booking_room', [
                'option_id' => $optionId,
                'booking_room_id' => $bookingRoomId,
                'booking_id' => $booking->booking_id,
                'room_id' => $roomData['room_id'],
                'option_name' => $roomData['option_name'] ?? 'Custom Package',
                'adults' => $roomData['adults'] ?? 1,
                'children' => $roomData['children'] ?? 0,
                'price' => $roomData['room_price']
            ]);
        }

        // 5. Create representative record
        if ($request->has('customer_name')) {
            DB::table('representatives')->insert([
                'booking_id' => $booking->booking_id,
                'booking_code' => $bookingCode,
                'room_id' => $request->input('rooms')[0]['room_id'],
                'full_name' => $request->input('customer_name'),
                'phone_number' => $request->input('customer_phone'),
                'email' => $request->input('customer_email'),
                'id_card' => $request->input('customer_id_card', ''),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 6. Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->booking_id,
            'amount_vnd' => $booking->total_price_vnd,
            'payment_type' => $request->input('payment_method') === 'pay_at_hotel' ? 'at_hotel' : 'full',
            'status' => 'pending',
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đặt phòng thành công!',
            'booking_code' => $bookingCode,
            'booking_id' => $booking->booking_id,
        ], 201);
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
    private function createVNPayPayment($booking)
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
    private function generateVietQRUrl($qrData)
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
                    'ro.cancellation_policy_type',
                    'ro.payment_policy_type',
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
     * Admin: Get all bookings with rooms and options for management
     */
    public function getAllBookingsWithOptions(Request $request)
    {
        try {
            $query = DB::table('booking as b')
                ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
                ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('room_option as ro', 'br.option_id', '=', 'ro.option_id')
                ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->leftJoin('representatives as rep', 'b.booking_id', '=', 'rep.booking_id')
                ->select([
                    'b.booking_id',
                    'b.booking_code',
                    'b.guest_name',
                    'b.guest_email',
                    'b.guest_phone',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.guest_count',
                    'b.total_price_vnd',
                    'b.status as booking_status',
                    'b.created_at',
                    'br.room_id',
                    'br.option_id',
                    'br.option_name',
                    'br.option_price',
                    'br.adults',
                    'br.children',
                    'br.price_per_night',
                    'br.nights',
                    'br.total_price as room_total_price',
                    'r.name as room_name',
                    'rt.name as room_type_name',
                    'ro.name as selected_option_name',
                    'ro.price_per_night_vnd as selected_option_price_per_night',
                    'ro.cancellation_policy_type',
                    'ro.payment_policy_type',
                    'p.amount_vnd as payment_amount',
                    'p.status as payment_status',
                    'p.payment_type',
                    'rep.full_name as representative_name',
                    'rep.phone_number as representative_phone'
                ]);

            // Filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('b.status', $request->status);
            }

            if ($request->has('payment_status') && $request->payment_status !== '') {
                $query->where('p.status', $request->payment_status);
            }

            if ($request->has('date_from') && $request->date_from !== '') {
                $query->whereDate('b.created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to !== '') {
                $query->whereDate('b.created_at', '<=', $request->date_to);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('b.booking_code', 'like', "%{$search}%")
                      ->orWhere('b.guest_name', 'like', "%{$search}%")
                      ->orWhere('b.guest_email', 'like', "%{$search}%")
                      ->orWhere('b.guest_phone', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('b.created_at', 'desc')
                            ->paginate($request->get('per_page', 20));

            // Group by booking_id để tránh trùng lặp
            $groupedBookings = $bookings->getCollection()->groupBy('booking_id')->map(function ($rooms, $bookingId) {
                $firstRoom = $rooms->first();
                return [
                    'booking_id' => $bookingId,
                    'booking_code' => $firstRoom->booking_code,
                    'guest_name' => $firstRoom->guest_name,
                    'guest_email' => $firstRoom->guest_email,
                    'guest_phone' => $firstRoom->guest_phone,
                    'check_in_date' => $firstRoom->check_in_date,
                    'check_out_date' => $firstRoom->check_out_date,
                    'guest_count' => $firstRoom->guest_count,
                    'total_price_vnd' => $firstRoom->total_price_vnd,
                    'booking_status' => $firstRoom->booking_status,
                    'payment_amount' => $firstRoom->payment_amount,
                    'payment_status' => $firstRoom->payment_status,
                    'payment_type' => $firstRoom->payment_type,
                    'representative_name' => $firstRoom->representative_name,
                    'representative_phone' => $firstRoom->representative_phone,
                    'created_at' => $firstRoom->created_at,
                    'rooms' => $rooms->map(function ($room) {
                        return [
                            'room_id' => $room->room_id,
                            'room_name' => $room->room_name,
                            'room_type_name' => $room->room_type_name,
                            'option_id' => $room->option_id,
                            'option_name' => $room->option_name ?? $room->selected_option_name,
                            'option_price' => $room->option_price ?? $room->selected_option_price_per_night,
                            'adults' => $room->adults,
                            'children' => $room->children,
                            'price_per_night' => $room->price_per_night,
                            'nights' => $room->nights,
                            'total_price' => $room->room_total_price,
                            'cancellation_policy_type' => $room->cancellation_policy_type,
                            'payment_policy_type' => $room->payment_policy_type,
                        ];
                    })
                ];
            })->values();

            $bookings->setCollection($groupedBookings);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting all bookings with options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting bookings'
            ], 500);
        }
    }

    /**
     * Extract price from various formats (number, object with vnd, etc.)
     */
    private function extractPrice($price)
    {
        if (is_numeric($price)) {
            return $price;
        }
        
        if (is_array($price) && isset($price['vnd'])) {
            return $price['vnd'];
        }
        
        if (is_object($price) && isset($price->vnd)) {
            return $price->vnd;
        }
        
        // Try to get numerical value from string
        if (is_string($price)) {
            $numericPrice = preg_replace('/[^0-9.]/', '', $price);
            return is_numeric($numericPrice) ? (float)$numericPrice : 0;
        }
        
        return 0;
    }

    /**
     * Process children ages from various formats
     */
    private function processChildrenAge($childrenAge)
    {
        if (empty($childrenAge)) {
            return null;
        }
        
        if (is_string($childrenAge)) {
            return $childrenAge; // Already JSON string
        }
        
        if (is_array($childrenAge)) {
            // Extract ages from array of objects or simple array
            $ages = [];
            foreach ($childrenAge as $child) {
                if (is_array($child) && isset($child['age'])) {
                    $ages[] = $child['age'];
                } elseif (is_object($child) && isset($child->age)) {
                    $ages[] = $child->age;
                } elseif (is_numeric($child)) {
                    $ages[] = $child;
                }
            }
            return json_encode($ages);
        }
        
        return json_encode([]);
    }

    /**
     * Check CPay payment status via Google Sheets
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

            Log::info("🔍 Checking CPay payment for booking", [
                'booking_code' => $bookingCode,
                'expected_amount' => $expectedAmount
            ]);

            // Find booking
            $booking = Booking::where('booking_code', $bookingCode)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Multiple CPay URLs for redundancy
            $cpayUrls = [
                'https://script.google.com/macros/s/AKfycbx4F-yvXHfFifvP4JkunVHRiTwgL9cZNg7yE6CgcXZs3hmAjVtr6-1qKIa7ZEk52d00/exec',
                // Backup URL can be added here if needed
            ];
            
            $paymentFound = false;
            $transactionData = null;
            $lastError = null;

            foreach ($cpayUrls as $index => $cpayUrl) {
                try {
                    Log::info("🌐 Trying CPay URL #" . ($index + 1), ['url' => $cpayUrl]);

                    // Try GET with query parameters first (more common for Google Apps Script)
                    $response = Http::timeout(15) // Increase timeout for Google Apps Script
                        ->withoutVerifying() // Skip SSL verification for Google Apps Script
                        ->retry(2, 100) // Retry 2 times with 100ms delay
                        ->withHeaders([
                            'User-Agent' => 'LavishStay-Payment-Checker/1.0',
                            'Accept' => 'application/json, text/plain, */*',
                            'Cache-Control' => 'no-cache'
                        ])
                        ->get($cpayUrl, [
                            'action' => 'checkPayment',
                            'booking_code' => $bookingCode,
                            'amount' => $expectedAmount,
                            'timestamp' => time()
                        ]);

                    Log::info("📨 CPay API Response", [
                        'status' => $response->status(),
                        'headers' => $response->headers(),
                        'body_preview' => substr($response->body(), 0, 200)
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['status']) && $data['status'] === 'success') {
                            // Check if payment transaction exists in the data
                            $transactions = $data['data'] ?? [];
                            
                            foreach ($transactions as $transaction) {
                                // Look for matching booking code in transaction content
                                $content = strtolower($transaction['content'] ?? '');
                                
                                // Multiple search patterns to be flexible
                                $searchPatterns = [
                                    strtolower("trace{$bookingCode}"),
                                    strtolower($bookingCode),
                                    strtolower("dat phong {$bookingCode}"),
                                    strtolower("thanh toan dat phong {$bookingCode}")
                                ];
                                
                                // Also check for amount match (allow small variance)
                                $transactionAmount = floatval($transaction['amount'] ?? 0);
                                $amountMatch = abs($transactionAmount - $expectedAmount) <= 1000; // 1000 VND tolerance
                                
                                $patternMatch = false;
                                foreach ($searchPatterns as $pattern) {
                                    if (strpos($content, $pattern) !== false) {
                                        $patternMatch = true;
                                        break;
                                    }
                                }
                                
                                if ($patternMatch && $amountMatch) {
                                    $paymentFound = true;
                                    $transactionData = $transaction;
                                    break 2; // Break both loops
                                }
                            }
                        }
                        
                        // If no payment found, continue to next URL
                        break;
                    } else {
                        $lastError = "HTTP {$response->status()}: " . $response->body();
                    }
                    
                } catch (\Exception $httpError) {
                    $lastError = $httpError->getMessage();
                    Log::warning("⚠️ CPay URL failed", [
                        'url' => $cpayUrl,
                        'error' => $lastError
                    ]);
                    continue; // Try next URL
                }
            }

            if ($paymentFound && $transactionData) {
                // Payment found, update booking status
                DB::beginTransaction();
                
                try {
                    $booking->update([
                        'status' => 'confirmed',
                        'updated_at' => now()
                    ]);

                    // Update payment status
                    $payment = Payment::where('booking_id', $booking->booking_id)->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'transaction_id' => $transactionData['reference_code'] ?? 'CPAY_' . time(),
                            'updated_at' => now()
                        ]);
                    }

                    DB::commit();

                    Log::info("✅ CPay payment verified successfully", [
                        'booking_code' => $bookingCode,
                        'transaction' => $transactionData
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment verified successfully',
                        'transaction' => $transactionData,
                        'booking_code' => $bookingCode
                    ]);
                } catch (\Exception $dbError) {
                    DB::rollBack();
                    throw $dbError;
                }
            } else {
                Log::info("❌ CPay payment not found", [
                    'booking_code' => $bookingCode,
                    'expected_amount' => $expectedAmount,
                    'last_error' => $lastError
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found or not completed yet',
                    'payment_found' => false,
                    'debug_info' => [
                        'searched_pattern' => "trace{$bookingCode}",
                        'expected_amount' => $expectedAmount,
                        'last_error' => $lastError
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('💥 Error checking CPay payment: ' . $e->getMessage(), [
                'booking_code' => $request->booking_code ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}