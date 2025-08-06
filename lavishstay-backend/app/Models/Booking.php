<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id'; // Auto-increment integer ID
    public $incrementing = true; // Đảm bảo sử dụng auto-increment
    
    protected $fillable = [
        'booking_code',
        'user_id',
        'option_id',
        'check_in_date',
        'check_out_date',
        'total_price_vnd',
        'guest_count',
        'adults',
        'children',
        'children_age',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone',
        'quantity',
        'room_id',
        'room_type_id',
        'notes'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price_vnd' => 'integer',
        'guest_count' => 'integer',
    ];

    public function roomOption()
    {
        return $this->belongsTo(RoomOption::class, 'option_id', 'option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    public function checkoutRequests()
    {
        return $this->hasMany(CheckoutRequest::class, 'booking_id', 'booking_id');
    }

    
    public function room()
    {
        return $this->hasMany(BookingRoom::class, 'booking_code', 'booking_code');
    }
    
    public function representatives()
    {
        return $this->hasMany(Representative::class, 'booking_code', 'booking_code');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'booking_id', 'booking_id');
    }

    /**
     * Get the latest invoice for this booking
     */
    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class, 'booking_id', 'booking_id')->latest('issued_at');
    }

    /**
     * Get total amount of additional services
     */
    public function getTotalServiceAmountAttribute()
    {
        return $this->bookingServices->sum(function ($service) {
            return $service->quantity * $service->price_vnd;
        });
    }

    /**
     * Get final total amount (room + services)
     */
    public function getFinalTotalAmountAttribute()
    {
        return $this->total_price_vnd + $this->total_service_amount;
    }

    /**
     * Get formatted final total amount
     */
    public function getFormattedFinalTotalAmountAttribute()
    {
        return number_format($this->final_total_amount, 0, ',', '.') . ' ₫';
    }

}
