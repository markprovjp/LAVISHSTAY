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
            ->where('status', $status)
            ->where('published_at', '<=', Carbon::now());

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

        // Get user actions if authenticated
        $userAction = $userId ? $news->getUserAction($userId) : null;

        $data = $news->toArray();
        $data['user_action'] = [
            'is_liked' => $userAction ? $userAction->is_liked : false,
            'is_bookmarked' => $userAction ? $userAction->is_bookmarked : false,
            'rating' => $userAction ? $userAction->rating : null,
        ];
        $data['stats'] = [
            'likes_count' => $news->getLikesCount(),
            'bookmarks_count' => $news->getBookmarksCount(),
            'comments_count' => $news->comments()->count(),
            'average_rating' => round($news->getAverageRating(), 1),
        ];

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
}