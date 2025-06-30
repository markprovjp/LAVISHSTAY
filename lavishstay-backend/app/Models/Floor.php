<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Translation; // Đảm bảo import model Translation

trait HasFloorTranslations // Đổi tên trait thành HasFloorTranslations
{
    public function getTranslatedAttribute($column, $lang)
    {
        return Translation::where('table_name', $this->getTable())
            ->where('column_name', $column)
            ->where('record_id', $this->getKey())
            ->where('language_code', $lang)
            ->value('value') ?? $this->$column;
    }
}

class Floor extends Model
{
    use HasFloorTranslations; // Sử dụng trait với tên mới

    protected $table = 'floors';
    protected $primaryKey = 'floor_id';
    public $timestamps = true;

    protected $fillable = [
        'floor_number',
        'floor_name',
        'floor_type',
        'description',
        'facilities',
        'is_active'
    ];

    protected $casts = [
        'floor_number' => 'integer',
        'is_active' => 'boolean'
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'record_id')
            ->where('table_name', $this->getTable());
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id', 'floor_id');
    }

    public function getAvailableRoomsCountAttribute()
    {
        return $this->rooms()->where('status', Room::STATUS_AVAILABLE)->count();
    }

    public function scopeByFloorNumber($query, $floorNumber)
    {
        return $query->where('floor_number', $floorNumber);
    }

    public function scopeByFloorType($query, $floorType)
    {
        return $query->where('floor_type', $floorType);
    }
}