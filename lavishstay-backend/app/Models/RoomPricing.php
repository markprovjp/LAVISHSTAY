<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPricing extends Model
{
    use HasFactory;

    protected $table = 'room_pricing';
    protected $primaryKey = 'pricing_id';

    protected $fillable = [
        'room_id',
        'start_date',
        'end_date',
        'price_vnd',
        'reason',
        'option_id',
        'is_weekend',
        'event_id',
        'holiday_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_vnd' => 'decimal:2',
        'is_weekend' => 'boolean'
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id', 'holiday_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        $now = now()->toDateString();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
    }

    public function scopeByType($query, $type)
    {
        switch ($type) {
            case 'event':
                return $query->whereNotNull('event_id');
            case 'holiday':
                return $query->whereNotNull('holiday_id');
            case 'weekend':
                return $query->where('is_weekend', true);
            default:
                return $query;
        }
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }
        return $query;
    }

    // Get pricing type
    public function getPricingTypeAttribute()
    {
        if ($this->event_id) {
            return 'event';
        } elseif ($this->holiday_id) {
            return 'holiday';
        } elseif ($this->is_weekend) {
            return 'weekend';
        }
        return 'other';
    }

    // Get status
    public function getStatusAttribute()
    {
        $now = now()->toDateString();
        if ($now < $this->start_date) {
            return 'upcoming';
        } elseif ($now >= $this->start_date && $now <= $this->end_date) {
            return 'active';
        } else {
            return 'expired';
        }
    }
}
