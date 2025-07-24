<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'amount_vnd',
        'payment_type',
        'status',
        'transaction_id'
    ];

    protected $casts = [
        'amount_vnd' => 'decimal:2',
    ];

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for completed payments
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Relationship with booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    // Confirm payment method
    public function confirmPayment()
    {
        $this->update([
            'status' => 'completed'
        ]);
    }
}