<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $primaryKey = 'amenity_id';
    
    protected $fillable = [
        'name',
        'description',
        'icon',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_amenities', 'amenity_id', 'room_type_id')
                    ->withPivot('is_highlighted', 'created_at', 'updated_at')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->icon ? $this->icon . ' ' . $this->name : $this->name;
    }
}
