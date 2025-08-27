<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCommentLike extends Model
{
    use HasFactory;

    protected $table = 'news_comment_likes';

    protected $fillable = [
        'comment_id',
        'user_id',
    ];

    public function comment()
    {
        return $this->belongsTo(NewsComment::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
