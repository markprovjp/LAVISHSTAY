<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;

    protected $table = 'news_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Quan hệ nhiều-nhiều với bài viết
     */
    public function news()
    {
        return $this->belongsToMany(
            News::class,
            'news_category_pivot',
            'category_id',
            'news_id'
        );
    }

    /**
     * Tự động tạo slug duy nhất từ name
     */
    protected static function booted()
    {
        static::saving(function ($category) {
            if (empty($category->slug) || $category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    /**
     * Hàm tạo slug không trùng
     */
    protected static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}