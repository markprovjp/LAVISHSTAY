<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CheckoutRequest extends Model
{
    protected $table = 'check_out_requests';
    protected $primaryKey = 'request_id';
    
    protected $fillable = [
        'booking_id',
        'type',
        'requested_check_out_time',
        'fee_vnd',
        'status',
        'admin_note',
        'processed_at',
        'processed_by'
    ];
    
    protected $casts = [
        'requested_check_out_time' => 'datetime',
        'processed_at' => 'datetime',
        'fee_vnd' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Constants for type
    const TYPE_EARLY = 'early';
    const TYPE_LATE = 'late';

    /**
     * Get the booking that owns the checkout request
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Get the admin who processed this request
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for late checkout requests
     */
    public function scopeLate($query)
    {
        return $query->where('type', self::TYPE_LATE);
    }

    /**
     * Scope for early checkout requests
     */
    public function scopeEarly($query)
    {
        return $query->where('type', self::TYPE_EARLY);
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if this is a late checkout request
     */
    public function isLateCheckout(): bool
    {
        return $this->type === self::TYPE_LATE;
    }

    /**
     * Check if this is an early checkout request
     */
    public function isEarlyCheckout(): bool
    {
        return $this->type === self::TYPE_EARLY;
    }

    /**
     * Get formatted fee with currency
     */
    public function getFormattedFeeAttribute(): string
    {
        return number_format($this->fee_vnd, 0, ',', '.') . ' đ';
    }

    /**
     * Get status in Vietnamese
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_REJECTED => 'Từ chối',
            default => 'Không xác định'
        };
    }

    /**
     * Get type in Vietnamese
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            self::TYPE_LATE => 'Trả muộn',
            self::TYPE_EARLY => 'Trả sớm',
            default => 'Không xác định'
        };
    }

    /**
     * Get the difference in hours from standard checkout
     */
    public function getDifferenceInHours(): float
    {
        if (!$this->booking) {
            return 0;
        }

        $standardCheckout = Carbon::parse($this->booking->check_out_date)->setTime(12, 0, 0);
        $requestedTime = $this->requested_check_out_time;
        
        return round($standardCheckout->diffInMinutes($requestedTime, false) / 60, 2);
    }

    /**
     * Check if request can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->isPending() && 
               $this->requested_check_out_time->isFuture();
    }

    /**
     * Check if request needs admin approval
     */
    public function needsAdminApproval(): bool
    {
        return $this->isPending() && $this->isLateCheckout();
    }
}