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

    // Scope for active weekend days
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get weekend days as array
    public static function getActiveWeekendDays()
    {
        return self::active()->pluck('day_of_week')->toArray();
    }
}
