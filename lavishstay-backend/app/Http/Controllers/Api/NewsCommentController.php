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
        
        $comments = NewsComment::with(['user', 'replies.user', 'replies.replies.user'])
            ->where('news_id', $newsId)
            ->topLevel()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

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

        $comment = NewsComment::create([
            'news_id' => $newsId,
            'user_id' => Auth::id() ?? 1, // Default to user 1 if not authenticated
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
        $comment = NewsComment::find($id);

        if (!$comment || $comment->news_id != $newsId) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        // For simplicity keep existing behavior: if query param 'action' provided use it,
        // otherwise default to 'like' (increment). Real toggle by user not implemented here.
        $action = $request->query('action', 'like'); // 'like' or 'unlike'

        if ($action === 'like') {
            $comment->increment('likes');
            $isLiked = true;
            $message = 'Comment liked successfully';
        } else {
            $comment->decrement('likes');
            $isLiked = false;
            $message = 'Comment unliked successfully';
        }

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
