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
    // Booking primary key is `booking_id`
    return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
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
            // foreign key on Bookings table, owner key on Users table
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
            // Map via booking's booking_id -> room_options.option_id
            'booking_id', // foreign key on Booking referencing this Review
            'option_id',  // local key on RoomOption
            'booking_id', // local key on Review
            'option_id'   // foreign key on Booking
        );
    }
}