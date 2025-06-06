<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = 'room_types';
    protected $primaryKey = 'room_type_id';
    public $timestamps = false;

    protected $fillable = [
        'room_code',
        'name',
        'description',
        'total_room'
    ];

    public function amenities()
    {
        return $this->hasMany(RoomTypeAmenity::class, 'room_type_id', 'room_type_id');
    }

    public function highlightedAmenities()
    {
        return $this->amenities()->where('is_highlighted', 1);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id', 'room_type_id');
    }
    
}