<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;

class SeoScore extends Model
{
    protected $table = 'seo_scores';
    protected $primaryKey   = 'news_id';
    public    $incrementing = false;

   
    public $timestamps = true;   // bảng của bạn có cả 2 cột, giữ nguyên = true

    protected $fillable = [
        'news_id',
        'seo_score',
        'focus_keyword',
        'has_h1',
        'has_image_with_alt',
        'has_internal_link',
        'keyword_density',
        'is_slug_contain_keyword',
    ];

    /*--------------------------------------------------------------
    | 5. QUAN HỆ – 1-1 ngược với News
    *-------------------------------------------------------------*/
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /*--------------------------------------------------------------
    | 6. CASTING – đảm bảo kiểu boolean / number chính xác
    *-------------------------------------------------------------*/
    protected $casts = [
        'seo_score'           => 'integer',
        'has_h1'              => 'boolean',
        'has_image_with_alt'  => 'boolean',
        'has_internal_link'   => 'boolean',
        'keyword_density'     => 'decimal:2',
        'is_slug_contain_keyword' => 'boolean',
    ];
}