<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\NewsComment;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsCommentController extends Controller
{
    /**
     * Display a listing of comments for a specific news article
     */
    public function index(Request $request, $newsId)
    {
        $perPage = $request->query('per_page', 10);
        $userId = Auth::id();
        
        $comments = NewsComment::with(['user', 'replies.user', 'replies.replies.user'])
            ->where('news_id', $newsId)
            ->topLevel()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add user like status to each comment
        if ($userId) {
            $commentIds = $comments->pluck('id')->toArray();
            $userLikes = \App\Models\News\NewsCommentLike::where('user_id', $userId)
                ->whereIn('comment_id', $commentIds)
                ->pluck('comment_id')
                ->toArray();

            foreach ($comments as $comment) {
                $comment->is_liked = in_array($comment->id, $userLikes);
                
                // Also check replies
                if ($comment->replies) {
                    $replyIds = $comment->replies->pluck('id')->toArray();
                    $replyLikes = \App\Models\News\NewsCommentLike::where('user_id', $userId)
                        ->whereIn('comment_id', $replyIds)
                        ->pluck('comment_id')
                        ->toArray();
                    
                    foreach ($comment->replies as $reply) {
                        $reply->is_liked = in_array($reply->id, $replyLikes);
                    }
                }
            }
        } else {
            foreach ($comments as $comment) {
                $comment->is_liked = false;
                if ($comment->replies) {
                    foreach ($comment->replies as $reply) {
                        $reply->is_liked = false;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
            'total' => $comments->total(),
        ]);
    }

    /**
     * Store a newly created comment
     */
    public function store(Request $request, $newsId)
    {
        \Log::debug('NewsCommentController::store incoming Authorization:', [
            'auth_header' => $request->header('Authorization')
        ]);
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:news_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if news exists
        $news = News::find($newsId);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found'
            ], 404);
        }

        // Check if parent comment exists and belongs to the same news
        if ($request->parent_id) {
            $parentComment = NewsComment::where('id', $request->parent_id)
                ->where('news_id', $newsId)
                ->first();
            
            if (!$parentComment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent comment not found'
                ], 404);
            }
        }

        // Require authentication for commenting
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required to comment'
            ], 401);
        }

        $comment = NewsComment::create([
            'news_id' => $newsId,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            'likes' => 0,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * Display the specified comment
     */
    public function show($id)
    {
        $comment = NewsComment::with(['user', 'replies.user', 'news'])
            ->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $comment
        ]);
    }

    /**
     * Update the specified comment
     */
    public function update(Request $request, $id)
    {
        $comment = NewsComment::find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this comment'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'content' => $request->content
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified comment
     */
    public function destroy($id)
    {
        $comment = NewsComment::find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this comment'
            ], 403);
        }

        // Delete all replies first
        $comment->allReplies()->delete();
        
        // Delete the comment
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }

    /**
     * Like/Unlike a comment
     */
    public function toggleLike(Request $request, $newsId, $id)
    {
        \Log::debug('NewsCommentController::toggleLike incoming Authorization:', [
            'auth_header' => $request->header('Authorization')
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $comment = NewsComment::find($id);

        if (!$comment || $comment->news_id != $newsId) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        $userId = Auth::id();

        // Use news_user_actions.comment_likes JSON array to track which comment ids the user liked for this news
        $userAction = \App\Models\News\NewsUserAction::firstOrCreate(
            ['news_id' => $newsId, 'user_id' => $userId],
            ['is_liked' => false, 'is_bookmarked' => false, 'rating' => null, 'comment_likes' => null]
        );

        // Load existing likes array
        $commentLikes = [];
        if ($userAction->comment_likes) {
            try {
                $commentLikes = (array) json_decode($userAction->comment_likes, true) ?: [];
            } catch (\Exception $e) {
                $commentLikes = [];
            }
        }

        $isLiked = false;
        $message = '';

        if (in_array($id, $commentLikes)) {
            // Unlike
            $commentLikes = array_values(array_diff($commentLikes, [$id]));
            $comment->decrement('likes');
            $isLiked = false;
            $message = 'Comment unliked successfully';
        } else {
            // Like
            $commentLikes[] = $id;
            $comment->increment('likes');
            $isLiked = true;
            $message = 'Comment liked successfully';
        }

        // Persist updated comment_likes JSON
        $userAction->comment_likes = json_encode(array_values($commentLikes));
        $userAction->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $comment->likes
            ]
        ]);
    }

    /**
     * Get replies for a specific comment
     */
    public function getReplies($id)
    {
        $comment = NewsComment::find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        $replies = $comment->replies()->with('user')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $replies
        ]);
    }
}
