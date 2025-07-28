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
        'user_id',
        'rating',
        'title',
        'comment',
        'review_date',
        'helpful',
        'not_helpful',
        'travel_type',
        'admin_reply_content',
        'admin_reply_date',
        'admin_name',
        'score_cleanliness',
        'score_location',
        'score_facilities',
        'score_service',
        'score_value',
        'status',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'review_date' => 'date',
        'admin_reply_date' => 'date',
        'score_cleanliness' => 'decimal:2',
        'score_location' => 'decimal:2',
        'score_facilities' => 'decimal:2',
        'score_service' => 'decimal:2',
        'score_value' => 'decimal:2',
        'helpful' => 'integer',
        'not_helpful' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'booking_id',
            'id',
            'booking_id',
            'user_id'
        );
    }

    // Mối quan hệ gián tiếp qua Booking để lấy RoomOption
    public function roomOption()
    {
        return $this->hasOneThrough(
            RoomOption::class,
            Booking::class,
            'booking_id', // Khóa ngoại trên booking
            'option_id',  // Khóa chính của room_options
            'booking_id', // Khóa chính của reviews
            'option_id'   // Khóa ngoại trên booking
        );
    }
}