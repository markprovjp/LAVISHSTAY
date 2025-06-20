<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // VNPay Configuration
    private function getVNPayConfig()
    {
        return [
            'vnp_Url' => config('vnpay.vnp_url'),
            'vnp_Returnurl' => config('vnpay.vnp_return_url'),
            'vnp_TmnCode' => config('vnpay.vnp_tmn_code'),
            'vnp_HashSecret' => config('vnpay.vnp_hash_secret')
        ];
    }

    /**
     * Admin view
     */
    public function adminIndex()
    {
        return view('admin.bookings.trading.index');
    }

    /**
     * Tạo booking mới từ frontend
     */
    public function createBooking(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'rooms_data' => 'required|array',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:vietqr,vnpay,pay_at_hotel,pending',
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'special_requests' => 'nullable|string'
            ]);

            // Generate booking code
            $bookingCode = 'LAVISH' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            // Ensure unique booking code
            while (Payment::where('booking_code', $bookingCode)->exists()) {
                $bookingCode = 'LAVISH' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
            }

            // Create booking record
            $booking = Payment::create([
                'booking_code' => $bookingCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'rooms_data' => $request->rooms_data,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'special_requests' => $request->special_requests
            ]);

            $response = [
                'success' => true,
                'booking_code' => $bookingCode,
                'booking_id' => $booking->id,
                'message' => 'Booking created successfully'
            ];

            // Nếu là VNPay, tạo payment URL
            if ($request->payment_method === 'vnpay') {
                Log::info('Creating VNPay payment for booking: ' . $bookingCode);
                try {
                    $vnpayUrl = $this->createVNPayPayment($booking);
                    $response['vnpay_url'] = $vnpayUrl;
                    Log::info('VNPay URL created successfully: ' . $vnpayUrl);
                } catch (\Exception $e) {
                    Log::error('Error creating VNPay URL: ' . $e->getMessage());
                    $response['error'] = 'Failed to create VNPay URL: ' . $e->getMessage();
                }
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    private function createVNPayPayment($booking)
    {
        $vnpayConfig = $this->getVNPayConfig();
        
        // Thông tin đơn hàng
        $vnp_TxnRef = $booking->booking_code;
        $vnp_OrderInfo = "Thanh toan dat phong LavishStay " . $booking->booking_code; // Không có space/+, dùng underscore
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $booking->total_amount * 100; // VNPay expects amount in VND cents
        $vnp_Locale = "vn";
        $vnp_BankCode = ""; // Để trống để cho khách hàng chọn
        $vnp_IpAddr = request()->ip();

        // Tạo input data theo chuẩn VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnpayConfig['vnp_TmnCode'],
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnpayConfig['vnp_Returnurl'],
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Thêm mã ngân hàng nếu có
        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp mảng theo key
        ksort($inputData);
        
        $query = "";
        $hashdata = "";
        
        // Tạo chuỗi hash (KHÔNG URL encode) và query string (CÓ URL encode)
        $i = 0;
        foreach ($inputData as $key => $value) {
            // Cho hash data: không URL encode
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            
            // Cho query string: có URL encode
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL thanh toán
        $vnp_Url = $vnpayConfig['vnp_Url'] . "?" . $query;
        
        // Tạo secure hash từ hashdata (không URL encode)
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpayConfig['vnp_HashSecret']);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Log để debug
        Log::info('VNPay Payment URL created', [
            'booking_code' => $booking->booking_code,
            'amount' => $vnp_Amount,
            'hashdata' => $hashdata,
            'vnpSecureHash' => $vnpSecureHash,
            'vnp_Url' => $vnp_Url
        ]);

        return $vnp_Url;
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function handleVNPayReturn(Request $request)
    {
        $vnpayConfig = $this->getVNPayConfig();
        $vnp_HashSecret = $vnpayConfig['vnp_HashSecret'];
        
        // Lấy secure hash từ request
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        
        // Loại bỏ secure hash khỏi data để tính hash
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        // Tạo hash data theo chuẩn VNPay (KHÔNG URL encode)
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . $key . "=" . $value;
            } else {
                $hashData .= $key . "=" . $value;
                $i = 1;
            }
        }

        // Tính secure hash để so sánh
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        Log::info('VNPay Return Validation', [
            'received_hash' => $vnp_SecureHash,
            'calculated_hash' => $secureHash,
            'hash_data' => $hashData,
            'input_data' => $inputData
        ]);
        
        $frontendUrl = config('vnpay.frontend_payment_url');
        
        // Kiểm tra tính hợp lệ của hash
        if ($secureHash === $vnp_SecureHash) {
            $booking = Payment::where('booking_code', $request->vnp_TxnRef)->first();
            
            if ($booking) {
                if ($request->vnp_ResponseCode == '00') {
                    // Thanh toán thành công - Auto approve booking
                    $booking->update([
                        'payment_status' => 'confirmed',
                        'payment_confirmed_at' => now(),
                        'booking_status' => 'confirmed' // Tự động duyệt đơn đặt phòng
                    ]);
                    
                    Log::info('VNPay: Payment successful and booking auto-approved', [
                        'booking_code' => $booking->booking_code,
                        'amount' => $request->vnp_Amount,
                        'transaction_ref' => $request->vnp_TxnRef
                    ]);
                    
                    // Redirect về frontend với trạng thái thành công
                    $frontendUrl .= '?status=success&booking_code=' . $booking->booking_code . '&message=' . urlencode('Thanh toán thành công và đơn đặt phòng đã được xác nhận');
                    
                    // Nếu là API request, trả về JSON
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Thanh toán thành công và đơn đặt phòng đã được xác nhận',
                            'booking_code' => $booking->booking_code,
                            'payment_status' => 'confirmed',
                            'booking_status' => 'confirmed'
                        ]);
                    }
                    
                    return redirect($frontendUrl);
                } else {
                    // Thanh toán thất bại
                    $booking->update([
                        'payment_status' => 'failed',
                        'booking_status' => 'cancelled'
                    ]);
                    
                    Log::warning('VNPay: Payment failed', [
                        'booking_code' => $booking->booking_code,
                        'response_code' => $request->vnp_ResponseCode,
                        'transaction_ref' => $request->vnp_TxnRef
                    ]);
                    
                    // Redirect về frontend với trạng thái lỗi
                    $frontendUrl .= '?status=failed&booking_code=' . $booking->booking_code . '&message=' . urlencode('Thanh toán thất bại');
                    
                    // Nếu là API request, trả về JSON
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Thanh toán thất bại',
                            'booking_code' => $booking->booking_code,
                            'payment_status' => 'failed'
                        ]);
                    }
                    
                    return redirect($frontendUrl);
                }
            } else {
                Log::error('VNPay Return: Booking not found', [
                    'transaction_ref' => $request->vnp_TxnRef
                ]);
                
                $frontendUrl .= '?status=error&message=' . urlencode('Không tìm thấy đơn đặt phòng');
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy đơn đặt phòng'
                    ], 404);
                }
                
                return redirect($frontendUrl);
            }
        } else {
            Log::error('VNPay Return: Invalid signature', [
                'received_hash' => $vnp_SecureHash,
                'calculated_hash' => $secureHash
            ]);
            
            $frontendUrl .= '?status=error&message=' . urlencode('Chữ ký không hợp lệ');
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chữ ký không hợp lệ'
                ], 400);
            }
            
            return redirect($frontendUrl);
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay
     */
    public function handleVNPayIPN(Request $request)
    {
        $vnpayConfig = $this->getVNPayConfig();
        $vnp_HashSecret = $vnpayConfig['vnp_HashSecret'];
        $inputData = array();
        
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . $key . "=" . $value;
            } else {
                $hashData .= $key . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        $returnData = array();
        
        if ($secureHash == $vnp_SecureHash) {
            $booking = Payment::where('booking_code', $request->vnp_TxnRef)->first();
            
            if ($booking) {
                if ($request->vnp_ResponseCode == '00') {
                    // Payment successful
                    if ($booking->payment_status == 'pending') {
                        $booking->update([
                            'payment_status' => 'confirmed',
                            'payment_confirmed_at' => now(),
                            'booking_status' => 'confirmed' // Auto-approve booking
                        ]);
                        
                        Log::info('VNPay IPN: Payment confirmed for booking ' . $booking->booking_code);
                    }
                    
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                } else {
                    // Payment failed - Cancel booking
                    $booking->update([
                        'payment_status' => 'failed',
                        'booking_status' => 'cancelled'
                    ]);
                    
                    Log::warning('VNPay IPN: Payment failed for booking ' . $booking->booking_code . ' with response code: ' . $request->vnp_ResponseCode);
                    
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                }
            } else {
                Log::error('VNPay IPN: Booking not found for transaction: ' . $request->vnp_TxnRef);
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Order not found';
            }
        } else {
            Log::error('VNPay IPN: Invalid signature');
            $returnData['RspCode'] = '97';
            $returnData['Message'] = 'Invalid signature';
        }
        
        return response()->json($returnData);
    }

    /**
     * Hiển thị form test payment (chỉ để test)
     */
    public function showTestPaymentForm()
    {
        if (app()->environment('production')) {
            abort(404);
        }
        
        return view('payment.test-form');
    }

    /**
     * Xử lý test payment
     */
    public function processTestPayment(Request $request)
    {
        if (app()->environment('production')) {
            abort(404);
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'order_info' => 'required|string|max:255'
        ]);
        
        // Tạo test booking
        $testBooking = Payment::create([
            'booking_code' => 'TEST' . time(),
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '0123456789',
            'rooms_data' => [['room_id' => 1, 'quantity' => 1]],
            'total_amount' => $request->amount,
            'payment_method' => 'vnpay',
            'payment_status' => 'pending',
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+2 days')),
            'special_requests' => $request->order_info
        ]);
        
        $vnpayUrl = $this->createVNPayPayment($testBooking);
        
        return redirect($vnpayUrl);
    }

    /**
     * Admin: Lấy danh sách booking chờ thanh toán
     */
    public function getPendingPayments(Request $request)
    {
        try {
            $query = Payment::query();

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
            $booking = Payment::findOrFail($bookingId);

            if ($booking->payment_status === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment already confirmed'
                ]);
            }

            $booking->update([
                'payment_status' => 'confirmed',
                'payment_confirmed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
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
     */
    public function getBookingDetails($bookingCode)
    {
        try {
            $booking = Payment::where('booking_code', $bookingCode)->first();

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
                    'rooms_data' => $booking->rooms_data,
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
     * Test VNPay configuration (chỉ dùng để debug)
     */
    public function testVNPayConfig(Request $request)
    {
        if (!app()->environment(['local', 'testing'])) {
            return response()->json(['error' => 'Not available in production'], 403);
        }

        $vnpayConfig = $this->getVNPayConfig();
        
        return response()->json([
            'config' => [
                'vnp_Url' => $vnpayConfig['vnp_Url'],
                'vnp_Returnurl' => $vnpayConfig['vnp_Returnurl'],
                'vnp_TmnCode' => $vnpayConfig['vnp_TmnCode'],
                'vnp_HashSecret' => substr($vnpayConfig['vnp_HashSecret'], 0, 10) . '...' // Hide full secret
            ],
            'test_booking' => [
                'booking_code' => 'TEST123',
                'total_amount' => 100000
            ]
        ]);
    }

    // Legacy VNPay payment method (keep for backward compatibility)
    public function vnpayPayment(Request $request)
    {
        $data = $request->all();
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
        $vnp_TmnCode = "7C2XX1GB";
        $vnp_HashSecret = "Z5JYIVEOHJMYD7BUWFTQ4S3OQZZDVHN5";
        
        $vnp_TxnRef = $data['order_id'] ?? time();
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "LAVISHSTAY";
        $vnp_Amount = ($data['total'] ?? 0) * 100;
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
       
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
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
}
