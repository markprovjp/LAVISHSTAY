<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypeAmenity extends Model
{
    protected $table = 'room_type_amenity';
    protected $primaryKey = 'amenity_id';
    public $timestamps = false;

    protected $fillable = [
        'room_type_id',
        'name',
        'icon',
        'is_highlighted'
    ];

    protected $casts = [
        'is_highlighted' => 'boolean'
    ];

    /**
     * Relationship with room type
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
