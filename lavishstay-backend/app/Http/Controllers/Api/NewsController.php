<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 9; // Số bài viết mỗi trang
        $searchTitle = $request->query('search_title');
        $categoryId = $request->query('category_id');
        $status = $request->query('status', 1); // Mặc định chỉ lấy bài viết hiển thị

        $query = News::query()
            ->with(['thumbnail', 'category', 'author'])
            ->where('status', $status)
            ->where('published_at', '<=', Carbon::now());

        if ($searchTitle) {
            $query->where(function ($q) use ($searchTitle) {
                $q->where('meta_title', 'like', '%' . $searchTitle . '%')
                  ->orWhere('content', 'like', '%' . $searchTitle . '%');
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ], 200);
    }

    /**
     * Lấy chi tiết bài viết theo slug
     */
    public function show($slug)
    {
        $news = News::query()
            ->with(['thumbnail', 'category', 'author'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->where('published_at', '<=', Carbon::now())
            ->first();

        if (!$news) {
            return response()->json(['message' => 'Bài viết không tồn tại'], 404);
        }

        // Tăng số lượt xem
        $news->increment('views');

        return response()->json($news, 200);
    }

    /**
     * Lấy danh sách danh mục
     */
    public function categories()
    {
        $categories = NewsCategory::all(['id', 'name', 'slug', 'description', 'created_at', 'updated_at']);

        return response()->json($categories, 200);
    }
}