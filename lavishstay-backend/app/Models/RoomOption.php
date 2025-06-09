<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOption extends Model
{
    protected $table = 'room_option';
    protected $primaryKey = 'option_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'option_id',
        'room_id',
        'name',
        'price_per_night_vnd',
        'max_guests',
        'min_guests',
        'cancellation_policy_type',
        'payment_policy_type',
        'most_popular',
        'recommended'
    ];

    protected $casts = [
        'price_per_night_vnd' => 'decimal:2',
        'most_popular' => 'boolean',
        'recommended' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function availabilities()
    {
        return $this->hasMany(RoomAvailability::class, 'option_id', 'option_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'option_id', 'option_id');
    }
}
