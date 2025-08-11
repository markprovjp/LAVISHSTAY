<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NewsUserAction extends Model
{
    use HasFactory;

    protected $table = 'news_user_actions';

    public $timestamps = false;

    protected $fillable = [
        'news_id',
        'user_id',
        'is_liked',
        'is_bookmarked',
        'rating',
    ];

    protected $casts = [
        'is_liked' => 'boolean',
        'is_bookmarked' => 'boolean',
        'rating' => 'float',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeLiked($query)
    {
        return $query->where('is_liked', 1);
    }

    public function scopeBookmarked($query)
    {
        return $query->where('is_bookmarked', 1);
    }

    public function scopeRated($query)
    {
        return $query->whereNotNull('rating');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByNews($query, $newsId)
    {
        return $query->where('news_id', $newsId);
    }
}
