<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    
    protected $fillable = [
        'user_id',
        'option_id',
        'check_in_date',
        'check_out_date',
        'total_price_vnd',
        'guest_count',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price_vnd' => 'decimal:2',
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
}
