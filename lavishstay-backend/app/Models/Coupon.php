<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'currency',
        'description',
        'start_at',
        'end_at',
        'usage_limit',
        'per_user_limit',
        'min_booking_amount_vnd',
        'applicable_room_type_ids',
        'stackable',
        'combinable_with',
        'active',
        'created_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'applicable_room_type_ids' => 'array',
        'combinable_with' => 'array',
        'active' => 'boolean',
        'stackable' => 'boolean',
        'value' => 'decimal:2',
        'min_booking_amount_vnd' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->active()
                    ->where('start_at', '<=', $now)
                    ->where('end_at', '>=', $now);
    }

    /**
     * Helper Methods
     */
    
    /**
     * Kiểm tra mã có đang hoạt động không
     */
    public function isActive(): bool
    {
        return $this->active && 
               Carbon::now()->between($this->start_at, $this->end_at);
    }

    /**
     * Kiểm tra mã có hợp lệ cho booking không
     */
    public function isValidForBooking(Booking $booking, ?User $user = null): array
    {
        // Kiểm tra mã có active và trong thời gian hiệu lực
        if (!$this->isActive()) {
            return [
                'valid' => false, 
                'reason' => 'expired',
                'message' => 'Mã đã hết hạn hoặc chưa có hiệu lực'
            ];
        }

        // Kiểm tra số tiền tối thiểu
        if ($this->min_booking_amount_vnd && $booking->total_price_vnd < $this->min_booking_amount_vnd) {
            return [
                'valid' => false,
                'reason' => 'min_amount',
                'message' => "Đơn hàng phải có giá trị tối thiểu " . number_format($this->min_booking_amount_vnd) . " VND"
            ];
        }

        // Kiểm tra loại phòng áp dụng
        if ($this->applicable_room_type_ids && !in_array($booking->room_type_id, $this->applicable_room_type_ids)) {
            return [
                'valid' => false,
                'reason' => 'room_type',
                'message' => 'Mã không áp dụng cho loại phòng này'
            ];
        }

        // Kiểm tra giới hạn sử dụng toàn cục
        if ($this->usage_limit && $this->redemptions()->count() >= $this->usage_limit) {
            return [
                'valid' => false,
                'reason' => 'usage_limit',
                'message' => 'Mã đã hết lượt sử dụng'
            ];
        }

        // Kiểm tra giới hạn per user
        if ($user && $this->per_user_limit) {
            $userUsageCount = $this->redemptions()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->per_user_limit) {
                return [
                    'valid' => false,
                    'reason' => 'user_limit',
                    'message' => 'Bạn đã sử dụng hết lượt cho mã này'
                ];
            }
        }

        // Kiểm tra đã áp dụng cho booking này chưa
        $existingRedemption = $this->redemptions()->where('booking_id', $booking->booking_id)->first();
        if ($existingRedemption) {
            return [
                'valid' => false,
                'reason' => 'already_applied',
                'message' => 'Mã đã được áp dụng cho booking này'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Lấy số lượt sử dụng còn lại
     */
    public function remainingUses(): ?int
    {
        if (!$this->usage_limit) {
            return null; // Unlimited
        }
        
        return max(0, $this->usage_limit - $this->redemptions()->count());
    }

    /**
     * Áp dụng mã giảm giá lên số tiền
     */
    public function applyToAmount(float $amount): array
    {
        if ($this->type === 'percent') {
            $discountAmount = ($amount * $this->value) / 100;
        } else {
            $discountAmount = min($this->value, $amount); // Fixed amount, không vượt quá tổng tiền
        }

        $discountAmount = round($discountAmount, 2);
        $newTotal = max(0, $amount - $discountAmount);

        return [
            'original_amount' => $amount,
            'discount_amount' => $discountAmount,
            'new_total' => $newTotal,
            'discount_type' => $this->type,
            'discount_value' => $this->value,
        ];
    }

    /**
     * Mutators
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
}
