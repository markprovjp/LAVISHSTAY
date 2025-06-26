<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlexiblePricingRule extends Model
{
    use HasFactory;

    protected $table = 'flexible_pricing_rules';
    protected $primaryKey = 'rule_id';

    protected $fillable = [
        'room_type_id',
        'rule_type',
        'days_of_week',
        'event_id',
        'holiday_id',
        'season_name',
        'start_date',
        'end_date',
        'price_adjustment',
        'is_active'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    /**
     * Relationship with RoomType
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
    public function event()
        {
            return $this->belongsTo(Event::class, 'event_id', 'event_id');
        }

        public function holiday()
        {
            return $this->belongsTo(Holiday::class, 'holiday_id', 'holiday_id');
        }
    /**
     * Scope for weekend rules
     */
    public function scopeWeekend($query)
    {
        return $query->where('rule_type', 'weekend');
    }

    /**
     * Scope for active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for rules applicable to a specific date
     */
    public function scopeApplicableToDate($query, $date)
    {
        return $query->where(function($q) use ($date) {
            $q->where(function($subQ) use ($date) {
                // Rules with date restrictions
                $subQ->whereNotNull('start_date')
                     ->whereNotNull('end_date')
                     ->where('start_date', '<=', $date)
                     ->where('end_date', '>=', $date);
            })->orWhere(function($subQ) {
                                // Rules without date restrictions
                $subQ->whereNull('start_date')
                     ->whereNull('end_date');
            })->orWhere(function($subQ) use ($date) {
                // Rules with only start date
                $subQ->whereNotNull('start_date')
                     ->whereNull('end_date')
                     ->where('start_date', '<=', $date);
            })->orWhere(function($subQ) use ($date) {
                // Rules with only end date
                $subQ->whereNull('start_date')
                     ->whereNotNull('end_date')
                     ->where('end_date', '>=', $date);
            });
        });
    }

    /**
     * Check if rule applies to a specific day of week
     */
    public function appliesToDayOfWeek($dayOfWeek)
    {
        $days = $this->days_of_week ?? [];
        return in_array($dayOfWeek, $days);
    }

    /**
     * Get formatted days of week
     */
    public function getFormattedDaysOfWeekAttribute()
    {
        $dayNames = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật'
        ];

        $days = $this->days_of_week ?? [];
        return array_map(function($day) use ($dayNames) {
            return $dayNames[$day] ?? $day;
        }, $days);
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Tạm dừng';
        }

        $now = now()->toDateString();
        
        if ($this->start_date && $this->end_date) {
            if ($now < $this->start_date) {
                return 'Sắp áp dụng';
            } elseif ($now >= $this->start_date && $now <= $this->end_date) {
                return 'Đang áp dụng';
            } else {
                return 'Đã hết hạn';
            }
        }

        return 'Đang áp dụng';
    }

    /**
     * Calculate adjusted price
     */
    public function calculateAdjustedPrice($basePrice)
    {
        $adjustment = $this->price_adjustment / 100;
        return $basePrice * (1 + $adjustment);
    }
}

