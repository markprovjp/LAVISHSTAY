<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'price_vnd',
        'unit',
        'included_services',
        'is_active'
    ];

    protected $casts = [
        'price_vnd' => 'decimal:2',
        'is_active' => 'boolean',
        'included_services' => 'boolean'
    ];

    /**
     * Translations relationship
     */
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
     * Room types that use this service
     */
    public function roomTypes()
    {
        return $this->belongsToMany(
            RoomType::class,
            'room_type_service',
            'service_id',
            'room_type_id'
        )->withTimestamps();
    }

    public function packages(){
        return $this->belongsToMany(RoomTypePackage::class, 'room_type_package_services', 'service_id', 'package_id')
                    ->withTimestamps();
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price_vnd, 0, ',', '.') . ' ₫';
    }

    /**
     * Get price with unit
     */
    public function getPriceWithUnitAttribute()
    {
        $price = $this->formatted_price;
        return $this->unit ? $price . '/' . $this->unit : $price;
    }
    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingService::class, 'service_id', 'service_id');
    }

    
    /**
     * Scope for included services (add-on services)
     */
    public function scopeIncluded($query)
    {
        return $query->where('included_services', true);
    }

}
