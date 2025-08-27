<?php

namespace App\Http\Controllers\NewsController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\News;
use App\Models\News\NewsComment;
use App\Models\News\NewsUserAction;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Hiển thị danh sách bình luận của bài viết
     */
    public function comments(News $news)
    {
        $comments = NewsComment::topLevel()
            ->where('news_id', $news->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'replies.user' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $news->likes = NewsUserAction::where('news_id', $news->id)->where('is_liked', 1)->count();
        foreach ($comments as $comment) {
            $comment->liked_by_user = false; // Tạm thời false, cần bảng comment_likes
        }
        return view('admin.news.comments.comments', compact('news', 'comments'));
        // Mục đích: Hiển thị danh sách bình luận phân cấp
        // Lợi ích: Không thay đổi logic hiển thị bình luận
    }

    /**
     * Thêm bình luận hoặc phản hồi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_id' => 'required|exists:news,id',
            'parent_id' => '_nullable|exists:news_comments,id',
            'content' => 'required|string',
        ], [
            'content.required' => 'Nội dung không được để trống.',
        ]);

        $comment = NewsComment::create([
            'news_id' => $validated['news_id'],
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'likes' => 0,
        ]);

        // Lấy lại danh sách bình luận cha (có replies) để render lại HTML
        $comments = NewsComment::topLevel()
            ->where('news_id', $validated['news_id'])
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'replies.user' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('admin.news.comments._comments_list', compact('comments'))->render();

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được thêm thành công!',
            'html' => $html,
            'comment_id' => $comment->id, // Trả về ID của bình luận mới để giữ vị trí cuộn
        ]);
        // Mục đích: Lưu bình luận mới và trả về HTML cùng ID bình luận
        // Lợi ích: Hỗ trợ giữ vị trí cuộn khi thêm bình luận
    }

    /**
     * Cập nhật nội dung bình luận
     */
    public function updateComment(Request $request, NewsComment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ], [
            'content.required' => 'Nội dung bình luận không được để trống.',
        ]);

        $comment->update(['content' => $validated['content']]);

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được cập nhật thành công!'
        ]);
    }

    /**
     * Xóa bình luận
     */
    public function deleteComment(NewsComment $comment)
    {
        $news_id = $comment->news_id;
        try {
            $comment->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa bình luận do còn bình luận con hoặc ràng buộc dữ liệu!'
            ]);
        }

        // Lấy lại danh sách bình luận cha (có replies) để render lại HTML
        $comments = NewsComment::topLevel()
            ->where('news_id', $news_id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'replies.user' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('admin.news.comments._comments_list', compact('comments'))->render();

        return response()->json([
            'success' => true,
            'message' => 'Bình luận đã được xóa thành công!',
            'html' => $html
        ]);
        // Mục đích: Cho phép xóa bình luận và cập nhật danh sách
        // Lợi ích: Không giới hạn quyền xóa
    }

    /**
     * Like bình luận
     */
    public function likeComment(Request $request, NewsComment $comment)
    {
        $sessionKey = 'liked_comment_' . $comment->id;
        $isLiked = session()->get($sessionKey, false);

        if ($isLiked) {
            if ($comment->likes > 0) $comment->decrement('likes');
            session()->forget($sessionKey);
            $liked = false;
        } else {
            $comment->increment('likes');
            session()->put($sessionKey, true);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'message' => $liked ? 'Đã thả tym!' : 'Đã bỏ tym!',
            'likes' => $comment->likes,
            'is_liked' => $liked
        ]);
    }

    /**
     * Lấy danh sách bình luận (dùng cho AJAX)
     */
    public function fetchComments(News $news, Request $request)
    {
        // Lấy tham số sắp xếp từ request
        $sort = $request->query('sort', 'all');
        
        $query = NewsComment::topLevel()
            ->where('news_id', $news->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'replies.user' => function ($query) {
                    $query->select('id', 'name');
                }
            ]);

        // Xử lý sắp xếp theo loại
        if ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            // Khi chọn "all", lấy tất cả bình luận mà không phân trang
            $query->orderBy('created_at', 'desc');
        }

        // Lấy dữ liệu: nếu là "all", lấy toàn bộ, nếu không thì phân trang
        $comments = $sort === 'all' ? $query->get() : $query->paginate(10);

        $html = view('admin.news.comments._comments_list', compact('comments'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
        // Mục đích: Lấy danh sách bình luận theo loại sắp xếp
        // Lợi ích: Hỗ trợ lấy tất cả bình luận hoặc phân trang, sắp xếp theo yêu cầu
    }
}
