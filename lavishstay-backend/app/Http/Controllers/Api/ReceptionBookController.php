<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Models\Representative;
use App\Models\BookingRoom;
use App\Models\Payment;
use Illuminate\Support\Str;
use DB;

class ReceptionBookController extends Controller
{
    // Tạo booking mới cho lễ tân
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate và lấy dữ liệu
            $data = $request->all();
            $rooms = $data['rooms'] ?? [];
            $representatives = $data['representatives'] ?? [];
            $dateRange = $data['dateRange'] ?? [];
            $subtotal = $data['subtotal'] ?? 0;
if (empty($dateRange[0]) || empty($dateRange[1])) {
    return response()->json(['success' => false, 'message' => 'Thiếu ngày nhận/trả phòng!'], 422);
}
            // Tạo booking
            $booking = Booking::create([
 'booking_id' => Str::upper('RCPT' . rand(100000, 999999)),
    'check_in_date' => $dateRange[0],
    'check_out_date' => $dateRange[1],
                'total_price_vnd' => $subtotal,
                'guest_count' => count($rooms),
                'status' => 'pending',
                'guest_name' => $representatives[array_key_first($representatives)]['fullName'] ?? null,
                'guest_email' => $representatives[array_key_first($representatives)]['email'] ?? null,
                'guest_phone' => $representatives[array_key_first($representatives)]['phoneNumber'] ?? null,
                // Thêm các trường khác nếu cần
            ]);

            // Lưu từng phòng vào bảng booking_rooms (nếu có)
            foreach ($rooms as $room) {
                $bookingRoom = BookingRoom::create([
                    'booking_id' => $booking->booking_id,
                    'room_id' => $room['id'],
                    'price_per_night' => $room['room_type']['adjusted_price'] ?? 0,
                    'nights' => $room['nights'] ?? 1,
                    'total_price' => $room['totalPrice'] ?? 0,
                    'check_in_date' => $room['checkIn'] ?? null,
                    'check_out_date' => $room['checkOut'] ?? null,
                ]);
                // Lưu đại diện cho từng phòng (nếu có)
                if (isset($representatives[$room['id']])) {
                    Representative::create([
                        'booking_id' => $booking->booking_id,
                        'room_id' => $room['id'],
                        'full_name' => $representatives[$room['id']]['fullName'],
                        'phone_number' => $representatives[$room['id']]['phoneNumber'],
                        'email' => $representatives[$room['id']]['email'],
                        'id_card' => $representatives[$room['id']]['idCard'],
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'booking_code' => $booking->booking_id,
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy chi tiết booking
    public function detail($booking_id)
    {
        $booking = Booking::with(['rooms', 'representatives'])->where('booking_id', $booking_id)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy booking'], 404);
        }
        return response()->json(['success' => true, 'booking' => $booking]);
    }

    // Kiểm tra trạng thái thanh toán
    public function paymentStatus($booking_id)
    {
        $payment = Payment::where('booking_id', $booking_id)->orderByDesc('created_at')->first();
        $status = $payment ? $payment->status : 'pending';
        return response()->json(['success' => true, 'payment_status' => $status]);
    }

    // Lấy danh sách booking theo ngày hoặc trạng thái
    public function list(Request $request)
    {
        $query = Booking::query();
        if ($request->has('date')) {
            $query->whereDate('check_in_date', $request->input('date'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        $bookings = $query->with(['rooms', 'representatives'])->orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'bookings' => $bookings]);
    }
}