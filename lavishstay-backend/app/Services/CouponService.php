<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Booking;
use App\Models\User;
use App\Events\CouponApplied;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CouponService
{
    /**
     * Validate mã giảm giá với thông tin booking
     */
    public function validateCode(string $code, $bookingData, ?User $user = null): array
    {
        try {
            // Tìm mã giảm giá
            $coupon = Coupon::where('code', strtoupper($code))->first();
            
            if (!$coupon) {
                return [
                    'valid' => false,
                    'reason' => 'not_found',
                    'message' => 'Mã giảm giá không tồn tại'
                ];
            }

            // Chuyển booking data thành object để validate
            if (is_array($bookingData)) {
                $mockBooking = $this->createMockBooking($bookingData);
            } else {
                $mockBooking = $bookingData;
            }

            // Validate mã với booking
            $validation = $coupon->isValidForBooking($mockBooking, $user);
            
            if (!$validation['valid']) {
                return $validation;
            }

            // Tính toán giảm giá
            $baseAmount = $bookingData['base_price_vnd'] ?? $mockBooking->total_price_vnd;
            $discountCalculation = $coupon->applyToAmount($baseAmount);
            
            $taxes = $bookingData['taxes_vnd'] ?? 0;
            $fees = $bookingData['fees_vnd'] ?? 0;
            $newTotal = $discountCalculation['new_total'] + $taxes + $fees;

            return [
                'valid' => true,
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'description' => $coupon->description
                ],
                'discount_vnd' => $discountCalculation['discount_amount'],
                'new_total_vnd' => $newTotal,
                'breakdown' => [
                    'base' => $baseAmount,
                    'discount' => $discountCalculation['discount_amount'],
                    'taxes' => $taxes,
                    'fees' => $fees,
                    'total' => $newTotal
                ]
            ];

        } catch (Exception $e) {
            Log::error('CouponService validateCode error: ' . $e->getMessage());
            return [
                'valid' => false,
                'reason' => 'system_error',
                'message' => 'Lỗi hệ thống khi xác thực mã giảm giá'
            ];
        }
    }

    /**
     * Áp dụng mã giảm giá cho booking
     */
    public function applyCouponToBooking(Booking $booking, string $code, ?User $user = null): array
    {
        return DB::transaction(function () use ($booking, $code, $user) {
            try {
                // Lock coupon để tránh race condition
                $coupon = Coupon::where('code', strtoupper($code))
                    ->lockForUpdate()
                    ->first();

                if (!$coupon) {
                    return [
                        'success' => false,
                        'reason' => 'not_found',
                        'message' => 'Mã giảm giá không tồn tại'
                    ];
                }

                // Kiểm tra xem đã áp dụng chưa (idempotency)
                $existingRedemption = CouponRedemption::where('coupon_id', $coupon->id)
                    ->where('booking_id', $booking->booking_id)
                    ->first();

                if ($existingRedemption) {
                    return [
                        'success' => true,
                        'existing' => true,
                        'redemption' => $existingRedemption,
                        'message' => 'Mã đã được áp dụng trước đó'
                    ];
                }

                // Validate mã
                $validation = $coupon->isValidForBooking($booking, $user);
                if (!$validation['valid']) {
                    return [
                        'success' => false,
                        'reason' => $validation['reason'],
                        'message' => $validation['message']
                    ];
                }

                // Tính toán giảm giá
                $discountCalculation = $coupon->applyToAmount($booking->total_price_vnd);
                $newTotal = $discountCalculation['new_total'];

                // Log user_id we will insert to aid debugging when it's null
                Log::info('CouponService creating redemption with user fallback', [
                    'user_id_candidate' => $user?->id ?? $booking->user_id,
                    'provided_user_id' => $user?->id,
                    'booking_user_id' => $booking->user_id,
                    'booking_id' => $booking->booking_id,
                ]);

                // Tạo bản ghi redemption
                $redemption = CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    // prefer explicit $user if provided, otherwise fall back to booking->user_id
                    'user_id' => $user?->id ?? $booking->user_id,
                    'booking_id' => $booking->booking_id,
                    'amount_saved_vnd' => $discountCalculation['discount_amount'],
                    'applied_amount_vnd' => $booking->total_price_vnd,
                    'meta' => [
                        'original_total' => $booking->total_price_vnd,
                        'discount_calculation' => $discountCalculation,
                        'applied_at' => now()->toISOString(),
                        'coupon_snapshot' => [
                            'code' => $coupon->code,
                            'type' => $coupon->type,
                            'value' => $coupon->value,
                        ]
                    ]
                ]);

                // Cập nhật booking total
                $booking->update([
                    'total_price_vnd' => $newTotal
                ]);

                // Emit event
                event(new CouponApplied($coupon, $booking, $user, $redemption));

                return [
                    'success' => true,
                    'redemption' => $redemption,
                    'booking' => $booking->fresh(),
                    'discount_amount' => $discountCalculation['discount_amount'],
                    'new_total' => $newTotal,
                    'message' => 'Áp dụng mã giảm giá thành công'
                ];

            } catch (Exception $e) {
                Log::error('CouponService applyCouponToBooking error: ' . $e->getMessage());
                throw $e; // Re-throw để transaction rollback
            }
        });
    }

    /**
     * Lấy danh sách mã đã sử dụng bởi user
     */
    public function getUserRedemptions(User $user, int $limit = 10): array
    {
        $redemptions = CouponRedemption::with(['coupon', 'booking'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $redemptions->map(function ($redemption) {
            return [
                'id' => $redemption->id,
                'coupon_code' => $redemption->coupon->code,
                'amount_saved' => $redemption->amount_saved_vnd,
                'booking_code' => $redemption->booking->booking_code,
                'applied_at' => $redemption->created_at,
            ];
        })->toArray();
    }

    /**
     * Tạo mock Booking model từ array data (không lưu vào DB)
     * Trả về instance của App\Models\Booking để phù hợp với typehint
     */
    private function createMockBooking(array $data): Booking
    {
        $attributes = [
            'booking_id' => $data['booking_id'] ?? null,
            'total_price_vnd' => $data['base_price_vnd'] ?? $data['total_price_vnd'] ?? 0,
            'room_type_id' => $data['room_type_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
        ];

        // Create a Booking model instance without persisting it
        $mock = new Booking($attributes);

        // Ensure attributes are set on the model
        foreach ($attributes as $key => $value) {
            $mock->{$key} = $value;
        }

        return $mock;
    }

    /**
     * Kiểm tra tình trạng sử dụng của coupon
     */
    public function getCouponUsageStats(Coupon $coupon): array
    {
        $totalRedemptions = $coupon->redemptions()->count();
        $totalSaved = $coupon->redemptions()->sum('amount_saved_vnd');
        
        return [
            'total_redemptions' => $totalRedemptions,
            'remaining_uses' => $coupon->remainingUses(),
            'total_amount_saved' => $totalSaved,
            'usage_percentage' => $coupon->usage_limit ? 
                round(($totalRedemptions / $coupon->usage_limit) * 100, 2) : null
        ];
    }
}
