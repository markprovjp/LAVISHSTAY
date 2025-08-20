<?php

namespace App\Observers;

use App\Models\News\News;
use Illuminate\Support\Facades\Cache;

class NewsObserver
{
    public function created(News $news)
    {
        // Xóa cache các sitemap liên quan
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_news_page_1'); // Xóa trang đầu tiên, có thể mở rộng cho các trang khác
    }

    public function updated(News $news)
    {
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_news_page_1');
    }

    public function deleted(News $news)
    {
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_news_page_1');
    }
}