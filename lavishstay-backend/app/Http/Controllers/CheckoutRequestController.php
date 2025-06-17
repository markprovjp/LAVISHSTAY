<?php

namespace App\Http\Controllers;

use App\Models\CheckoutRequest;
use App\Models\Booking;
use App\Models\CheckOutPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckoutRequestController extends Controller
{

    public function index(Request $request){
        $filter = $request->input('filter', 'all'); // Lấy tham số filter từ query string, mặc định là 'all'

        $query = CheckoutRequest::with('booking')->orderBy('created_at', 'desc');
        if ($filter !== 'all') {
            if (in_array($filter, ['approved', 'rejected'])) {
                $query->where('status', $filter);
            } elseif (in_array($filter, ['early', 'late'])) {
                $query->where('type', $filter);
            }
        }
        $requests = $query->get();

        $bookings = Booking::where('check_out_date', '>=', now()->toDateString())->get();
        return view('admin.room_modification.checkout_requests.index', compact('requests', 'bookings'));
    }

    public function create(){
        $bookings = Booking::where('check_out_date', '>=', now()->toDateString())->get(); // Chỉ lấy booking còn hiệu lực
        return view('admin.room_modification.checkout_requests.create', compact('bookings'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $booking = $request->has('booking_id') ? Booking::findOrFail($request->booking_id) : Booking::where('user_id', $user->id)->where('check_out_date', '>=', now()->toDateString())->first();

        if (!$booking) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy booking hiện tại'])->withInput();
        }

        $request->validate([
            'booking_id' => 'required|exists:booking,booking_id',
            'checkout_date' => 'required|date',
            'checkout_hour' => 'required|integer|between:1,12',
            'checkout_minute' => 'required|integer|min:0|max:59',
            'checkout_period' => 'required|in:AM,PM',
        ]);

        DB::beginTransaction();
        try {
            // Ghép thời gian từ form
            $hour = (int)$request->checkout_hour;
            $minute = (int)$request->checkout_minute;
            $period = $request->checkout_period;
            $date = Carbon::parse($request->checkout_date);

            // Chuyển đổi 12h sang 24h
            if ($period === 'PM' && $hour != 12) {
                $hour += 12;
            } elseif ($period === 'AM' && $hour == 12) {
                $hour = 0;
            }

            $requestedTime = $date->setHour($hour)->setMinute($minute)->setSecond(0);
            $checkOutDate = Carbon::parse($booking->check_out_date)->setTime(12, 0, 0);
            $diffInMinutes = $checkOutDate->diffInMinutes($requestedTime, false);

            // Khai báo $policies trước khi log
            $policies = CheckOutPolicy::where('is_active', 1)->orderBy('late_check_out_max_hours', 'asc')->get();
            \Log::info('DiffInMinutes: ' . $diffInMinutes, ['policies' => $policies->toArray()]);

            $fee_vnd = 0;
            $status = 'pending';
            $type = 'on_time'; // Mặc định là on_time

            if ($diffInMinutes < 0) {
                $type = 'early'; // Trả phòng sớm
                $isPolicyMatched = false;
                foreach ($policies as $policy) {
                    \Log::info('Policy check: early', ['max_hours' => $policy->early_check_out_max_hours, 'fee' => $policy->early_check_out_fee_vnd, 'diff' => $diffInMinutes]);
                    if ($diffInMinutes >= -$policy->early_check_out_max_hours * 60) {
                        $fee_vnd = $policy->early_check_out_fee_vnd ?? 0;
                        $isPolicyMatched = true;
                        \Log::info('Early policy matched', ['fee_vnd' => $fee_vnd]);
                        break;
                    }
                }
                if (!$isPolicyMatched) {
                    $status = 'rejected'; // Không khớp chính sách, tự động rejected
                    \Log::info('No early policy matched', ['diff' => $diffInMinutes]);
                }
            } elseif ($diffInMinutes > 0) {
                $type = 'late'; // Trả phòng muộn
                $isPolicyMatched = false;
                foreach ($policies as $policy) {
                    if ($diffInMinutes <= $policy->late_check_out_max_hours * 60) { // So sánh phút
                        $fee_vnd = $policy->late_check_out_fee_vnd ?? 0;
                        $isPolicyMatched = true;
                        break;
                    }
                }
                if (!$isPolicyMatched) {
                    $status = 'rejected'; // Không khớp chính sách, tự động rejected
                }
            }

            // Cập nhật status dựa trên type và fee_vnd
            if ($type === 'on_time' && $fee_vnd == 0) {
                $status = 'approved'; // Trả phòng đúng giờ, tự động phê duyệt
            } elseif ($type === 'early' && $fee_vnd == 0) {
                $status = 'approved'; // Trả phòng sớm không phí, tự động phê duyệt
            } elseif ($type === 'late' && $fee_vnd > 0) {
                $status = 'approved'; // Trả phòng muộn có phí, tự động phê duyệt nếu khớp chính sách
            } elseif ($type === 'early' && $fee_vnd > 0) {
                $status = 'approved'; // Trả phòng sớm có phí, tự động phê duyệt nếu khớp chính sách
            }

            $newRequest = CheckoutRequest::create([
                'booking_id' => $booking->booking_id,
                'requested_check_out_time' => $requestedTime,
                'fee_vnd' => $fee_vnd,
                'status' => $status,
                'type' => $type,
            ]);

            DB::commit();
            return redirect()->route('admin.check_out_requests')->with('success', 'Yêu cầu trả phòng đã được gửi thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in store: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Lỗi khi gửi yêu cầu: ' . $e->getMessage()])->withInput();
        }
    }

    // (Phương thức approve có thể giữ nếu cần phê duyệt thủ công sau này)
    public function approve(Request $request, $requestId)
    {
        $requestRecord = CheckoutRequest::findOrFail($requestId);
        $request->validate([
            'approve' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $requestRecord->status = $request->approve ? 'approved' : 'rejected';
            $requestRecord->save();

            if ($request->approve) {
                $booking = $requestRecord->booking;
                if ($booking) {
                    $newCheckOutDate = Carbon::parse($requestRecord->requested_check_out_time)->toDateString();
                    $booking->check_out_date = $newCheckOutDate;
                    $booking->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.check_out_requests')->with('success', 'Yêu cầu đã được ' . ($request->approve ? 'phê duyệt' : 'từ chối'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Lỗi khi xử lý yêu cầu: ' . $e->getMessage()]);
        }
    }
}