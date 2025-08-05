<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutPolicy extends Model
{
    protected $table = 'check_out_policies';
    protected $primaryKey = 'policy_id';
    
    protected $fillable = [
        'name',
        'description',
        'standard_check_out_time',
        'late_check_out_fee_vnd',
        'conditions',
        'action',
        'priority',
        'is_active',
    ];
    
    protected $casts = [
        'standard_check_out_time' => 'datetime:H:i:s',
        'late_check_out_fee_vnd' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'priority' => 0,
        'is_active' => true,
    ];

    /**
     * Get the check-out requests that use this policy
     */
    public function checkoutRequests()
    {
        return $this->hasMany(CheckOutRequest::class, 'policy_id', 'policy_id');
    }

    /**
     * Scope to get only active policies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by priority (highest first)
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Get formatted standard check-out time
     */
    public function getFormattedStandardTimeAttribute()
    {
        return $this->standard_check_out_time ? 
            \Carbon\Carbon::parse($this->standard_check_out_time)->format('H:i') : 
            null;
    }

    /**
     * Get formatted late check-out fee
     */
    public function getFormattedLateFeeAttribute()
    {
        return $this->late_check_out_fee_vnd ? 
            number_format($this->late_check_out_fee_vnd, 0, ',', '.') . ' VND' : 
            null;
    }

    /**
     * Check if policy is applicable for late check-out
     */
    public function isApplicableForLateCheckout($checkoutTime)
    {
        if (!$this->standard_check_out_time || !$this->is_active) {
            return false;
        }

        $standardTime = \Carbon\Carbon::parse($this->standard_check_out_time);
        $requestedTime = \Carbon\Carbon::parse($checkoutTime);

        return $requestedTime->gt($standardTime);
    }

    /**
     * Calculate late check-out fee for given time
     */
    public function calculateLateCheckoutFee($checkoutTime)
    {
        if (!$this->isApplicableForLateCheckout($checkoutTime)) {
            return 0;
        }

        return $this->late_check_out_fee_vnd ?? 0;
    }

    /**
     * Get priority level description
     */
    public function getPriorityLevelAttribute()
    {
        if ($this->priority >= 8) {
            return 'Rất cao';
        } elseif ($this->priority >= 5) {
            return 'Cao';
        } elseif ($this->priority >= 3) {
            return 'Trung bình';
        } elseif ($this->priority >= 1) {
            return 'Thấp';
        } else {
            return 'Rất thấp';
        }
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        if ($this->priority >= 8) {
            return 'red';
        } elseif ($this->priority >= 5) {
            return 'orange';
        } elseif ($this->priority >= 3) {
            return 'yellow';
        } elseif ($this->priority >= 1) {
            return 'blue';
        } else {
            return 'gray';
        }
    }
}