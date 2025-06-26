<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $table = 'room';
    protected $primaryKey = 'room_id';
    public $timestamps = false;

    protected $fillable = [
        'room_type_id',
        'name',
        'image',
        'floor',
        'status',
        'description'
    ];

    protected $casts = [
        'floor' => 'integer',
        'room_type_id' => 'integer'
    ];

      // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_CLEANING = 'cleaning';


    
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

    public function transfersAsOldRoom()
    {
        return $this->hasMany(RoomTransfer::class, 'old_room_id', 'room_id');
    }

    public function transfersAsNewRoom()
    {
        return $this->hasMany(RoomTransfer::class, 'new_room_id', 'room_id');
    }
    public function bedTypes()
    {
        return $this->belongsToMany(
            BedType::class,
            'room_bed_types',
            'room_id',
            'bed_type_id'
        )->withPivot('quantity', 'is_default')
          ->withTimestamps();
    }

    /**
     * Meal types relationship
     */
    public function mealTypes()
    {
        return $this->belongsToMany(
            MealType::class,
            'room_meal_types',
            'room_id',
            'meal_type_id'
        )->withPivot('is_default')
          ->withTimestamps();
    }

    /**
     * Scope for available rooms
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for rooms by floor
     */
    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor', $floor);
    }

    /**
     * Scope for rooms by type
     */
    public function scopeByType($query, $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
    }

}
