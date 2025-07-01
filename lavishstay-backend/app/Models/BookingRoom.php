<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = 'booking_rooms';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'room_id',
        'price_per_night',
        'nights',
        'total_price',
        'check_in_date',
        'check_out_date',
    ];

    // Quan hệ với Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    // Quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
}