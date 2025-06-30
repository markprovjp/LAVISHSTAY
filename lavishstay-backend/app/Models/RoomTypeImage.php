<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeImage extends Model
{
    use HasFactory;

    protected $table = 'room_type_image';
    protected $primaryKey = 'image_id';
    
    protected $fillable = [
        'room_type_id',
        'image_path',
        'alt_text',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    /**
     * Relationship với RoomType
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
