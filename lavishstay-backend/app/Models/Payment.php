<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    protected $fillable = ['booking_id', 'amount_vnd', 'payment_type', 'status', 'transaction_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}