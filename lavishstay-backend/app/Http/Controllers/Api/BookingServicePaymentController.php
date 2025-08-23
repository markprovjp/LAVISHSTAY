<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class BookingServicePaymentController extends Controller
{
    /**
     * Get service payment information for a booking
     */
    public function getServicePaymentInfo($bookingId)
    {
        try {
            $booking = Booking::with(['bookingServices.service'])->findOrFail($bookingId);
            
            $services = $booking->bookingServices->map(function ($bookingService) {
                return [
                    'booking_service_id' => $bookingService->id,
                    'service_id' => $bookingService->service_id,
                    'service_name' => $bookingService->service->name ?? 'Unknown Service',
                    'service_description' => $bookingService->service->description ?? '',
                    'quantity' => $bookingService->quantity,
                    'unit_price_vnd' => $bookingService->price_vnd,
                    'total_price_vnd' => $bookingService->total_price,
                    'paid_amount_vnd' => $bookingService->paid_amount_vnd,
                    'outstanding_amount_vnd' => $bookingService->outstanding_amount,
                    'payment_status' => $bookingService->payment_status,
                    'payment_percentage' => $bookingService->payment_percentage,
                    'formatted_total_price' => number_format($bookingService->total_price, 0, ',', '.') . ' ₫',
                    'formatted_paid_amount' => number_format($bookingService->paid_amount_vnd, 0, ',', '.') . ' ₫',
                    'formatted_outstanding' => number_format($bookingService->outstanding_amount, 0, ',', '.') . ' ₫',
                ];
            });

            $totalServiceAmount = $services->sum('total_price_vnd');
            $totalPaidAmount = $services->sum('paid_amount_vnd');
            $totalOutstanding = $totalServiceAmount - $totalPaidAmount;

            return response()->json([
                'success' => true,
                'data' => [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'services' => $services,
                    'summary' => [
                        'total_service_amount' => $totalServiceAmount,
                        'total_paid_amount' => $totalPaidAmount,
                        'total_outstanding' => $totalOutstanding,
                        'payment_percentage' => $totalServiceAmount > 0 ? round(($totalPaidAmount / $totalServiceAmount) * 100, 2) : 100,
                        'services_count' => $services->count(),
                        'pending_services' => $services->where('payment_status', 'pending')->count(),
                        'partial_services' => $services->where('payment_status', 'partial')->count(),
                        'paid_services' => $services->where('payment_status', 'paid')->count(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting service payment info', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin thanh toán dịch vụ'
            ], 500);
        }
    }

    /**
     * Generate VietQR for service payment
     */
    public function generateServicePaymentQR(Request $request, $bookingId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1000',
                'service_ids' => 'array',
                'service_ids.*' => 'integer|exists:booking_services,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $booking = Booking::findOrFail($bookingId);
            $amount = $request->amount;
            $serviceIds = $request->service_ids ?? [];

            // Validate that services belong to this booking
            if (!empty($serviceIds)) {
                $validServices = BookingService::where('booking_id', $bookingId)
                    ->whereIn('id', $serviceIds)
                    ->count();
                
                if ($validServices !== count($serviceIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Một số dịch vụ không thuộc về đơn đặt phòng này'
                    ], 422);
                }
            }

            // Get VietQR settings
            $bankId = PaymentSetting::get('vietqr.bank_id', '970422');
            $accountNo = PaymentSetting::get('vietqr.account_no', '0123456789');
            $accountName = PaymentSetting::get('vietqr.account_name', 'LAVISH STAY HOTEL');
            $template = PaymentSetting::get('vietqr.template', 'print');

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $bookingId,
                'amount_vnd' => $amount,
                'payment_type' => 'additional',
                'status' => 'pending',
                'transaction_id' => 'SERVICE_' . $booking->booking_code . '_' . time()
            ]);

            // Generate payment content
            $content = "LVSS {$booking->booking_code} {$payment->payment_id}";
            $encodedContent = urlencode($content);
            $encodedAccountName = urlencode($accountName);

            // Generate QR URL
            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$encodedContent}&accountName={$encodedAccountName}";

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $payment->payment_id,
                    'qr_url' => $qrUrl,
                    'amount' => $amount,
                    'formatted_amount' => number_format($amount, 0, ',', '.') . ' ₫',
                    'payment_content' => $content,
                    'transaction_id' => $payment->transaction_id,
                    'bank_info' => [
                        'bank_id' => $bankId,
                        'account_no' => $accountNo,
                        'account_name' => $accountName
                    ],
                    'selected_services' => $serviceIds,
                    'expires_at' => now()->addMinutes(15)->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating service payment QR', [
                'booking_id' => $bookingId,
                'amount' => $request->amount ?? 0,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo mã QR thanh toán'
            ], 500);
        }
    }

    /**
     * Check service payment using CPay API
     */
    public function checkServicePayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_id' => 'required|integer|exists:payment,payment_id',
                'booking_code' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $paymentId = $request->payment_id;
            $bookingCode = $request->booking_code;
            $expectedAmount = $request->amount;

            Log::info("Checking service payment", [
                'payment_id' => $paymentId,
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ]);

            // Get payment record
            $payment = Payment::with('booking')->findOrFail($paymentId);
            
            if ($payment->status === 'completed') {
                // Compute if booking is now ready for checkout using full rule engine
                try {
                    $booking = $payment->booking;
                    $engine = new \App\Services\Checkout\CheckoutRuleEngine();
                    $validation = $engine->validate($booking);
                    $canCheckout = $validation->canCheckout;
                } catch (\Exception $e) {
                    // Fallback: check service-level outstanding
                    $remainingOutstanding = BookingService::where('booking_id', $payment->booking_id)
                        ->where('payment_status', '!=', 'paid')
                        ->sum(DB::raw('(quantity * price_vnd) - paid_amount_vnd'));

                    $canCheckout = $remainingOutstanding <= 0;
                }

                return response()->json([
                    'success' => true,
                    'payment_found' => true,
                    'message' => 'Thanh toán đã được xác nhận trước đó',
                    'booking_id' => $payment->booking_id,
                    'can_checkout' => $canCheckout,
                    'payment' => [
                        'payment_id' => $payment->payment_id,
                        'amount' => $payment->amount_vnd,
                        'status' => $payment->status,
                        'transaction_id' => $payment->transaction_id,
                        'confirmed_at' => $payment->updated_at
                    ]
                ]);
            }

            // Call CPay API to check for payment
            $cPayTransaction = $this->checkCPayAPI($bookingCode, $expectedAmount);

            if ($cPayTransaction) {
                // Payment found, process it
                DB::transaction(function () use ($payment, $cPayTransaction, $request) {
                    // Update payment status
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $cPayTransaction['transaction_id']
                    ]);

                    // Allocate payment to services
                    $serviceIds = $request->service_ids ?? [];
                    $this->allocatePaymentToServices(
                        $payment->booking_id,
                        $payment->amount_vnd,
                        $serviceIds,
                        $payment->payment_id
                    );
                });

                // After allocation, compute full booking checkout readiness
                try {
                    $booking = Booking::find($payment->booking_id);
                    $engine = new \App\Services\Checkout\CheckoutRuleEngine();
                    $validation = $engine->validate($booking);
                    $canCheckout = $validation->canCheckout;
                } catch (\Exception $e) {
                    $remainingOutstanding = BookingService::where('booking_id', $payment->booking_id)
                        ->where('payment_status', '!=', 'paid')
                        ->sum(DB::raw('(quantity * price_vnd) - paid_amount_vnd'));

                    $canCheckout = $remainingOutstanding <= 0;
                }

                return response()->json([
                    'success' => true,
                    'payment_found' => true,
                    'message' => 'Thanh toán thành công',
                    'booking_id' => $payment->booking_id,
                    'can_checkout' => $canCheckout,
                    'payment' => [
                        'payment_id' => $payment->payment_id,
                        'amount' => $payment->amount_vnd,
                        'status' => 'completed',
                        'transaction_id' => $cPayTransaction['transaction_id'],
                        'confirmed_at' => now()
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'payment_found' => false,
                'message' => 'Chưa tìm thấy giao dịch thanh toán'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking service payment', [
                'error' => $e->getMessage(),
                'payment_id' => $request->payment_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi kiểm tra thanh toán'
            ], 500);
        }
    }

    /**
     * Allocate payment amount to booking services
     */
    private function allocatePaymentToServices($bookingId, $paymentAmount, $serviceIds = [], $paymentId = null)
    {
        // Get services to allocate payment to
        $query = BookingService::where('booking_id', $bookingId)
            ->where('payment_status', '!=', 'paid');

        if (!empty($serviceIds)) {
            $query->whereIn('id', $serviceIds);
        }

        $services = $query->orderBy('id')->get();
        $remainingAmount = $paymentAmount;

        foreach ($services as $service) {
            if ($remainingAmount <= 0) break;

            $outstanding = $service->outstanding_amount;
            $allocateAmount = min($remainingAmount, $outstanding);

            if ($allocateAmount > 0) {
                $newPaidAmount = $service->paid_amount_vnd + $allocateAmount;
                $totalPrice = $service->total_price;

                // Determine new payment status
                $newStatus = 'pending';
                if ($newPaidAmount >= $totalPrice) {
                    $newStatus = 'paid';
                } elseif ($newPaidAmount > 0) {
                    $newStatus = 'partial';
                }

                // Update service
                $service->update([
                    'paid_amount_vnd' => $newPaidAmount,
                    'payment_status' => $newStatus,
                    'last_payment_id' => $paymentId
                ]);

                $remainingAmount -= $allocateAmount;

                Log::info("Allocated payment to service", [
                    'booking_service_id' => $service->id,
                    'service_name' => $service->service->name ?? 'Unknown',
                    'allocated_amount' => $allocateAmount,
                    'new_paid_amount' => $newPaidAmount,
                    'new_status' => $newStatus,
                    'remaining_payment' => $remainingAmount
                ]);
            }
        }

        // Log if there's overpayment
        if ($remainingAmount > 0) {
            Log::warning("Overpayment detected", [
                'booking_id' => $bookingId,
                'overpayment_amount' => $remainingAmount,
                'original_payment' => $paymentAmount
            ]);
        }

        return $paymentAmount - $remainingAmount; // Return allocated amount
    }

    /**
     * Call CPay API (reuse from PaymentController)
     */
    private function checkCPayAPI($bookingCode, $expectedAmount)
    {
        try {
            Log::info("Calling CPay API for service payment", [
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ]);

            $cPayApiUrl = env('CPAY_GOOGLE_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbx8VhqXhSp0oY1uPrcM9nEr3iZZE2b8u8nXq7dKYX3UXQ0PmDe5Yh4sYNfU-QNGRgDN/exec');
            
            // Use same payload shape as main PaymentController to match the Google Apps Script
            $requestData = [
                'action' => 'checkPayment',
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ];

            Log::info("CPay API Request", $requestData);

            $httpClient = Http::timeout(30)->retry(2, 500);
            
            // Disable SSL verification for local development
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
                Log::info("CPay API: SSL verification disabled for local development");
            }

            $response = $httpClient->asForm()->post($cPayApiUrl, $requestData);
            
            Log::info("CPay API Response Status: " . $response->status());
            
            if (!$response->successful()) {
                Log::error("CPay API HTTP Error: " . $response->status() . " - " . $response->body());
                return null;
            }

            $responseData = $response->json();
            Log::info("CPay API Response", $responseData ?? ['raw_body' => $response->body()]);

            // Align with PaymentController response shape: { status: 'success', data: [...] }
            if (isset($responseData['status']) && $responseData['status'] === 'success' && !empty($responseData['data'])) {
                $transaction = $responseData['data'][0];
                Log::info("✅ CPay service payment found", $transaction);

                return [
                    'transaction_id' => 'CPAY_SERVICE_' . $bookingCode . '_' . time(),
                    'amount' => $transaction['amount'] ?? $expectedAmount,
                    'payment_date' => $transaction['date'] ?? now()->toDateString(),
                    'payment_time' => $transaction['time'] ?? now()->toTimeString(),
                    'bank_account' => $transaction['account'] ?? ($transaction['bank_account'] ?? ''),
                    'description' => $transaction['content'] ?? ($transaction['description'] ?? ''),
                    'cpay_data' => $transaction
                ];
            }

            Log::info("❌ No CPay service payment found for booking: {$bookingCode}, amount: {$expectedAmount}");
            return null;

        } catch (\Exception $e) {
            Log::error('CPay API Error for service payment: ' . $e->getMessage(), [
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ]);
            return null;
        }
    }

    /**
     * Get service payment history for a booking
     */
    public function getServicePaymentHistory($bookingId)
    {
        try {
            $payments = Payment::where('booking_id', $bookingId)
                ->where('payment_type', 'additional')
                ->with('booking')
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedPayments = $payments->map(function ($payment) {
                return [
                    'payment_id' => $payment->payment_id,
                    'amount_vnd' => $payment->amount_vnd,
                    'formatted_amount' => number_format($payment->amount_vnd, 0, ',', '.') . ' ₫',
                    'payment_type' => $payment->payment_type,
                    'status' => $payment->status,
                    'transaction_id' => $payment->transaction_id,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at,
                    'formatted_date' => $payment->created_at->format('d/m/Y H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $formattedPayments,
                    'total_payments' => $payments->count(),
                    'total_amount' => $payments->where('status', 'completed')->sum('amount_vnd'),
                    'pending_amount' => $payments->where('status', 'pending')->sum('amount_vnd')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting service payment history', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tải lịch sử thanh toán'
            ], 500);
        }
    }
}
