<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealType extends Model
{
    use HasFactory;

    protected $table = 'meal_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type_name',
        'description',
        'base_price_vnd',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price_vnd' => 'float'
    ];

    // Scope để lấy Meal Types đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}