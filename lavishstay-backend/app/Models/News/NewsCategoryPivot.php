<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use App\Models\News\News;
use App\Models\News\NewsCategory;

class NewsCategoryPivot extends Model
{
    protected $table      = 'news_category_pivot';
    public    $incrementing = false;          // PRIMARY KEY không tự tăng
    public    $timestamps   = false;          // Bảng pivot thường không có created_at / updated_at
    protected $primaryKey   = ['news_id', 'category_id']; // Khóa kép

  
    protected $fillable = ['news_id', 'category_id'];

   
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('news_id', $this->getAttribute('news_id'))
            ->where('category_id', $this->getAttribute('category_id'));

        return $query;
    }
}