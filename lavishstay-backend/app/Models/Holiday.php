<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';
    protected $primaryKey = 'holiday_id';

    protected $fillable = [
        'name',
        'date',
        'description',
        'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationship with room pricing
    public function roomPricings()
    {
        return $this->hasMany(RoomPricing::class, 'holiday_id', 'holiday_id');
    }

    // Scope for active holidays
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for current year holidays
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    // Scope for upcoming holidays
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }
}
