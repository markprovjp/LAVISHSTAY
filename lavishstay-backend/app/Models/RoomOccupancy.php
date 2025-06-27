<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOccupancy extends Model
{
    protected $table = 'room_occupancy';
    protected $primaryKey = 'occupancy_id';
    
    protected $fillable = [
        'room_type_id',
        'date',
        'total_rooms',
        'booked_rooms'
    ];

    protected $casts = [
        'date' => 'date',
        'occupancy_rate' => 'decimal:2'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
