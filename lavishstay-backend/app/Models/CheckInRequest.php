<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckInRequest extends Model
{
    protected $table = 'check_in_requests';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'booking_id',
        'policy_id',
        'requested_time',
        'actual_time',
        'status',
        'fee_amount_vnd',
        'payment_id',
        'processed_by',
        'notes',
        'special_requests',
    ];
    
    protected $casts = [
        'requested_time' => 'datetime:H:i:s',
        'actual_time' => 'datetime:H:i:s',
        'fee_amount_vnd' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that this check-in request belongs to
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Get the check-in policy applied to this request
     */
    public function policy()
    {
        return $this->belongsTo(CheckinPolicy::class, 'policy_id', 'policy_id');
    }

    /**
     * Get the payment associated with this check-in request
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    /**
     * Get the user who processed this check-in request
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope to get only approved check-in requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only pending check-in requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get check-in requests for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Get formatted requested time
     */
    public function getFormattedRequestedTimeAttribute()
    {
        return $this->requested_time ? 
            \Carbon\Carbon::parse($this->requested_time)->format('H:i') : 
            null;
    }

    /**
     * Get formatted actual time
     */
    public function getFormattedActualTimeAttribute()
    {
        return $this->actual_time ? 
            \Carbon\Carbon::parse($this->actual_time)->format('H:i') : 
            null;
    }

    /**
     * Get formatted fee amount
     */
    public function getFormattedFeeAmountAttribute()
    {
        return $this->fee_amount_vnd ? 
            number_format($this->fee_amount_vnd, 0, ',', '.') . ' VND' : 
            '0 VND';
    }

    /**
     * Check if this is an early check-in request
     */
    public function isEarlyCheckin()
    {
        if (!$this->policy || !$this->policy->standard_check_in_time || !$this->actual_time) {
            return false;
        }

        $standardTime = \Carbon\Carbon::parse($this->policy->standard_check_in_time);
        $actualTime = \Carbon\Carbon::parse($this->actual_time);

        return $actualTime->lt($standardTime);
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'approved':
                return 'green';
            case 'pending':
                return 'yellow';
            case 'rejected':
                return 'red';
            default:
                return 'gray';
        }
    }

    /**
     * Get status label in Vietnamese
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'approved':
                return 'Đã duyệt';
            case 'pending':
                return 'Chờ duyệt';
            case 'rejected':
                return 'Từ chối';
            default:
                return 'Không xác định';
        }
    }
}