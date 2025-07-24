<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::with(['booking', 'user'])->get();

        // Tính toán thống kê chỉ cho reviews có status = 'approved'
        $stats = Review::select('room_types.name as room_type_name', \DB::raw('COUNT(*) as total_reviews, AVG(reviews.rating) as average_rating'))
            ->join('booking', 'reviews.booking_id', '=', 'booking.booking_id')
            ->join('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
            ->where('reviews.status', 'approved') // Lọc chỉ approved reviews
            ->groupBy('room_types.name')
            ->get();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function destroy($id){
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Không thể xóa đánh giá: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id){
        $review = Review::findOrFail($id);
        $status = $review->status === 'pending' ? 'approved' : 'rejected';
        $review->update(['status' => $status]);

        return redirect()->route('admin.reviews')->with('success', 'Trạng thái đã được cập nhật thành ' . ucfirst($status) . '.');
    }

    public function approve(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['approved', 'rejected'])) {
            return response()->json(['success' => false, 'error' => 'Trạng thái không hợp lệ!']);
        }

        $review->status = $status;
        $review->save();

        return response()->json(['success' => true, 'message' => 'Trạng thái đã được cập nhật thành ' . ucfirst($status)]);
    }
}