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
        'booking_code',
        'room_id',
        'option_id',
        'option_name',
        'option_price',
        'representative_id',
        'adults',
        'children',
        'children_age',
        'price_per_night',
        'nights',
        'total_price',
        'check_in_date',
        'check_out_date',
    ];

    // Quan hệ với Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_code', 'booking_code');
    }

    // Quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Quan hệ với Representative
    public function representative()
    {
        return $this->belongsTo(Representative::class, 'representative_id', 'id');
    }

    // Quan hệ với BookingRoomChildren
    public function children()
    {
        return $this->hasMany(BookingRoomChildren::class, 'booking_room_id', 'id')->orderBy('child_index');
    }
}