<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ReviewMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PublicReviewController extends Controller
{
    /**
     * Check if booking is eligible for review
     */
    public function checkEligibility($bookingId)
    {
        try {
            $booking = Booking::find($bookingId);
            Log::info('Checking review eligibility for booking', ['booking_id' => $bookingId]);
            if (!$booking) {
                return response()->json([
                    'eligible' => false,
                    'reason' => 'Booking không tồn tại'
                ], 404);
            }

            // Check if already reviewed
            $existingReview = Review::where('booking_id', $bookingId)->first();
            if ($existingReview) {
                return response()->json([
                    'eligible' => false,
                    'reason' => 'Booking này đã được đánh giá'
                ], 400);
            }

            // Check if checked out
            $isCheckedOut = $booking->status === 'Completed' || 
                           ($booking->check_out_date && Carbon::parse($booking->check_out_date)->isPast());

            if (!$isCheckedOut) {
                return response()->json([
                    'eligible' => false,
                    'reason' => 'Chỉ có thể đánh giá sau khi hoàn tất kỳ nghỉ'
                ], 400);
            }
            // Log::info('Booking is eligible for review', ['booking_id' => $booking->booking_id]);
            return response()->json([
                'eligible' => true,
                'booking_summary' => [
                    'id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'room_name' => 'Standard Room', // Default since no room relationship
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'total_amount' => $booking->total_price_vnd,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'eligible' => false,
                'reason' => 'Lỗi hệ thống'
            ], 500);
        }
    }

    /**
     * Get existing review for booking
     */
    public function getReview($bookingId)
    {
        try {
            $review = Review::with('reviewMedia')
                ->where('booking_id', $bookingId)
                ->first();

            if (!$review) {
                return response()->json(['review' => null]);
            }

            return response()->json([
                'review' => [
                    'review_id' => $review->review_id,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'comment' => $review->comment,
                    'detailed_scores' => $review->detailed_scores,
                    'pros' => $review->pros,
                    'cons' => $review->cons,
                    'travel_type' => $review->travel_type,
                    'review_date' => $review->review_date,
                    'status' => $review->status,
                    'media' => $review->reviewMedia->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'file_url' => $media->file_url,
                            'file_type' => $media->file_type,
                            'meta' => $media->meta,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi hệ thống'], 500);
        }
    }

    /**
     * Submit review for booking
     */
    public function submitReview(Request $request, $bookingId)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'rating' => 'required|numeric|min:0|max:5',
                'title' => 'required|string|max:255',
                'comment' => 'required|string',
                'detailed_scores' => 'nullable|array',
                'detailed_scores.*' => 'numeric|min:0|max:5',
                'pros' => 'nullable|string',
                'cons' => 'nullable|string',
                'travel_type' => 'required|in:business,couple,solo,family_young,group',
                'review_date' => 'required|date',
                'media_urls' => 'nullable|array|max:5',
                    // allow either absolute URLs or relative paths returned by older upload handlers
                    'media_urls.*' => 'string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Dữ liệu không hợp lệ',
                    'details' => $validator->errors()
                ], 422);
            }

            // Check eligibility again
            $eligibility = $this->checkEligibility($bookingId);
            if ($eligibility->getStatusCode() !== 200) {
                return $eligibility;
            }

            DB::beginTransaction();

            // Create review
            $review = Review::create([
                'booking_id' => $bookingId,
                'rating' => $request->rating,
                'title' => strip_tags($request->title),
                'comment' => strip_tags($request->comment),
                'detailed_scores' => $request->detailed_scores,
                'pros' => $request->pros ? strip_tags($request->pros) : null,
                'cons' => $request->cons ? strip_tags($request->cons) : null,
                'travel_type' => $request->travel_type,
                'review_date' => $request->review_date,
                'status' => 'pending'
            ]);

            // Save media if provided. Accept absolute URLs or relative paths (e.g. /storage/...) and
            // normalize them to absolute URLs before storing.
            if ($request->media_urls && is_array($request->media_urls)) {
                foreach ($request->media_urls as $mediaUrl) {
                    $original = $mediaUrl;

                    // If not a valid absolute URL, treat as relative and prefix with app url
                    if (!filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
                        // ensure it has a leading slash
                        if (strpos($mediaUrl, '/') !== 0) {
                            $mediaUrl = '/' . ltrim($mediaUrl, '/');
                        }
                        $mediaUrl = url($mediaUrl);
                    }

                    $fileType = $this->detectFileType($mediaUrl);

                    ReviewMedia::create([
                        'review_id' => $review->review_id,
                        'file_url' => $mediaUrl,
                        'file_type' => $fileType,
                        'meta' => json_encode(['original' => $original, 'uploaded_at' => now()])
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'review_id' => $review->review_id,
                'status' => $review->status,
                'message' => 'Đánh giá đã được gửi thành công và đang chờ phê duyệt'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Lỗi hệ thống khi lưu đánh giá'
            ], 500);
        }
    }

    /**
     * Upload media files
     */
    public function uploadMedia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array|max:5',
                'files.*' => 'file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:51200' // 50MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'File không hợp lệ',
                    'details' => $validator->errors()
                ], 422);
            }

            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                $path = $file->store('review-media', 'public');
                // Storage::url may return a relative path (e.g. /storage/...), convert to absolute URL
                $relativeUrl = Storage::url($path);
                $url = url($relativeUrl);

                $uploadedFiles[] = [
                    'url' => $url,
                    'type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video',
                    'meta' => [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]
                ];
            }

            return response()->json([
                'files' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi tải file'
            ], 500);
        }
    }

    /**
     * Detect file type from URL
     */
    private function detectFileType($url)
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
        
        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        }
        
        return 'image'; // default
    }
}
