<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\NewsUserAction;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsUserActionController extends Controller
{
    /**
     * Get user actions for a specific news article
     */
    public function show($newsId, $userId = null)
    {
    // If no explicit userId provided, use authenticated user if present.
    // Do NOT default to user id 1 (could expose another user's data).
    $userId = $userId ?? Auth::id();

        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $userAction = NewsUserAction::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        $data = [
            'is_liked' => $userAction ? $userAction->is_liked : false,
            'is_bookmarked' => $userAction ? $userAction->is_bookmarked : false,
            'rating' => $userAction ? $userAction->rating : null,
            'likes_count' => $news->getLikesCount(),
            'bookmarks_count' => $news->getBookmarksCount(),
            'average_rating' => round($news->getAverageRating(), 1),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Toggle like for a news article
     */
    public function toggleLike(Request $request, $newsId)
    {
        \Log::debug('NewsUserActionController::toggleLike incoming Authorization:', [
            'auth_header' => $request->header('Authorization')
        ]);
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();

        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $userAction = NewsUserAction::firstOrCreate(
            ['news_id' => $newsId, 'user_id' => $userId],
            ['is_liked' => false, 'is_bookmarked' => false, 'rating' => null]
        );

        $userAction->is_liked = !$userAction->is_liked;
        $userAction->save();

        $likesCount = NewsUserAction::where('news_id', $newsId)->where('is_liked', true)->count();
        
        return response()->json([
            'success' => true,
            'message' => $userAction->is_liked ? 'News liked successfully' : 'News unliked successfully',
            'data' => [
                'is_liked' => $userAction->is_liked,
                'likes_count' => $likesCount
            ]
        ]);
    }

    /**
     * Toggle bookmark for a news article
     */
    public function toggleBookmark(Request $request, $newsId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();

        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $userAction = NewsUserAction::firstOrCreate(
            ['news_id' => $newsId, 'user_id' => $userId],
            ['is_liked' => false, 'is_bookmarked' => false, 'rating' => null]
        );

        $userAction->is_bookmarked = !$userAction->is_bookmarked;
        $userAction->save();

        $bookmarksCount = NewsUserAction::where('news_id', $newsId)->where('is_bookmarked', true)->count();
        
        return response()->json([
            'success' => true,
            'message' => $userAction->is_bookmarked ? 'News bookmarked successfully' : 'News unbookmarked successfully',
            'data' => [
                'is_bookmarked' => $userAction->is_bookmarked,
                'bookmarks_count' => $bookmarksCount
            ]
        ]);
    }

    /**
     * Rate a news article
     */
    public function rate(Request $request, $newsId)
    {
        \Log::debug('NewsUserActionController::rate payload', ['payload' => $request->all(), 'headers' => $request->header('Authorization')]);
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            \Log::debug('NewsUserActionController::rate validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = Auth::id();

        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $userAction = NewsUserAction::updateOrCreate(
            ['news_id' => $newsId, 'user_id' => $userId],
            ['rating' => $request->rating]
        );

        $averageRating = NewsUserAction::where('news_id', $newsId)->whereNotNull('rating')->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 1) : 0;
        
        return response()->json([
            'success' => true,
            'message' => 'News rated successfully',
            'data' => [
                'rating' => $userAction->rating,
                'average_rating' => $averageRating
            ]
        ]);
    }

    /**
     * Remove rating from a news article
     */
    public function removeRating(Request $request, $newsId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();

        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $userAction = NewsUserAction::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        if ($userAction) {
            $userAction->rating = null;
            $userAction->save();
        }

        $averageRating = round($news->getAverageRating(), 1);
        
        return response()->json([
            'success' => true,
            'message' => 'Rating removed successfully',
            'data' => [
                'rating' => null,
                'average_rating' => $averageRating
            ]
        ]);
    }

    /**
     * Get user's liked news articles
     */
    public function getLikedNews(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();
        $perPage = $request->query('per_page', 10);

        $likedNews = News::whereHas('userActions', function($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_liked', 1);
        })
        ->with(['category', 'author', 'thumbnail'])
        ->published()
        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $likedNews->items(),
            'current_page' => $likedNews->currentPage(),
            'last_page' => $likedNews->lastPage(),
            'total' => $likedNews->total(),
        ]);
    }

    /**
     * Get user's bookmarked news articles
     */
    public function getBookmarkedNews(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();
        $perPage = $request->query('per_page', 10);

        $bookmarkedNews = News::whereHas('userActions', function($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_bookmarked', 1);
        })
        ->with(['category', 'author', 'thumbnail'])
        ->published()
        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookmarkedNews->items(),
            'current_page' => $bookmarkedNews->currentPage(),
            'last_page' => $bookmarkedNews->lastPage(),
            'total' => $bookmarkedNews->total(),
        ]);
    }

    /**
     * Get statistics for a news article
     */
    public function getStats($newsId)
    {
        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        $stats = [
            'views' => $news->views,
            'likes_count' => $news->getLikesCount(),
            'bookmarks_count' => $news->getBookmarksCount(),
            'comments_count' => $news->comments()->count(),
            'average_rating' => round($news->getAverageRating(), 1),
            'ratings_count' => $news->userActions()->whereNotNull('rating')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
