<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    // Danh sách review + thống kê
    public function index()
    {
        $reviews = Review::with(['booking.user', 'booking.roomOption', 'reviewMedia'])
            ->paginate(20);

        $stats = Review::select(
                'room_option.name as room_option_name',
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->join('booking', 'reviews.booking_id', '=', 'booking.booking_id')
            ->join('room_option', 'booking.option_id', '=', 'room_option.option_id')
            ->where('reviews.status', 'approved')
            ->groupBy('room_option.name')
            ->get();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    // Xóa review
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);

            // Xóa file media
            $mediaFiles = $review->reviewMedia->pluck('file_url');
            Storage::disk('public')->delete($mediaFiles->toArray());

            // Xóa record media + review
            $review->reviewMedia()->delete();
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được xóa thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Không thể xóa đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle trạng thái (pending <-> approved)
    public function toggleStatus($id)
    {
        $review = Review::findOrFail($id);

        $status = $review->status === 'approved' ? 'pending' : 'approved';
        $review->update(['status' => $status]);

        return redirect()
            ->route('admin.reviews')
            ->with('success', 'Trạng thái đã được cập nhật thành ' . ucfirst($status) . '.');
    }

    // Duyệt hoặc từ chối review
    public function approve(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            return response()->json([
                'success' => false,
                'error' => 'Trạng thái không hợp lệ!'
            ]);
        }

        $review->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật thành ' . ucfirst($status)
        ]);
    }

    // Ghi chú của admin (thay cho reply cũ)
    public function note(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'required|string',
        ]);

        $review->update([
            'admin_note' => $validated['admin_note'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ghi chú đã được lưu thành công.'
        ]);
    }
}
