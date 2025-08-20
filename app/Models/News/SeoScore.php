<?php

namespace App\Models\News; // Sử dụng App\Models nếu SeoScore ở thư mục app/Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoScore extends Model
{
    use HasFactory;

    protected $table = 'seo_scores';

    protected $fillable = [
        'news_id',             // ID bài viết
        'seo_score',           // Điểm SEO (0–100)
        'focus_keyword',       // Từ khóa chính bài viết
        'has_h1',              // Kiểm tra có thẻ H1 duy nhất không
        'has_image_with_alt',  // Kiểm tra có ảnh có ALT không
        'has_internal_link',   // Kiểm tra có link nội bộ không
        'keyword_density',     // Mật độ từ khóa chính trong bài viết
        'is_slug_contain_keyword', // Kiểm tra slug có chứa từ khóa chính không
    ];

    // Quan hệ với bảng News: Một bài viết có một điểm SEO
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
