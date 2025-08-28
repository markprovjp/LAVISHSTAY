<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class BookingActionPaymentController extends Controller
{
    public function createActionFeeQR(Request $request)
    {
    // Temporary request log to help diagnose 422 validation failures from the frontend
    Log::info('createActionFeeQR incoming request', $request->all());

        // Explicit validation so we can log failures for debugging
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',
            'action_type' => 'required|in:cancel,extend,reschedule',
            'amount' => 'required|numeric|min:1000',
            'booking_code' => 'required|string',
            'action_params' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            Log::warning('createActionFeeQR validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::find($request->booking_id);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đặt phòng'
                ], 404);
            }

            $actionLabels = [
                'cancel' => 'hủy đặt phòng',
                'extend' => 'gia hạn đặt phòng',
                'reschedule' => 'dời lịch đặt phòng'
            ];

            $actionLabel = $actionLabels[$request->action_type] ?? 'thao tác';
            
            // Tạo payment record mới với payment_type là 'additional' cho phí booking action
            $payment = new Payment();
            $payment->booking_id = $request->booking_id;
            $payment->amount_vnd = $request->amount;
            $payment->payment_type = 'additional'; // Sử dụng 'additional' để phân biệt với thanh toán booking chính
            $payment->status = 'pending';
            
            // Tạo transaction_id unique để theo dõi
            $paymentId = strtoupper($request->action_type) . '_FEE_' . $request->booking_id . '_' . time();
            $payment->transaction_id = $paymentId;
            
            // Thêm thông tin action vào collector_notes dưới dạng JSON
            $actionInfo = [
                'action_type' => $request->action_type,
                'action_params' => $request->action_params,
                'created_for' => 'booking_action_fee',
                'expires_at' => now()->addMinutes(15)->toISOString(),
            ];
            $payment->collector_notes = json_encode($actionInfo);
            
            $payment->save();
            
            // Get VietQR settings - use same defaults as PaymentSettingsController
            $bankId = PaymentSetting::get('vietqr.bank_id', 'MBBank');
            $accountNo = PaymentSetting::get('vietqr.account_no', '0335920306');
            $accountName = PaymentSetting::get('vietqr.account_name', 'NGUYEN VAN QUYEN');
            $template = PaymentSetting::get('vietqr.template', 'print');

            // Generate payment content
            $content = "LVSA {$request->booking_code} {$payment->payment_id}";
            $encodedContent = urlencode($content);
            $encodedAccountName = urlencode($accountName);

            // Generate QR URL using VietQR API
            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?amount={$request->amount}&addInfo={$encodedContent}&accountName={$encodedAccountName}";

            return response()->json([
                'success' => true,
                'data' => [
                    'qr_url' => $qrUrl,
                    'payment_id' => $paymentId,
                    'formatted_amount' => number_format($request->amount, 0, ',', '.') . ' ₫',
                    'payment_content' => $content,
                    'expires_at' => now()->addMinutes(15)->toISOString(),
                    'bank_info' => [
                        'account_name' => $accountName,
                        'account_no' => $accountNo,
                        'bank_id' => $bankId,
                    ],
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating action fee QR', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tạo mã QR'
            ], 500);
        }
    }

    public function checkActionFeePayment(Request $request)
    {
    Log::info('checkActionFeePayment incoming request', $request->all());

    $request->validate([
            'payment_id' => 'required|string',
            'booking_code' => 'required|string',
            'action_type' => 'required|in:cancel,extend,reschedule',
            'booking_id' => 'required|integer',
        ]);

        try {
            // Tìm payment record theo transaction_id và booking_id
            $payment = Payment::where('transaction_id', $request->payment_id)
                ->where('booking_id', $request->booking_id)
                ->where('payment_type', 'additional')
                ->first();

            if (!$payment) {
                $resp = [
                    'payment_found' => false,
                    'message' => 'Không tìm thấy thông tin thanh toán'
                ];
                Log::info('checkActionFeePayment response', $resp);
                return response()->json($resp);
            }

            // Kiểm tra xem payment đã completed chưa
            if ($payment->status === 'completed') {
                $resp = [
                    'payment_found' => true,
                    'payment' => [
                        'transaction_id' => $payment->transaction_id,
                        'amount' => $payment->amount_vnd,
                        'paid_at' => $payment->collected_at,
                    ]
                ];
                Log::info('checkActionFeePayment response (already completed)', $resp);
                return response()->json($resp);
            }

            // Parse collector_notes để lấy thông tin action
            $actionInfo = json_decode($payment->collector_notes, true);
            if (!$actionInfo || $actionInfo['action_type'] !== $request->action_type) {
                $resp = [
                    'payment_found' => false,
                    'message' => 'Thông tin thanh toán không khớp'
                ];
                Log::info('checkActionFeePayment response (action type mismatch)', $resp);
                return response()->json($resp);
            }

            // Kiểm tra hết hạn (parse expires_at safely)
            if (isset($actionInfo['expires_at'])) {
                try {
                    $expiresAt = Carbon::parse($actionInfo['expires_at']);
                    if (Carbon::now()->greaterThan($expiresAt)) {
                        $payment->status = 'failed';
                        $payment->save();

                        $resp = [
                            'payment_found' => false,
                            'message' => 'Thanh toán đã hết hạn'
                        ];
                        Log::info('checkActionFeePayment response (expired)', $resp);
                        return response()->json($resp);
                    }
                } catch (\Exception $e) {
                    Log::warning('Unable to parse expires_at in payment collector_notes', ['expires_at' => $actionInfo['expires_at'], 'error' => $e->getMessage()]);
                }
            }

            // Call CPay API to check for payment (reuse same shape as service payment check)
            Log::info('Starting CPay check for action payment', ['booking_code' => $request->booking_code, 'booking_id' => $request->booking_id, 'expected_amount' => $payment->amount_vnd, 'transaction_id' => $payment->transaction_id]);
            $cPayStart = microtime(true);
            $cPayTransaction = $this->checkCPayAPI($request->booking_code, $payment->amount_vnd);
            $cPayDuration = round((microtime(true) - $cPayStart) * 1000);
            Log::info('Finished CPay check', ['duration_ms' => $cPayDuration, 'found' => $cPayTransaction ? true : false]);

            if ($cPayTransaction) {
                // Payment found, persist collected info and mark completed
                DB::transaction(function () use ($payment, $cPayTransaction) {
                    // Update status and collected_at; keep original transaction_id (the local one)
                    $payment->status = 'completed';
                    $payment->collected_at = now();

                    // Merge collector_notes to include cpay data and collected info
                    $notes = json_decode($payment->collector_notes, true) ?? [];
                    $notes['cpay'] = $cPayTransaction['cpay_data'] ?? $cPayTransaction;
                    $notes['collected_transaction_id'] = $cPayTransaction['transaction_id'] ?? null;
                    $notes['collected_at'] = now()->toIsoString();
                    $payment->collector_notes = json_encode($notes);

                    $payment->save();
                });

                $resp = [
                    'success' => true,
                    'payment_found' => true,
                    'message' => 'Thanh toán thành công',
                    'booking_id' => $payment->booking_id,
                    'payment' => [
                        'transaction_id' => $payment->transaction_id,
                        'amount' => $payment->amount_vnd,
                        'status' => $payment->status,
                        'collected_at' => $payment->collected_at,
                        'cpay' => $cPayTransaction['cpay_data'] ?? $cPayTransaction
                    ]
                ];
                Log::info('checkActionFeePayment response (cpay found)', $resp);
                return response()->json($resp);
            }

            $resp = [
                'success' => true,
                'payment_found' => false,
                'message' => 'Chưa tìm thấy giao dịch thanh toán'
            ];
            Log::info('checkActionFeePayment response (not found after cpay check)', $resp);
            return response()->json($resp);

        } catch (\Exception $e) {
            Log::error('Error checking action fee payment', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'payment_found' => false,
                'message' => 'Đã xảy ra lỗi khi kiểm tra thanh toán'
            ]);
        }
    }

    /**
     * Lấy lịch sử thanh toán phí action cho một booking
     */
    public function getActionPaymentHistory($bookingId)
    {
        try {
            $payments = Payment::where('booking_id', $bookingId)
                ->where('payment_type', 'additional')
                ->whereNotNull('collector_notes')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($payment) {
                    $actionInfo = json_decode($payment->collector_notes, true);
                    if ($actionInfo && isset($actionInfo['created_for']) && $actionInfo['created_for'] === 'booking_action_fee') {
                        return [
                            'payment_id' => $payment->payment_id,
                            'transaction_id' => $payment->transaction_id,
                            'action_type' => $actionInfo['action_type'] ?? 'unknown',
                            'amount' => $payment->amount_vnd,
                            'status' => $payment->status,
                            'created_at' => $payment->created_at,
                            'paid_at' => $payment->collected_at,
                        ];
                    }
                    return null;
                })
                ->filter();

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting action payment history', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy lịch sử thanh toán'
            ], 500);
        }
    }

    /**
     * Call CPay API (reuse from BookingServicePaymentController)
     */
    private function checkCPayAPI($bookingCode, $expectedAmount)
    {
        try {
            Log::info("Calling CPay API for action payment", [
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ]);

            $cPayApiUrl = env('CPAY_GOOGLE_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbx8VhqXhSp0oY1uPrcM9nEr3iZZE2b8u8nXq7dKYX3UXQ0PmDe5Yh4sYNfU-QNGRgDN/exec');

            $requestData = [
                'action' => 'checkPayment',
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ];

            Log::info('CPay API Request', $requestData);

            $httpClient = Http::timeout(30)->retry(2, 500);
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
                Log::info("CPay API: SSL verification disabled for local development");
            }

            $response = $httpClient->asForm()->post($cPayApiUrl, $requestData);
            Log::info('CPay API Response Status: ' . $response->status());

            if (!$response->successful()) {
                Log::error('CPay API HTTP Error: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $responseData = $response->json();
            Log::info('CPay API Response', $responseData ?? ['raw_body' => $response->body()]);

            if (isset($responseData['status']) && $responseData['status'] === 'success' && !empty($responseData['data'])) {
                $transaction = $responseData['data'][0];
                Log::info('✅ CPay action payment found', $transaction);

                return [
                    'transaction_id' => 'CPAY_ACTION_' . $bookingCode . '_' . time(),
                    'amount' => $transaction['amount'] ?? $expectedAmount,
                    'payment_date' => $transaction['date'] ?? now()->toDateString(),
                    'payment_time' => $transaction['time'] ?? now()->toTimeString(),
                    'bank_account' => $transaction['account'] ?? ($transaction['bank_account'] ?? ''),
                    'description' => $transaction['content'] ?? ($transaction['description'] ?? ''),
                    'cpay_data' => $transaction
                ];
            }

            Log::info("❌ No CPay action payment found for booking: {$bookingCode}, amount: {$expectedAmount}");
            return null;

        } catch (\Exception $e) {
            Log::error('CPay API Error for action payment: ' . $e->getMessage(), [
                'booking_code' => $bookingCode,
                'amount' => $expectedAmount
            ]);
            return null;
        }
    }
}
