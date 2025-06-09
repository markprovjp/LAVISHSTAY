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
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_amenity', 'amenity_id', 'room_type_id')
                    ->withPivot('is_highlighted')
                    ->withTimestamps();
    }
}
