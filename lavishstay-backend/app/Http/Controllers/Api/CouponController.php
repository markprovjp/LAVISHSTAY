<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Http\Requests\Coupon\ValidateCouponRequest;
use App\Http\Requests\Coupon\ApplyCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Validate mã giảm giá với booking preview
     */
    public function validateCoupon(ValidateCouponRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Lấy user nếu có đăng nhập
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
        } elseif (isset($data['booking_preview']['user_id'])) {
            $user = User::find($data['booking_preview']['user_id']);
        }

        $result = $this->couponService->validateCode(
            $data['code'],
            $data['booking_preview'],
            $user
        );

        if (!$result['valid']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    /**
     * Áp dụng mã giảm giá cho booking
     */
    public function applyToBooking(ApplyCouponRequest $request, int $bookingId): JsonResponse
    {
        $data = $request->validated();
        
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking không tồn tại'
            ], 404);
        }

        // Kiểm tra quyền access booking
        $user = Auth::user();
        if ($user && $booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập booking này'
            ], 403);
        }

        $result = $this->couponService->applyCouponToBooking(
            $booking,
            $data['code'],
            $user
        );

        if (!$result['success']) {
            $statusCode = match($result['reason']) {
                'not_found' => 404,
                'usage_limit', 'user_limit' => 403,
                default => 422
            };
            
            return response()->json($result, $statusCode);
        }

        return response()->json($result);
    }

    /**
     * Lấy lịch sử sử dụng mã giảm giá của user
     */
    public function userRedemptions(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $limit = $request->get('limit', 10);
        $redemptions = $this->couponService->getUserRedemptions($user, $limit);

        return response()->json([
            'success' => true,
            'data' => $redemptions
        ]);
    }

    /**
     * Kiểm tra mã giảm giá có tồn tại và active không (public endpoint)
     */
    public function checkCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50']
        ]);

        $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))
            ->active()
            ->first();

    if (!$coupon) {
            return response()->json([
                'exists' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc không có hiệu lực'
            ], 404);
        }

        return response()->json([
            'exists' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'description' => $coupon->description,
                'min_booking_amount_vnd' => $coupon->min_booking_amount_vnd,
                'remaining_uses' => $coupon->remainingUses(),
            ],
            'message' => 'Mã giảm giá hợp lệ'
        ]);
    }
}
