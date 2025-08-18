<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NewsAPICategoryController extends Controller
{
    /**
     * Display a listing of news categories
     */
    public function index(Request $request)
    {
        $withNewsCount = $request->query('with_news_count', false);
        
        $query = NewsCategory::query();
        
        if ($withNewsCount) {
            $query->withCount('news');
        }
        
        $categories = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:news_categories,name',
            'slug' => 'required|string|max:255|unique:news_categories,slug',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = NewsCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = NewsCategory::withCount('news')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = NewsCategory::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:news_categories,name,' . $id,
            'slug' => 'string|max:255|unique:news_categories,slug,' . $id,
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->only(['name', 'slug', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = NewsCategory::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check if category has news articles
        $newsCount = $category->news()->count();
        if ($newsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete category. It has {$newsCount} news articles."
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Get news articles by category
     */
    public function getNews($id, Request $request)
    {
        $category = NewsCategory::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $perPage = $request->query('per_page', 10);
        $status = $request->query('status', 1);

        $news = $category->news()
            ->with(['thumbnail', 'author'])
            ->where('status', $status)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]);
    }
}