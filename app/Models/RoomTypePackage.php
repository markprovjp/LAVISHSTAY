<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypePackage extends Model
{
    protected $table = 'room_type_package';
    protected $primaryKey = 'package_id';
    public $timestamps = true;

    protected $fillable = [
        'room_type_id',
        'name',
        'price_modifier_vnd',
        'include_all_services',
        'description'
    ];

    protected $casts = [
        'price_modifier_vnd' => 'decimal:2',
        'include_all_services' => 'boolean',
        'room_type_id' => 'integer'
    ];

    // Package types constants
    const PACKAGE_STANDARD = 'standard';
    const PACKAGE_VIP = 'vip';

    /**
     * Translations relationship
     */

    public function services()
{
    return $this->belongsToMany(Service::class, 'room_type_package_services', 'package_id', 'service_id')
                ->withTimestamps();
}

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
     * Room type relationship
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Room options using this package
     */
    public function roomOptions()
    {
        return $this->hasMany(RoomOption::class, 'package_id', 'package_id');
    }

    

    /**
     * Get formatted price modifier
     */
    public function getFormattedPriceModifierAttribute()
    {
        if ($this->price_modifier_vnd == 0) {
            return 'Miễn phí';
        }
        
        $sign = $this->price_modifier_vnd > 0 ? '+' : '';
        return $sign . number_format($this->price_modifier_vnd, 0, ',', '.') . ' ₫';
    }

    /**
     * Check if this is VIP package
     */
    public function getIsVipAttribute()
    {
        return $this->include_all_services || 
               stripos($this->name, 'vip') !== false || 
               stripos($this->name, 'premium') !== false;
    }

    /**
     * Check if this is standard package
     */
    public function getIsStandardAttribute()
    {
        return !$this->is_vip;
    }

    /**
     * Get package type
     */
    public function getPackageTypeAttribute()
    {
        return $this->is_vip ? self::PACKAGE_VIP : self::PACKAGE_STANDARD;
    }

    /**
     * Get services included in this package
     */
    public function getIncludedServicesAttribute()
    {
        if ($this->include_all_services) {
            return $this->roomType->services;
        }
        
        // If not include all services, return empty collection
        // You can extend this to have specific services per package
        return collect();
    }

    /**
     * Calculate total price with base room price
     */
    public function calculateTotalPrice($basePrice)
    {
        return $basePrice + $this->price_modifier_vnd;
    }

    /**
     * Scope for VIP packages
     */
    public function scopeVip($query)
    {
        return $query->where('include_all_services', true)
                    ->orWhere('name', 'like', '%vip%')
                    ->orWhere('name', 'like', '%premium%');
    }

    /**
     * Scope for standard packages
     */
    public function scopeStandard($query)
    {
        return $query->where('include_all_services', false)
                    ->where('name', 'not like', '%vip%')
                    ->where('name', 'not like', '%premium%');
    }
}
