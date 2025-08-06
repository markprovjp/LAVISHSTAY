<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $table = 'booking_services';
    protected $primaryKey = 'id';
    public $timestamps = false; // Table only has created_at, not updated_at

    protected $fillable = [
        'booking_id',
        'service_id',
        'quantity',
        'price_vnd'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_vnd' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    /**
     * Relationship with booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Relationship with service
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    /**
     * Get total price for this booking service
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price_vnd;
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' ₫';
    }

    /**
     * Scope for specific booking
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope for specific service
     */
    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    /**
     * Calculate total amount for multiple booking services
     */
    public static function calculateTotalAmount($bookingServices)
    {
        return $bookingServices->sum(function ($bookingService) {
            return $bookingService->quantity * $bookingService->price_vnd;
        });
    }
}