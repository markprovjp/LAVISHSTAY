<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekendDay extends Model
{
    use HasFactory;

    protected $table = 'weekend_days';

    protected $fillable = [
        'day_of_week',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get day name in Vietnamese
    public function getDayNameViAttribute()
    {
        $days = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật'
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }
}
