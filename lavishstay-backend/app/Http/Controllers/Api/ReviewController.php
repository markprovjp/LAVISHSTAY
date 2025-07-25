<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\RoomType;


class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function apiReviewsList(Request $request): JsonResponse
    {
        try {
            $reviews = Review::with(['booking', 'user', 'roomOption'])->where('status', 'approved')->get();

            $data = $reviews->map(function ($review) {
                return [
                    'id' => $review->review_id,
                    'booking_id' => $review->booking->booking_id ?? 'N/A',
                    'user_name' => $review->user->name ?? 'N/A',
                    'room_type' => $review->booking->room_type_id ?? 'N/A',
                    'title' => $review->title ?? 'Chưa có',
                    'rating' => $review->rating ?? 'N/A',
                    'comment' => $review->comment ?? 'Không có bình luận',
                    'review_date' => $review->review_date ? $review->review_date->format('d/m/Y') : 'Chưa có',
                    'status' => $review->status,
                    'helpful' => $review->helpful ?? 0,
                    'not_helpful' => $review->not_helpful ?? 0,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reviews: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi lấy danh sách đánh giá',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function apiReviewsDetail(string $id): JsonResponse{
        try {
            $review = Review::with(['booking', 'user', 'roomOption'])
                ->where('review_id', $id)
                ->firstOrFail();

            $data = [
                'id' => $review->review_id,
                'booking_id' => $review->booking->booking_id ?? 'N/A',
                'user_name' => $review->user->name ?? 'N/A',
                'room_type' => $review->booking->room_type_id ?? 'N/A',
                'title' => $review->title ?? 'Chưa có',
                'rating' => $review->rating ?? 'N/A',
                'comment' => $review->comment ?? 'Không có bình luận',
                'review_date' => $review->review_date ? $review->review_date->format('d/m/Y') : 'Chưa có',
                'status' => $review->status,
                'helpful' => $review->helpful ?? 0,
                'not_helpful' => $review->not_helpful ?? 0,
                'admin_reply_content' => $review->admin_reply_content ?? 'Chưa có',
                'admin_reply_date' => $review->admin_reply_date ? $review->admin_reply_date->format('d/m/Y') : 'Chưa có',
                'admin_name' => $review->admin_name ?? 'N/A',
                'score_cleanliness' => $review->score_cleanliness ?? 'N/A',
                'score_location' => $review->score_location ?? 'N/A',
                'score_facilities' => $review->score_facilities ?? 'N/A',
                'score_service' => $review->score_service ?? 'N/A',
                'score_value' => $review->score_value ?? 'N/A',
                'created_at' => $review->created_at ? $review->created_at->format('d/m/Y H:i:s') : null,
                'updated_at' => $review->updated_at ? $review->updated_at->format('d/m/Y H:i:s') : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function apiReviewsCreate(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'required|exists:booking,booking_id',
                'title' => 'nullable|string|max:255',
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'nullable|string',
                'review_date' => 'nullable|date',
                'admin_reply_content' => 'nullable|string',
                'admin_reply_date' => 'nullable|date',
                'admin_name' => 'nullable|string|max:255',
                'score_cleanliness' => 'nullable|numeric|min:1|max:5',
                'score_location' => 'nullable|numeric|min:1|max:5',
                'score_facilities' => 'nullable|numeric|min:1|max:5',
                'score_service' => 'nullable|numeric|min:1|max:5',
                'score_value' => 'nullable|numeric|min:1|max:5',
                'helpful' => 'nullable|integer|min:0',
                'not_helpful' => 'nullable|integer|min:0',
            ]);

            // Tạo review với các trường hợp lệ, lấy user_id gián tiếp từ booking nếu cần
            $review = Review::create(array_merge($validated, [
                'status' => 'pending',
                'review_date' => $validated['review_date'] ?? now(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được tạo thành công',
                'data' => $review,
            ], 201);
        } catch (\ValidationException $e) {
            Log::error('Validation error creating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi tạo đánh giá',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function apiReviewsUpdate(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'nullable|string',
                'review_date' => 'nullable|date',
                'admin_reply_content' => 'nullable|string',
                'admin_reply_date' => 'nullable|date',
                'admin_name' => 'nullable|string|max:255',
                'score_cleanliness' => 'nullable|numeric|min:1|max:5',
                'score_location' => 'nullable|numeric|min:1|max:5',
                'score_facilities' => 'nullable|numeric|min:1|max:5',
                'score_service' => 'nullable|numeric|min:1|max:5',
                'score_value' => 'nullable|numeric|min:1|max:5',
                'helpful' => 'nullable|integer|min:0',
                'not_helpful' => 'nullable|integer|min:0',
            ]);

            $review = Review::findOrFail($id);
            // Log the update attempt

            \Log::info('Updating review with ID: ' . $review);

            $review->fill($validated);
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được cập nhật thành công',
                'data' => $review,
            ]);
        
        } catch (\Exception $e) {
            Log::error('Error updating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi cập nhật đánh giá',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified review
     */
    public function apiReviewsDelete(string $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được xóa thành công',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi xóa đánh giá',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function apiReviewsApprove($id): JsonResponse
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đánh giá'], 404);
        }
        if ($review->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Chỉ được duyệt đánh giá ở trạng thái pending'], 400);
        }
        $review->status = 'approved';
        $review->save();
        return response()->json(['success' => true, 'message' => 'Đánh giá đã được duyệt', 'status' => $review->status]);
    }

    public function apiReviewsReject($id): JsonResponse
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đánh giá'], 404);
        }
        if ($review->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Chỉ được từ chối đánh giá ở trạng thái pending'], 400);
        }
        $review->status = 'rejected';
        $review->save();
        return response()->json(['success' => true, 'message' => 'Đánh giá đã bị từ chối', 'status' => $review->status]);
    }


    public function apiRoomTypeDetails($id): JsonResponse{
        try {
            $roomType = RoomType::findOrFail($id);

            // Chỉ lấy các review có status = 'approved'
            $approvedReviews = Review::whereHas('booking', function ($query) use ($id) {
                $query->where('room_type_id', $id);
            })
            ->where('status', 'approved')
            ->with(['booking', 'user', 'roomOption'])
            ->get();

            $reviewCount = $approvedReviews->count();
            $averageRating = $reviewCount > 0 ? $approvedReviews->avg('rating') : 0; // Tránh lỗi chia cho 0

            // Format chỉ các review approved
            $reviewsFormatted = $approvedReviews->map(function ($review) {
                return [
                    'id' => $review->review_id,
                    'booking_id' => $review->booking->booking_id ?? 'N/A',
                    'user_name' => $review->user->name ?? 'N/A',
                    'room_type' => $review->booking->room_type_id ?? 'N/A',
                    'title' => $review->title ?? 'Chưa có',
                    'rating' => $review->rating ?? 'N/A',
                    'comment' => $review->comment ?? 'Không có bình luận',
                    'review_date' => $review->review_date ? $review->review_date->format('d/m/Y') : 'Chưa có',
                    'status' => $review->status,
                    'helpful' => $review->helpful ?? 0,
                    'not_helpful' => $review->not_helpful ?? 0,
                    'admin_reply_content' => $review->admin_reply_content ?? 'Chưa có',
                    'admin_reply_date' => $review->admin_reply_date ? $review->admin_reply_date->format('d/m/Y') : 'Chưa có',
                    'admin_name' => $review->admin_name ?? 'N/A',
                    'score_cleanliness' => $review->score_cleanliness ?? 'N/A',
                    'score_location' => $review->score_location ?? 'N/A',
                    'score_facilities' => $review->score_facilities ?? 'N/A',
                    'score_service' => $review->score_service ?? 'N/A',
                    'score_value' => $review->score_value ?? 'N/A',
                    'created_at' => $review->created_at ? $review->created_at->format('d/m/Y H:i:s') : null,
                    'updated_at' => $review->updated_at ? $review->updated_at->format('d/m/Y H:i:s') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'room_type_id' => $roomType->room_type_id,
                'room_type_name' => $roomType->name,
                'review_count' => $reviewCount, // Chỉ tính approved reviews
                'average_rating' => number_format($averageRating, 2), // Chỉ tính approved reviews
                'reviews' => $reviewsFormatted, // Chỉ trả về approved reviews
            ]);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy chi tiết đánh giá theo loại phòng: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chi tiết loại phòng',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

}