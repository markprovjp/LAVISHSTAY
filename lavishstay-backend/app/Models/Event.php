<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationship with room pricing
    public function roomPricings()
    {
        return $this->hasMany(RoomPricing::class, 'event_id', 'event_id');
    }

    // Scope for active events
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for current events
    public function scopeCurrent($query)
    {
        $now = now()->toDateString();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    // Scope for upcoming events
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now()->toDateString());
    }
}
