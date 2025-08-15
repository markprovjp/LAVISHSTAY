<?php

namespace App\Models\News;

use App\Models\User; // Import model User
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'slug',
        'title',
        'summary',
        'content',
        'tags',
        'thumbnail_id',
        'author_id',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'schema_json',
        'views',
        'status',
        'published_at',
        'is_featured', // Thêm cột is_featured
    ];

    /**
     * Khai báo các cột ngày tháng để Laravel tự động chuyển thành Carbon
     */
    protected $dates = [
        'published_at',
    ];

    /**
     * Hoặc sử dụng casts (tùy phiên bản Laravel)
     */
    protected $casts = [
        'tags' => 'array',
        'schema_json' => 'array',
        'published_at' => 'datetime',
        'status' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Quan hệ với bảng NewsCategory
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    /**
     * Quan hệ với bảng MediaFile (thumbnail)
     */
    public function thumbnail()
    {
        return $this->belongsTo(MediaFile::class, 'thumbnail_id');
    }

    /**
     * Quan hệ nhiều-nhiều với MediaFile qua bảng news_media_files
     */
    public function mediaFiles()
    {
        return $this->belongsToMany(MediaFile::class, 'news_media_files', 'news_id', 'media_file_id');
    }

    /**
     * Quan hệ với bảng User (tác giả)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Quan hệ với bảng NewsComment
     */
    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    /**
     * Quan hệ với bảng NewsUserAction
     */
    public function userActions()
    {
        return $this->hasMany(NewsUserAction::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 1)
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('content', 'like', '%' . $search . '%')
              ->orWhere('meta_title', 'like', '%' . $search . '%');
        });
    }

    // Helper methods
    public function getLikesCount()
    {
        return $this->userActions()->where('is_liked', 1)->count();
    }

    public function getBookmarksCount()
    {
        return $this->userActions()->where('is_bookmarked', 1)->count();
    }

    public function getAverageRating()
    {
        return $this->userActions()->whereNotNull('rating')->avg('rating');
    }

    public function getUserAction($userId = null)
    {
        if (!$userId) {
            return null;
        }
        
        return $this->userActions()->where('user_id', $userId)->first();
    }

    /**
     * Lấy URL ảnh đại diện
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? $this->thumbnail->filepath : asset('storage/no-image.png');
    }
}