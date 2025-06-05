<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    protected $primaryKey = 'room_id';
    public $timestamps = false;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'name',
        'image',
        'base_price_vnd',
        'size',
        'view',
        'rating',
        'lavish_plus_discount',
        'max_guests',
        'total_rooms',
        'description'
    ];

    protected $casts = [
        'base_price_vnd' => 'decimal:2',
        'rating' => 'decimal:1',
        'lavish_plus_discount' => 'decimal:2',
    ];

    /**
     * Relationship with room type
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
