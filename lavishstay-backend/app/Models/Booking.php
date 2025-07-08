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
        'room_type_id'
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

    
    public function rooms()
    {
        return $this->hasMany(BookingRoom::class, 'booking_code', 'booking_code');
    }
    
    public function representatives()
    {
        return $this->hasMany(Representative::class, 'booking_code', 'booking_code');
    }
}
