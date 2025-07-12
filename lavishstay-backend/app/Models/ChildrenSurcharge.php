<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildrenSurcharge extends Model
{
    protected $fillable = [
        'min_age', 'max_age', 'is_free', 'count_as_adult', 'surcharge_amount_vnd', 'requires_extra_bed'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'count_as_adult' => 'boolean',
        'requires_extra_bed' => 'boolean',
        'surcharge_amount_vnd' => 'integer',
    ];

    /**
     * Get the age range as a formatted string
     */
    public function getAgeRangeAttribute()
    {
        return $this->min_age . ' - ' . $this->max_age . ' tuổi';
    }

    /**
     * Get the policy type description
     */
    public function getPolicyTypeAttribute()
    {
        if ($this->is_free) {
            return 'Miễn phí';
        } elseif ($this->count_as_adult) {
            return 'Tính như người lớn';
        } else {
            return 'Phụ thu cố định';
        }
    }

    /**
     * Get formatted surcharge amount
     */
    public function getFormattedSurchargeAttribute()
    {
        if ($this->is_free) {
            return 'Miễn phí';
        } elseif ($this->count_as_adult) {
            return 'Giá người lớn';
        } else {
            return $this->surcharge_amount_vnd ? number_format($this->surcharge_amount_vnd, 0, ',', '.') . ' VND' : '-';
        }
    }

    /**
     * Check if a given age falls within this policy's range
     */
    public function appliesToAge($age)
    {
        return $age >= $this->min_age && $age <= $this->max_age;
    }

    /**
     * Get applicable policy for a given age
     */
    public static function getApplicablePolicy($age)
    {
        return static::where('min_age', '<=', $age)
                    ->where('max_age', '>=', $age)
                    ->first();
    }

        /**
     * Calculate surcharge for a given age and base price
     */
    public static function calculateSurcharge($age, $basePrice = 0)
    {
        $policy = static::getApplicablePolicy($age);
        
        if (!$policy) {
            return 0; // No policy found, no surcharge
        }

        if ($policy->is_free) {
            return 0;
        } elseif ($policy->count_as_adult) {
            return $basePrice; // Full adult price
        } else {
            return $policy->surcharge_amount_vnd ?? 0;
        }
    }

    /**
     * Get all policies ordered by age range
     */
    public static function getOrderedPolicies()
    {
        return static::orderBy('min_age')->get();
    }

    /**
     * Scope to find overlapping age ranges
     */
    public function scopeOverlapping($query, $minAge, $maxAge, $excludeId = null)
    {
        $query->where(function($q) use ($minAge, $maxAge) {
            $q->whereBetween('min_age', [$minAge, $maxAge])
              ->orWhereBetween('max_age', [$minAge, $maxAge])
              ->orWhere(function($subQ) use ($minAge, $maxAge) {
                  $subQ->where('min_age', '<=', $minAge)
                       ->where('max_age', '>=', $maxAge);
              });
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }
}

