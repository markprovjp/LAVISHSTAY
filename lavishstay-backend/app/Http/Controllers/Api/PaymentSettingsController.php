<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentSettingsController extends Controller
{
    /**
     * Get all payment settings for frontend
     */
    public function index(): JsonResponse
    {
        try {
            $settings = [
                'vietqr' => $this->getVietQRSettings(),
                'cpay' => $this->getCPaySettings(),
                'vnpay' => $this->getVNPaySettings(),
                'pay_at_hotel' => $this->getPayAtHotelSettings(),
                'general' => $this->getGeneralSettings()
            ];

            Log::info('Payment settings API called', [
                'settings_count' => count($settings),
                'vietqr_enabled' => $settings['vietqr']['enabled'] ?? false,
                'user_agent' => request()->header('User-Agent')
            ]);

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Lấy cấu hình thanh toán thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get payment settings via API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy cấu hình thanh toán',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get specific payment method settings
     */
    public function getByMethod(string $method): JsonResponse
    {
        try {
            $validMethods = ['vietqr', 'cpay', 'vnpay', 'pay_at_hotel', 'general'];
            
            if (!in_array($method, $validMethods)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phương thức thanh toán không hợp lệ'
                ], 400);
            }

            $methodName = 'get' . ucfirst(str_replace('_', '', $method)) . 'Settings';
            if ($method === 'pay_at_hotel') {
                $methodName = 'getPayAtHotelSettings';
            }

            $settings = $this->$methodName();
            
            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => "Lấy cấu hình {$method} thành công"
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to get {$method} settings via API", [
                'method' => $method,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Không thể lấy cấu hình {$method}"
            ], 500);
        }
    }

    /**
     * Test VietQR connection (public endpoint for testing)
     */
    public function testVietQR(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'bank_id' => 'required|string|max:20',
                'account_no' => 'required|string|max:50',
                'account_name' => 'required|string|max:255'
            ]);

            $bankId = $request->input('bank_id');
            $accountNo = $request->input('account_no');
            $accountName = $request->input('account_name');

            // Test generate QR URL
            $testAmount = 10000;
            $testContent = 'Test connection - ' . now()->format('H:i:s');
            $encodedContent = urlencode($testContent);
            $encodedAccountName = urlencode($accountName);
            
            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-print.png?amount={$testAmount}&addInfo={$encodedContent}&accountName={$encodedAccountName}";

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
                Log::info('VietQR test successful via API', [
                    'bank_id' => $bankId,
                    'account_no' => $accountNo,
                    'test_url' => $qrUrl,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kết nối VietQR thành công! QR code có thể được tạo.',
                    'test_url' => $qrUrl,
                    'test_data' => [
                        'amount' => $testAmount,
                        'content' => $testContent
                    ]
                ]);
            } else {
                throw new \Exception("HTTP {$httpCode}: Không thể tạo QR code. Vui lòng kiểm tra thông tin tài khoản.");
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('VietQR test failed via API', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Test VietQR thất bại: ' . $e->getMessage()
            ], 500);
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
}