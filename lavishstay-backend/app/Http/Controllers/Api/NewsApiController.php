<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
    

class NewsApiController extends Controller
{
    // Danh sách bài viết
    public function index(Request $request)
    {
        $query = News::with('category')->where('status', 1);

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $newsList = $query->latest()->paginate(10);
        return response()->json($newsList);
    }

    // Xem chi tiết bài viết
    public function show($id)
    {
        $news = News::with('category')->findOrFail($id);
        return response()->json($news);
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:news_categories,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $news = News::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'content' => $validated['content'] ?? null,
            'category_id' => $validated['category_id'],
            'author_id' => Auth::id() ?? 1,
            'status' => $request->input('status', 1),
            'publish_date' => now(),
        ]);

        return response()->json(['message' => 'Tạo bài viết thành công', 'data' => $news], 201);
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:news_categories,id',
        ]);

        $news->update(array_merge($validated, [
            'status' => $request->input('status', $news->status)
        ]));

        return response()->json(['message' => 'Đã cập nhật bài viết', 'data' => $news]);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        News::destroy($id);
        return response()->json(['message' => 'Đã xóa bài viết']);
    }

    // Danh sách danh mục
    public function getCategories()
    {
        return response()->json(NewsCategory::orderBy('name')->get());
    }

    // Lấy chi tiết danh mục
    public function getCategory($id)
    {
        return response()->json(NewsCategory::findOrFail($id));
    }

    // Tạo danh mục
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = NewsCategory::create($validated);
        return response()->json(['message' => 'Tạo danh mục thành công', 'data' => $category], 201);
    }

    // Cập nhật danh mục
    public function updateCategory(Request $request, $id)
    {
        $category = NewsCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);
        return response()->json(['message' => 'Đã cập nhật danh mục']);
    }

    // Xóa danh mục
    public function destroyCategory($id)
    {
        NewsCategory::destroy($id);
        return response()->json(['message' => 'Đã xóa danh mục']);
    }
}