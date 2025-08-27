<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 9);
        $searchTitle = $request->query('search_title');
        $categoryId = $request->query('category_id');
        $status = $request->query('status', 1);
        $tags = $request->query('tags'); // JSON array of tags
        $authorId = $request->query('author_id');
        $sortBy = $request->query('sort_by', 'published_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = News::query()
            ->with(['thumbnail', 'category', 'author'])
            ->where('status', 1);

        if ($searchTitle) {
            $query->search($searchTitle);
        }

        if ($categoryId) {
            $query->byCategory($categoryId);
        }

        if ($authorId) {
            $query->where('author_id', $authorId);
        }

        if ($tags) {
            $tagsArray = json_decode($tags, true);
            if (is_array($tagsArray)) {
                $query->where(function ($q) use ($tagsArray) {
                    foreach ($tagsArray as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                });
            }
        }

        $news = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
        Log::info('NewsController index query', [
            'searchTitle' => $searchTitle,
            'categoryId' => $categoryId,
            'authorId' => $authorId,
            'tags' => $tags,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'perPage' => $perPage
        ]);
        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ], 200);
    }

    /**
     * Store a newly created news article
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'thumbnail_id' => 'nullable|exists:media_files,id',
            'category_id' => 'required|exists:news_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'schema_json' => 'nullable|array',
            'status' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $news = News::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'summary' => $request->summary,
            'content' => $request->content,
            'tags' => $request->tags ?? [],
            'thumbnail_id' => $request->thumbnail_id,
            'author_id' => Auth::id() ?? 1,
            'category_id' => $request->category_id,
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description ?? $request->summary,
            'meta_keywords' => $request->meta_keywords,
            'canonical_url' => $request->canonical_url,
            'schema_json' => $request->schema_json ?? [],
            'views' => 0,
            'status' => $request->status ?? 1,
            'published_at' => $request->published_at ?? now(),
        ]);

        $news->load(['thumbnail', 'category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'News article created successfully',
            'data' => $news
        ], 201);
    }

    /**
     * Display the specified news article
     */
    public function show($slug)
    {
        $userId = Auth::id();
        
        $news = News::query()
            ->with(['thumbnail', 'category', 'author'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->where('published_at', '<=', Carbon::now())
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        // Increment views
        $news->increment('views');

        // Get user actions
        $userId = Auth::id();
        $userAction = null;
        if ($userId) {
            $userAction = \App\Models\News\NewsUserAction::where('news_id', $news->id)
                ->where('user_id', $userId)
                ->first();
        }

        // Build response data with safe defaults
        $data = $news->toArray();
        
        // Add user action data
        $data['user_action'] = [
            'is_liked' => $userAction ? (bool)$userAction->is_liked : false,
            'is_bookmarked' => $userAction ? (bool)$userAction->is_bookmarked : false,
            'rating' => $userAction && $userAction->rating ? (float)$userAction->rating : null,
        ];
        
        // Get real stats
        $likesCount = \App\Models\News\NewsUserAction::where('news_id', $news->id)->where('is_liked', true)->count();
        $bookmarksCount = \App\Models\News\NewsUserAction::where('news_id', $news->id)->where('is_bookmarked', true)->count();
        $commentsCount = \App\Models\News\NewsComment::where('news_id', $news->id)->count();
        $averageRating = \App\Models\News\NewsUserAction::where('news_id', $news->id)->whereNotNull('rating')->avg('rating');
        
        $data['stats'] = [
            'views' => (int)$news->views,
            'likes' => $likesCount,
            'bookmarks' => $bookmarksCount,
            'comments' => $commentsCount,
            'shares' => 0,
            'average_rating' => $averageRating ? round($averageRating, 1) : 0
        ];

        // Ensure arrays are properly formatted to prevent spread errors
        $data['tags'] = is_array($data['tags']) ? $data['tags'] : [];
        $data['schema_json'] = is_array($data['schema_json']) ? $data['schema_json'] : [];

        // Ensure nested objects exist
        $data['thumbnail'] = $data['thumbnail'] ?? null;
        $data['category'] = $data['category'] ?? [
            'id' => null,
            'name' => 'Khác',
            'slug' => 'other',
            'description' => null,
            'created_at' => null,
            'updated_at' => null
        ];
        $data['author'] = $data['author'] ?? [
            'id' => null,
            'name' => 'Admin',
            'email' => null,
            'avatar' => null,
            'profile_photo_url' => null,
            'created_at' => null,
            'updated_at' => null
        ];

        // Ensure all numeric fields are properly typed
        $data['id'] = (int)$data['id'];
        $data['views'] = (int)($data['views'] ?? 0);
        $data['thumbnail_id'] = $data['thumbnail_id'] ? (int)$data['thumbnail_id'] : null;
        $data['author_id'] = $data['author_id'] ? (int)$data['author_id'] : null;
        $data['category_id'] = $data['category_id'] ? (int)$data['category_id'] : null;
        $data['is_featured'] = (bool)$data['is_featured'];
        $data['status'] = (bool)$data['status'];

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified news article
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'slug' => 'string|max:255|unique:news,slug,' . $id,
            'summary' => 'nullable|string|max:500',
            'content' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'thumbnail_id' => 'nullable|exists:media_files,id',
            'category_id' => 'exists:news_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'schema_json' => 'nullable|array',
            'status' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $news->update($request->only([
            'title', 'slug', 'summary', 'content', 'tags', 'thumbnail_id',
            'category_id', 'meta_title', 'meta_description', 'meta_keywords',
            'canonical_url', 'schema_json', 'status', 'published_at'
        ]));

        $news->load(['thumbnail', 'category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'News article updated successfully',
            'data' => $news
        ]);
    }

    /**
     * Remove the specified news article
     */
    public function destroy($id)
    {
        $news = News::find($id);
        
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        // Delete related data
        $news->comments()->delete();
        $news->userActions()->delete();
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News article deleted successfully'
        ]);
    }

    /**
     * Get related news articles
     */
    public function getRelated($slug, Request $request)
    {
        $limit = $request->query('limit', 5);
        
        $currentNews = News::where('slug', $slug)->first();
        
        if (!$currentNews) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $relatedNews = News::with(['thumbnail', 'category', 'author'])
            ->where('id', '!=', $currentNews->id)
            ->where('category_id', $currentNews->category_id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedNews
        ]);
    }

    /**
     * Get popular news articles
     */
    public function getPopular(Request $request)
    {
        $limit = $request->query('limit', 10);
        $period = $request->query('period', 'week'); // week, month, year, all

        $query = News::with(['thumbnail', 'category', 'author'])
            ->published();

        switch ($period) {
            case 'week':
                $query->where('published_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('published_at', '>=', now()->subMonth());
                break;
            case 'year':
                $query->where('published_at', '>=', now()->subYear());
                break;
            // 'all' - no date restriction
        }

        $popularNews = $query->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularNews
        ]);
    }

    /**
     * Search news by tags
     */
    public function searchByTags(Request $request)
    {
        $tags = $request->query('tags'); // comma-separated string
        $perPage = $request->query('per_page', 10);
        
        if (!$tags) {
            return response()->json([
                'success' => false,
                'message' => 'Tags parameter is required'
            ], 422);
        }

        $tagsArray = explode(',', $tags);
        
        $query = News::with(['thumbnail', 'category', 'author'])
            ->published();
            
        $query->where(function ($q) use ($tagsArray) {
            foreach ($tagsArray as $tag) {
                $q->orWhereJsonContains('tags', trim($tag));
            }
        });

        $news = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]);
    }

    /**
     * Lấy danh sách danh mục
     */
    public function categories()
    {
        $categories = NewsCategory::all(['id', 'name', 'slug', 'description', 'created_at', 'updated_at']);

        return response()->json($categories, 200);
    }

      public function getFeatured(Request $request)
    {
    $perPage = $request->query('per_page', 5);
    $isFeatured = $request->query('is_featured', 1);
    $status = $request->query('status', 1);


        // Log điều kiện lọc và SQL query
        \Log::info('getFeatured params', [
            'is_featured' => $isFeatured,
            'status' => $status,
            'per_page' => $perPage
        ]);

        $query = News::with(['thumbnail', 'category', 'author'])
                ->where('is_featured', 1)
            ->where('status', (int)$status)
            ->where('published_at', '<=', now());

        \Log::info('getFeatured SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $news = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]);
    }

    /**
     * Get trending news (by views)
     */
    public function getTrending(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $sortBy = $request->query('sort_by', 'views');
        $sortOrder = $request->query('sort_order', 'desc');
        $status = $request->query('status', 1);

        $query = News::with(['thumbnail', 'category', 'author'])
            ->where('status', (int)$status)
            ->where('published_at', '<=', now());

        $news = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]);
    }
}