<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
    use HasFactory;

    protected $table = 'bed_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type_name',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scope để lấy Bed Types đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Quan hệ ngược lại với Room (nếu cần)
    public function rooms()
    {
        return $this->hasMany(Room::class, 'bed_type_fixed', 'id');
    }
}