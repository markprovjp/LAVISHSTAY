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
        'helpful',
        'not_helpful',
        'admin_reply_content',
        'admin_reply_date',
        'admin_name',
        'status',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'review_date' => 'date',
        'admin_reply_date' => 'date',
        'detailed_scores' => 'array',
        'helpful' => 'integer',
        'not_helpful' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function reviewMedia()
    {
        return $this->hasMany(ReviewMedia::class, 'review_id', 'review_id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id',
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
            'id', // Khóa ngoại trên booking
            'option_id',  // Khóa chính của room_options
            'booking_id', // Khóa chính của reviews
            'option_id'   // Khóa ngoại trên booking
        );
    }
}