<?php
// File: app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Tạo booking mới từ frontend
     */
    public function createBooking(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => 'required|string|unique:bookings,booking_code',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'rooms_data' => 'required|json',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:vietqr,pay_at_hotel',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        try {
            $booking = DB::table('bookings')->insert([
                'booking_code' => $validated['booking_code'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'rooms_data' => $validated['rooms_data'], // JSON string
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending', // pending, confirmed, failed
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking_code' => $validated['booking_code'],
                'payment_status' => 'pending'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status - Frontend sẽ gọi mỗi 30s
     */
    public function checkPaymentStatus($bookingCode)
    {
        try {
            $booking = DB::table('bookings')
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
                'booking_code' => $bookingCode,
                'payment_status' => $booking->payment_status,
                'confirmed_at' => $booking->payment_confirmed_at,
                'total_amount' => $booking->total_amount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin confirm payment manually
     */
    public function adminConfirmPayment($bookingCode)
    {
        try {
            $updated = DB::table('bookings')
                ->where('booking_code', $bookingCode)
                ->where('payment_status', 'pending')
                ->update([
                    'payment_status' => 'confirmed',
                    'payment_confirmed_at' => now(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed successfully',
                    'booking_code' => $bookingCode
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or already confirmed'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error confirming payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách booking chờ thanh toán cho Admin
     */
    public function getPendingPayments()
    {
        try {
            $pendingBookings = DB::table('bookings')
                ->where('payment_status', 'pending')
                ->where('payment_method', 'vietqr') // Chỉ lấy VietQR
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pendingBookings,
                'count' => $pendingBookings->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting pending payments: ' . $e->getMessage()
            ], 500);
        }
    }
}
