<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang admin quản lý payment bookings
     */
    public function adminIndex()
    {
        return view('admin.bookings.trading.index');
    }

    /**
     * Lấy danh sách booking chờ thanh toán cho Admin
     */
    public function getPendingPayments()
    {
        try {
            // Lấy toàn bộ booking và payment_method = vietqr
            $pendingBookings = Payment::where('payment_method', 'vietqr')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_code' => $booking->booking_code ?: $booking->formatted_booking_code,
                        'customer_name' => $booking->customer_name,
                        'customer_email' => $booking->customer_email,
                        'customer_phone' => $booking->customer_phone,
                        'total_amount' => (float) $booking->total_amount,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'created_at' => $booking->created_at,
                        'payment_status' => $booking->payment_status,
                        'payment_method' => $booking->payment_method,
                        'special_requests' => $booking->special_requests,
                        'rooms_data' => $booking->rooms_data
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $pendingBookings,
                'count' => $pendingBookings->count(),
                'bank_info' => [
                    'bank_name' => 'MB Bank',
                    'account_number' => '0335920306',
                    'account_name' => 'NGUYEN VAN QUYEN'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting pending payments", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
        

    /**
     * Admin confirm payment manually
     */
    public function adminConfirmPayment($bookingId)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::find($bookingId);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            if ($payment->payment_status === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment already confirmed'
                ], 400);
            }

            // Sử dụng method confirmPayment() từ Model
            $payment->confirmPayment();

            DB::commit();

            Log::info("Payment confirmed by admin", [
                'booking_id' => $bookingId,
                'customer_name' => $payment->customer_name,
                'total_amount' => $payment->total_amount
            ]);

            $bookingCode = $payment->booking_code ?: $payment->formatted_booking_code;

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'data' => [
                    'booking_code' => $bookingCode,
                    'payment_status' => 'confirmed',
                    'confirmed_at' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error("Error confirming payment", [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error confirming payment. Please try again.'
            ], 500);
        }
    }

    /**
     * Tạo booking mới từ frontend
     */
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required|string|unique:bookings,booking_code',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'rooms_data' => 'required|json',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:vietqr,pay_at_hotel',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'special_requests' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'booking_code' => $request->booking_code,
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

            DB::commit();

            Log::info("New booking created", [
                'booking_id' => $payment->id,
                'booking_code' => $payment->booking_code,
                'customer_name' => $payment->customer_name,
                'total_amount' => $payment->total_amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'id' => $payment->id,
                    'booking_code' => $payment->booking_code,
                    'payment_status' => 'pending',
                    'total_amount' => $payment->total_amount,
                    'payment_method' => $payment->payment_method
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error("Error creating booking", [
                'error' => $e->getMessage(),
                'booking_code' => $request->booking_code ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating booking. Please try again.'
            ], 500);
        }
    }

    /**
     * Check payment status - Frontend sẽ gọi để kiểm tra trạng thái
     */
    public function checkPaymentStatus($bookingId)
    {
        try {
            $payment = Payment::find($bookingId);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $payment->id,
                    'booking_code' => $payment->booking_code,
                    'payment_status' => $payment->payment_status,
                    'confirmed_at' => $payment->payment_confirmed_at,
                    'total_amount' => $payment->total_amount,
                    'customer_name' => $payment->customer_name,
                    'check_in' => $payment->check_in,
                    'check_out' => $payment->check_out
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error checking payment status", [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status'
            ], 500);
        }
    }

    /**
     * Lấy lịch sử booking và payment
     */
    public function getBookingHistory(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status'); // pending, confirmed, cancelled
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $query = Booking::with(['roomOption', 'payments']);

            // Filter by status
            if ($status) {
                $query->where('status', $status);
            }

            // Filter by date range
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $bookings = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $formattedBookings = $bookings->getCollection()->map(function ($booking) {
                $latestPayment = $booking->payments()->latest()->first();
                
                return [
                    'id' => $booking->booking_id,
                    'booking_code' => 'BK' . str_pad($booking->booking_id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $booking->guest_name,
                    'customer_email' => $booking->guest_email,
                    'customer_phone' => $booking->guest_phone,
                    'total_amount' => (float) $booking->total_price_vnd,
                    'check_in' => $booking->check_in_date->format('Y-m-d'),
                    'check_out' => $booking->check_out_date->format('Y-m-d'),
                    'created_at' => $booking->created_at,
                    'booking_status' => $booking->status,
                    'payment_status' => $latestPayment ? $latestPayment->payment_status : 'pending',
                    'payment_method' => $latestPayment ? $latestPayment->payment_method : 'N/A',
                    'room_option' => $booking->roomOption ? $booking->roomOption->option_name : 'N/A',
                    'guest_count' => $booking->guest_count,
                    'payment_date' => $latestPayment ? $latestPayment->payment_date : null
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedBookings,
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting booking history", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting booking history'
            ], 500);
        }
    }

    /**
     * Lấy thống kê booking cho admin dashboard
     */
    public function getBookingStats()
    {
        try {
            $today = Carbon::today();
            
            $stats = [
                'total_pending' => Booking::whereHas('payments', function($query) {
                    $query->where('payment_status', 'pending');
                })->count(),
                    
                'total_confirmed_today' => Booking::whereHas('payments', function($query) use ($today) {
                    $query->where('payment_status', 'completed')
                          ->whereDate('payment_date', $today);
                })->count(),
                    
                'total_revenue_today' => Booking::whereHas('payments', function($query) use ($today) {
                    $query->where('payment_status', 'completed')
                          ->whereDate('payment_date', $today);
                })->sum('total_price_vnd'),
                    
                'total_bookings_today' => Booking::whereDate('created_at', $today)->count(),

                'pending_amount' => Booking::whereHas('payments', function($query) {
                    $query->where('payment_status', 'pending');
                })->sum('total_price_vnd')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting booking stats", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting booking statistics'
            ], 500);
        }
    }
}
