<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompensationPolicy extends Model
{
    protected $table = 'compensation_policies';
    protected $primaryKey = 'compensation_policy_id';

    protected $fillable = [
        'name',
        'description',
        'applies_to_room_type_id',
        'condition_type',
        'discount_type',
        'discount_value',
        'max_compensation_amount',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_compensation_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the room type this policy applies to.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'applies_to_room_type_id', 'room_type_id');
    }

    /**
     * Get compensation requests using this policy.
     */
    public function compensationRequests(): HasMany
    {
        return $this->hasMany(CompensationRequest::class, 'policy_id', 'compensation_policy_id');
    }

    /**
     * Scope for active policies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific condition type
     */
    public function scopeForCondition($query, $conditionType)
    {
        return $query->where('condition_type', $conditionType);
    }

    /**
     * Scope for specific room type
     */
    public function scopeForRoomType($query, $roomTypeId)
    {
        return $query->where(function($q) use ($roomTypeId) {
            $q->where('applies_to_room_type_id', $roomTypeId)
              ->orWhereNull('applies_to_room_type_id');
        });
    }

    /**
     * Calculate compensation amount based on policy
     */
    public function calculateCompensation(float $totalAmount): float
    {
        if ($this->discount_type === 'percentage') {
            $compensation = $totalAmount * ($this->discount_value / 100);
        } else {
            $compensation = $this->discount_value;
        }

        // Apply max compensation limit if set
        if ($this->max_compensation_amount && $compensation > $this->max_compensation_amount) {
            $compensation = $this->max_compensation_amount;
        }

        return $compensation;
    }

    /**
     * Get formatted discount value
     */
    public function getFormattedDiscountValueAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        } else {
            return number_format($this->discount_value, 0, ',', '.') . ' ₫';
        }
    }

    /**
     * Get condition type label
     */
    public function getConditionTypeLabelAttribute(): string
    {
        return match($this->condition_type) {
            'room_damage' => 'Hư hỏng phòng',
            'service_failure' => 'Lỗi dịch vụ',
            'overbooking' => 'Overbooking',
            'other' => 'Khác',
            default => 'Không xác định'
        };
    }

    /**
     * Get discount type label
     */
    public function getDiscountTypeLabelAttribute(): string
    {
        return match($this->discount_type) {
            'percentage' => 'Phần trăm',
            'fixed_amount' => 'Số tiền cố định',
            default => 'Không xác định'
        };
    }
}