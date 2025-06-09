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
        return $this->belongsToMany(
            Amenity::class, 
            'room_type_amenity',     // Pivot table
            'room_type_id',          // Foreign key của RoomType trong pivot
            'amenity_id'             // Foreign key của Amenity trong pivot
        )->withPivot('is_highlighted')  // Lấy thêm field từ pivot
         ->withTimestamps();            // Nếu pivot có created_at, updated_at
    }

    public function highlightedAmenities()
    {
        return $this->amenities()->where('is_highlighted', 1);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id', 'room_type_id');
    }
    /**
     * Relationship với bảng room_type_image
     */
    public function images()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Lấy ảnh chính
     */
    public function mainImage()
    {
        return $this->hasOne(RoomTypeImage::class, 'room_type_id', 'room_type_id')
                    ->where('is_main', 1);
    }

    /**
     * Lấy các ảnh phụ
     */
    public function otherImages()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id', 'room_type_id')
                    ->where('is_main', 0);
    }
    
}