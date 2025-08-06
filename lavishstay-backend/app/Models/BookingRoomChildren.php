<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRoomChildren extends Model
{
    use HasFactory;

    protected $table = 'booking_room_children';

    protected $fillable = [
        'booking_room_id',
        'age',
        'child_index',
    ];

    protected $casts = [
        'age' => 'integer',
        'child_index' => 'integer',
    ];

    /**
     * Relationship với BookingRoom
     */
    public function bookingRoom()
    {
        return $this->belongsTo(BookingRoom::class, 'booking_room_id', 'id');
    }
}
