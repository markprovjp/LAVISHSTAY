<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterWelcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = $request->email;

            // Check if already subscribed
            $existingSubscriber = DB::table('newsletter_subscribers')
                ->where('email', $email)
                ->where('active', true)
                ->first();

            if ($existingSubscriber) {
                // Check if they already have a coupon
                $existingCoupon = DB::table('coupons')
                    ->where('description', 'LIKE', "%$email%")
                    ->where('active', 1)
                    ->where('end_at', '>', now())
                    ->first();

                if ($existingCoupon) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Email đã đăng ký. Sử dụng mã giảm giá hiện có.',
                        'discount_code' => $existingCoupon->code
                    ]);
                }
            }

            // Generate unique coupon code
            do {
                $code = sprintf('LAVISH20-%06d', random_int(0, 999999));
                $exists = DB::table('coupons')->where('code', strtoupper($code))->exists();
            } while ($exists);

            $couponCode = strtoupper($code);

            // Create coupon
            $couponId = DB::table('coupons')->insertGetId([
                'code' => $couponCode,
                'type' => 'percent',
                'value' => 20.00,
                'currency' => 'VND',
                'description' => "20% giảm giá từ newsletter - $email",
                'start_at' => now(),
                'end_at' => now()->addDays(90),
                'usage_limit' => 1,
                'per_user_limit' => 1,
                'stackable' => 0,
                'active' => 1,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create or update newsletter subscriber
            $unsubscribeToken = Str::random(64);
            
            DB::table('newsletter_subscribers')->updateOrInsert(
                ['email' => $email],
                [
                    'active' => true,
                    'unsubscribe_token' => $unsubscribeToken,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                    'updated_at' => now(),
                ]
            );

            // Queue welcome email
            $unsubscribeUrl = url("/api/newsletter/unsubscribe?email=" . urlencode($email) . "&token=" . $unsubscribeToken);

            // Build booking URL from env frontend base (default to localhost dev)
            $frontendBase = env('FRONTEND_URL', 'http://localhost:3000');
            $bookingUrl = rtrim($frontendBase, '/') . '/booking?discount=' . $couponCode;

            Mail::to($email)->queue(new NewsletterWelcome($couponCode, $email, $unsubscribeUrl, $bookingUrl));

            Log::info('Newsletter subscription successful', [
                'email' => $email,
                'coupon_code' => $couponCode,
                'coupon_id' => $couponId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Kiểm tra email để nhận mã giảm giá.',
                'discount_code' => $couponCode
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký newsletter'
            ], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        try {
            $email = $request->query('email');
            $token = $request->query('token');

            if (!$email || !$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin không hợp lệ'
                ], 400);
            }

            $subscriber = DB::table('newsletter_subscribers')
                ->where('email', $email)
                ->where('unsubscribe_token', $token)
                ->first();

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link hủy đăng ký không hợp lệ'
                ], 404);
            }

            // Unsubscribe
            DB::table('newsletter_subscribers')
                ->where('email', $email)
                ->where('unsubscribe_token', $token)
                ->update([
                    'active' => false,
                    'unsubscribed_at' => now(),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đăng ký newsletter thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter unsubscribe error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đăng ký'
            ], 500);
        }
    }
}
