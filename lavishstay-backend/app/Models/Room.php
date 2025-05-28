<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'capacity',
        'amenities',
        'image_url',
        'status',
    ];

    protected $casts = [
        'amenities' => 'array', // Assuming amenities is stored as a JSON array
    ];

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
