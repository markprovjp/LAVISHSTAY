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
        'floor_id',
        'bed_type_fixed',
        'status',
        'description',
        'last_cleaned',
        'room_area', 
        'max_guests'
    ];

    protected $casts = [
        'status' => 'string',
        'last_cleaned' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'available' => 'Trống',
            'occupied' => 'Đang sử dụng',
            'maintenance' => 'Đang bảo trì',
            'cleaning' => 'Đang dọn dẹp',
        ];
        return $statusLabels[$this->status] ?? $this->status;
    }

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

    public function availability()
    {
        return $this->hasManyThrough(
            RoomAvailability::class,
            RoomOption::class,
            'room_id',
            'option_id',
            'room_id',
            'option_id'
        );
    }

    public function bookings()
    {
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

    // public function bedTypes()
    // {
    //     return $this->belongsToMany(
    //         BedType::class,
    //         'room_bed_types',
    //         'room_id',
    //         'id'
    //     )->withPivot('quantity', 'is_default')
    //       ->withTimestamps();
    // }

    public function bedType(){
        return $this->belongsTo(BedType::class, 'bed_type_fixed', 'id');
    }

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

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor_id', $floor);
    }

    public function scopeByType($query, $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
    }
}