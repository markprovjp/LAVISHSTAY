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
        'floor',
        'image',
        'base_price_vnd',
        'size',
        'view',
        'rating',
        'lavish_plus_discount',
        'max_guests',
        'description',
        'status'
    ];

    protected $casts = [
        'base_price_vnd' => 'decimal:2',
        'rating' => 'decimal:1',
        'lavish_plus_discount' => 'decimal:2',
        'size' => 'integer',
        'max_guests' => 'integer',
        'floor' => 'integer'
    ];


    public function translations()
    {
        return $this->hasMany(Translation::class, 'record_id')
            ->where('table_name', $this->getTable());
    }
    public function getTranslatedAttribute($column, $lang)
    {
        $translation = $this->translations()
            ->where('column_name', $column)
            ->where('language_code', $lang)
            ->first();

        return $translation ? $translation->value : $this->$column;
    }
    /**
     * Relationship with room type
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'available' => 'Trống',
            'occupied' => 'Đang sử dụng',
            'maintenance' => 'Đang bảo trì',
            'cleaning' => 'Đang dọn dẹp'
        ];

        return $labels[$this->status] ?? 'Không xác định';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'available' => 'green',
            'occupied' => 'red',
            'maintenance' => 'yellow',
            'cleaning' => 'blue'
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function roomOptions()
    {
        return $this->hasMany(RoomOption::class, 'room_id', 'room_id');
    }
    
    /**
     * Get availability data through room options
     */
    public function availability()
    {
        return $this->hasManyThrough(
            RoomAvailability::class,
            RoomOption::class,
            'room_id', // Foreign key on room_option table
            'option_id', // Foreign key on room_availability table
            'room_id', // Local key on rooms table
            'option_id' // Local key on room_option table
        );
    }

    public function bookings(){
        return $this->hasMany(Booking::class, 'room_id', 'room_id');
    }
}
