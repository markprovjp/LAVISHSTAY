<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

trait HasTranslations
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
class RoomType extends Model
{
    use HasTranslations;
    protected $table = 'room_types';
    protected $primaryKey = 'room_type_id';
    public $timestamps = false;

    protected $fillable = [
        'room_code',
        'name',
        'description',
        'total_room',
        'base_price',
        'room_area',
        'view',
        'rating',
        'max_guests'
    ];


    protected $casts = [
        'total_room' => 'integer',
        'base_price' => 'decimal:2',
        'room_area' => 'integer',
        'rating' => 'integer',
        'max_guests' => 'integer'
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'record_id')
            ->where('table_name', $this->getTable());
    }
    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'room_type_amenity',     // Pivot table
            'room_type_id',          // Foreign key của RoomType trong pivot
            'amenity_id'             // Foreign key của Amenity trong pivot
        )->withPivot('is_highlighted')  // Lấy thêm field từ pivot
            ->withTimestamps();            // Nếu pivot có created_at, updated_at
    }

    public function highlightedAmenities()
    {
        return $this->amenities()->where('is_highlighted', 1);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id', 'room_type_id');
    }

    public function dynamicPricingRules()
    {
        return $this->hasMany(DynamicPricingRule::class, 'room_type_id', 'room_type_id');
    }
    /**
     * Relationship với bảng room_type_image
     */
    public function images()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Lấy ảnh chính
     */
    public function mainImage()
    {
        return $this->hasOne(RoomTypeImage::class, 'room_type_id', 'room_type_id')
            ->where('is_main', 1);
    }

    /**
     * Lấy các ảnh phụ
     */
    public function otherImages()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id', 'room_type_id')
            ->where('is_main', 0);
    }
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            Room::class,
            'room_type_id',
            'room_id',
            'room_type_id',
            'room_id'
        );
    }


    /**
     * Flexible pricing rules
     */
    public function flexiblePricingRules()
    {
        return $this->hasMany(FlexiblePricingRule::class, 'room_type_id', 'room_type_id');
    }


    /**
     * Packages relationship
     */
    public function packages()
    {
        return $this->hasMany(RoomTypePackage::class, 'room_type_id', 'room_type_id');
    }
    

    /**
     * Get total available rooms count
     */
    public function getAvailableRoomsCountAttribute()
    {
        return $this->availableRooms()->count();
    }

    /**
     * Get occupancy rate
     */
    public function getOccupancyRateAttribute()
    {
        $totalRooms = $this->total_room;
        $occupiedRooms = $this->rooms()->where('status', Room::STATUS_OCCUPIED)->count();
        
        return $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
    }

    /**
     * Scope for room types with available rooms
     */
    public function scopeWithAvailableRooms($query)
    {
        return $query->whereHas('rooms', function($q) {
            $q->where('status', Room::STATUS_AVAILABLE);
        });
    }

    /**
     * Scope for room types by price range
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('base_price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope for room types by guest capacity
     */
    public function scopeByGuestCapacity($query, $guests)
    {
        return $query->where('max_guests', '>=', $guests);
    }

    /**
     * Services relationship
     */
    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'room_type_service',
            'room_type_id',
            'service_id'
        )->withTimestamps();
    }

    /**
     * Active services only
     */
    public function activeServices()
    {
        return $this->services()->where('services.is_active', true);
    }

    /**
     * VIP packages
     */
    public function vipPackages()
    {
        return $this->packages()->vip();
    }

    /**
     * Standard packages
     */
    public function standardPackages()
    {
        return $this->packages()->standard();
    }

    /**
     * Get default VIP package
     */
    public function getDefaultVipPackageAttribute()
    {
        return $this->vipPackages()->first() ?: $this->createDefaultVipPackage();
    }

    /**
     * Get default standard package
     */
    public function getDefaultStandardPackageAttribute()
    {
        return $this->standardPackages()->first() ?: $this->createDefaultStandardPackage();
    }

    /**
     * Create default VIP package if not exists
     */
    protected function createDefaultVipPackage()
    {
        return $this->packages()->create([
            'name' => 'Gói VIP',
            'price_modifier_vnd' => $this->base_price * 0.3, // 30% increase
            'include_all_services' => true,
            'description' => 'Gói VIP bao gồm tất cả dịch vụ của loại phòng này'
        ]);
    }

    /**
     * Create default standard package if not exists
     */
    protected function createDefaultStandardPackage()
    {
        return $this->packages()->create([
            'name' => 'Gói Tiêu chuẩn',
            'price_modifier_vnd' => 0,
            'include_all_services' => false,
            'description' => 'Gói tiêu chuẩn không bao gồm dịch vụ bổ sung'
        ]);
    }

    /**
     * Get total services value
     */
    public function getTotalServicesValueAttribute()
    {
        return $this->activeServices()->sum('price_vnd');
    }

    /**
     * Calculate VIP package price
     */
    public function getVipPriceAttribute()
    {
        $vipPackage = $this->default_vip_package;
        return $this->base_price + $vipPackage->price_modifier_vnd;
    }

    /**
     * Calculate standard package price
     */
    public function getStandardPriceAttribute()
    {
        $standardPackage = $this->default_standard_package;
        return $this->base_price + $standardPackage->price_modifier_vnd;
    }

}
