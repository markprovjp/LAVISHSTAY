<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $primaryKey = 'review_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'booking_id',
        'rating',
        'title',
        'comment',
        'detailed_scores',
        'pros',
        'cons',
        'travel_type',
        'review_date',
        'status',
        'admin_note',
        'helpful_count',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'review_date' => 'date',
        'detailed_scores' => 'array',
        'helpful_count' => 'integer',
        'status' => 'string', // enum: pending, approved, rejected
    ];

    // Quan hệ với Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    // Quan hệ với ReviewMedia
    public function reviewMedia()
    {
        return $this->hasMany(ReviewMedia::class, 'review_id', 'review_id');
    }

    // Lấy User qua Booking
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'booking_id', // Khóa chính trên bookings
            'id',         // Khóa chính trên users
            'booking_id', // Khóa ngoại trên reviews
            'user_id'     // Khóa ngoại trên bookings
        );
    }

    // Lấy RoomOption qua Booking
    public function roomOption()
    {
        return $this->hasOneThrough(    
            RoomOption::class,
            Booking::class,
            'booking_id', // Khóa chính trên bookings
            'option_id',  // Khóa chính trên room_options
            'booking_id', // Khóa ngoại trên reviews
            'option_id'   // Khóa ngoại trên bookings
        );
    }
}
