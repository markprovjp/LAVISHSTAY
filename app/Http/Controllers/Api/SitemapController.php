<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap_index', now()->addHours(24), function () {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            $xml .= '<sitemap>';
            $xml .= '<loc>http://localhost:8888/api/sitemap-main</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toDateString() . '</lastmod>';
            $xml .= '</sitemap>';

            $xml .= '<sitemap>';
            $xml .= '<loc>http://localhost:8888/api/sitemap-categories</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toDateString() . '</lastmod>';
            $xml .= '</sitemap>';

            $xml .= '<sitemap>';
            $xml .= '<loc>http://localhost:8888/api/sitemap-news?page=1</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toDateString() . '</lastmod>';
            $xml .= '</sitemap>';

            $xml .= '</sitemapindex>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function main()
    {
        $xml = Cache::remember('sitemap_main', now()->addHours(24), function () {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            $xml .= '<url>';
            $xml .= '<loc>http://localhost:3000/tin-tuc</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toDateString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function categories()
    {
        $xml = Cache::remember('sitemap_categories', now()->addHours(24), function () {
            $categories = NewsCategory::all(['slug', 'updated_at']);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($categories as $category) {
                $xml .= '<url>';
                $xml .= '<loc>http://localhost:3000/tin-tuc/' . htmlspecialchars($category->slug) . '</loc>';
                $xml .= '<lastmod>' . $category->updated_at->toDateString() . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.7</priority>';
                $xml .= '</url>';
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function news(Request $request)
    {
        $perPage = 10000;
        $page = $request->query('page', 1);

        $xml = Cache::remember("sitemap_news_page_{$page}", now()->addHours(24), function () use ($page, $perPage) {
            $news = News::where('status', 1)
                ->where('published_at', '<=', Carbon::now())
                ->orderBy('updated_at', 'desc')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get(['slug', 'updated_at']);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($news as $item) {
                $xml .= '<url>';
                $xml .= '<loc>http://localhost:3000/tin-tuc/' . htmlspecialchars($item->slug) . '</loc>';
                $xml .= '<lastmod>' . $item->updated_at->toDateString() . '</lastmod>';
                $xml .= '<changefreq>monthly</changefreq>';
                $xml .= '<priority>0.6</priority>';
                $xml .= '</url>';
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}