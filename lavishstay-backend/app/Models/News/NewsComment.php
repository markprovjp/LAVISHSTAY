<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NewsComment extends Model
{
    use HasFactory;

    protected $table = 'news_comments';

    protected $fillable = [
        'news_id',
        'user_id',
        'content',
        'likes',
        'parent_id',
    ];

    protected $casts = [
        'likes' => 'integer',
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

    public function parent()
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id');
    }

    public function allReplies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->with('allReplies');
    }

    // Scopes
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Helper methods
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    public function getRepliesCount()
    {
        return $this->replies()->count();
    }
}
