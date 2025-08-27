<?php
namespace App\Models\News;

use App\Models\User;
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
        'is_featured',
    ];

    // Định dạng các cột
    protected $casts = [
        'schema_json' => 'array', // Chỉ giữ cast cho schema_json
        'published_at' => 'datetime',
        'status' => 'boolean',
        'views' => 'integer',
    ];
    // - Dòng trên: Loại bỏ 'tags' => 'array' vì tags giờ là chuỗi thô.
    // + Lợi ích: Tránh tự động chuyển tags thành mảng, giữ nguyên dạng chuỗi như yêu cầu.
    // + Lý do: Cột tags trong cơ sở dữ liệu không còn là JSON, nên không cần cast.

    protected $dates = [
        'published_at',
    ];

    // Quan hệ với bảng NewsCategory
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    // Quan hệ với bảng MediaFile (thumbnail)
    public function thumbnail()
    {
        return $this->belongsTo(MediaFile::class, 'thumbnail_id');
    }

    // Quan hệ nhiều-nhiều với MediaFile
    public function mediaFiles()
    {
        return $this->belongsToMany(MediaFile::class, 'news_media_files', 'news_id', 'media_file_id');
    }

    // Quan hệ với bảng User (tác giả)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Quan hệ với bảng NewsComment
    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    // Quan hệ với bảng NewsUserAction
    public function userActions()
    {
        return $this->hasMany(NewsUserAction::class);
    }

    // Scope: Lấy bài viết đã xuất bản
    public function scopePublished($query)
    {
        return $query->where('status', 1)->where('published_at', '<=', now());
    }

    // Scope: Lấy bài viết theo danh mục
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Scope: Tìm kiếm bài viết
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('content', 'like', '%' . $search . '%')
              ->orWhere('meta_title', 'like', '%' . $search . '%')
              ->orWhere('tags', 'like', '%' . $search . '%'); // Thêm tìm kiếm trong tags
        });
    }
    // - Dòng trên: Thêm tags vào scopeSearch để hỗ trợ tìm kiếm theo chuỗi tags.
    // + Lợi ích: Cho phép tìm kiếm tags như chuỗi văn bản (ví dụ: tìm "Phước ơi").
    // + Lý do: Tags giờ là chuỗi thô, có thể dùng LIKE để tìm kiếm.

    // Đếm số lượt thích
    public function getLikesCount()
    {
        return $this->userActions()->where('is_liked', 1)->count();
    }

    // Đếm số lượt đánh dấu
    public function getBookmarksCount()
    {
        return $this->userActions()->where('is_bookmarked', 1)->count();
    }

    // Tính điểm đánh giá trung bình
    public function getAverageRating()
    {
        return $this->userActions()->whereNotNull('rating')->avg('rating');
    }

    // Lấy hành động của người dùng
    public function getUserAction($userId = null)
    {
        if (!$userId) {
            return null;
        }
        return $this->userActions()->where('user_id', $userId)->first();
    }

    // Lấy URL ảnh đại diện
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? $this->thumbnail->filepath : asset('storage/no-image.png');
    }
}