<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\News\NewsCategory;
use App\Models\User;
use App\Models\News\MediaFile;
use App\Models\News\SeoScore;

class News extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail_id',
        'author_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'schema_json',
        'views',
        'status',
        'published_at'
    ];

    /**
     * Ép kiểu các trường dữ liệu
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(MediaFile::class, 'thumbnail_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            NewsCategory::class,
            'news_category_pivot',
            'news_id',
            'category_id'
        );
    }

    // app/Models/News/News.php
    public function seoScore()
    {
        return $this->hasOne(SeoScore::class, 'news_id');
    }
}