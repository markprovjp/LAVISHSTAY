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
        'published_at',
        'category_id',
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
     * Lấy URL ảnh đại diện
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? $this->thumbnail->filepath : asset('storage/no-image.png');
    }
}