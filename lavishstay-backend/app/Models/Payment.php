<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'booking_code',
        'customer_name', 
        'customer_email', 
        'customer_phone', 
        'rooms_data', 
        'total_amount', 
        'payment_method', 
        'payment_status', 
        'payment_confirmed_at',
        'check_in',
        'check_out',
        'special_requests'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'payment_confirmed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'rooms_data' => 'json'
    ];

    // Scope cho pending payments
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Scope cho VietQR payments
    public function scopeVietQR($query)
    {
        return $query->where('payment_method', 'vietqr');
    }

    // Accessor cho booking code format
    public function getFormattedBookingCodeAttribute()
    {
        return 'LAVISH' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Confirm payment method
    public function confirmPayment()
    {
        $this->update([
            'payment_status' => 'confirmed',
            'payment_confirmed_at' => now()
        ]);
    }
}