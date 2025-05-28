<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = 'room_types';
    protected $primaryKey = 'room_type_id';

    protected $fillable = [
        'type_name_en',
        'type_name_vi',
        'category',
        'description_en',
        'description_vi',
        'size_sqm',
        'max_adults',
        'max_children',
        'max_occupancy',
        'base_price_usd',
        'weekend_price_usd',
        'is_sale',
        'sale_percentage',
        'sale_price_usd',
        'sale_start_date',
        'sale_end_date',
        'view_type',
        'bed_type',
        'room_features_en',
        'room_features_vi',
        'policies_en',
        'policies_vi',
        'cancellation_policy_en',
        'cancellation_policy_vi',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_sale' => 'boolean',
        'is_active' => 'boolean',
        'sale_percentage' => 'decimal:2',
        'base_price_usd' => 'decimal:2',
        'weekend_price_usd' => 'decimal:2',
        'sale_price_usd' => 'decimal:2',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'room_features_en' => 'array',
        'room_features_vi' => 'array',
        'max_adults' => 'integer',
        'max_children' => 'integer',
        'max_occupancy' => 'integer',
        'size_sqm' => 'integer',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_sale', true)
                    ->where('sale_start_date', '<=', now())
                    ->where('sale_end_date', '>=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        if ($this->is_sale && 
            $this->sale_start_date <= now() && 
            $this->sale_end_date >= now() && 
            $this->sale_price_usd) {
            return $this->sale_price_usd;
        }
        return $this->base_price_usd;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->is_sale && 
               $this->sale_start_date <= now() && 
               $this->sale_end_date >= now();
    }
}
