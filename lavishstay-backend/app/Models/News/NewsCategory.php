<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $table = 'news_categories';

    protected $fillable = [
        'name',        // Tên danh mục (Ví dụ: Tin tức, Ưu đãi)
        'slug',        // URL thân thiện SEO của danh mục
        'description', // Mô tả danh mục
    ];

    // Quan hệ với bảng News: Một danh mục có nhiều bài viết
    public function news()
    {
        return $this->hasMany(News::class, 'category_id');
    }
}