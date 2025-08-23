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
        'price_vnd',
        'paid_amount_vnd',
        'payment_status',
        'last_payment_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_vnd' => 'decimal:2',
        'paid_amount_vnd' => 'decimal:2',
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
     * Get outstanding amount for this booking service
     */
    public function getOutstandingAmountAttribute()
    {
        return max(0, $this->total_price - $this->paid_amount_vnd);
    }

    /**
     * Get payment completion percentage
     */
    public function getPaymentPercentageAttribute()
    {
        if ($this->total_price <= 0) return 100;
        return min(100, round(($this->paid_amount_vnd / $this->total_price) * 100, 2));
    }

    /**
     * Check if this service is fully paid
     */
    public function isFullyPaid()
    {
        return $this->paid_amount_vnd >= $this->total_price;
    }

    /**
     * Check if this service has partial payment
     */
    public function isPartiallyPaid()
    {
        return $this->paid_amount_vnd > 0 && $this->paid_amount_vnd < $this->total_price;
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
