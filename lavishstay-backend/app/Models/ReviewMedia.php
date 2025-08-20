<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewMedia extends Model
{
    protected $table = 'review_media';
    
    protected $fillable = [
        'review_id',
        'file_url',
        'file_type',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
    
    protected $dates = ['created_at'];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id', 'review_id');
    }
}
