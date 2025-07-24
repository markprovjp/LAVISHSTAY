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
            // Debug log
            \Log::info('Reception booking data received:', $request->all());
            
            // Validate và lấy dữ liệu
            $data = $request->all();
            $rooms = $data['rooms'] ?? [];
            $representatives = $data['representatives'] ?? [];
            $dateRange = $data['dateRange'] ?? [];
            $subtotal = $data['subtotal'] ?? 0;
            
            if (empty($dateRange[0]) || empty($dateRange[1])) {
                return response()->json(['success' => false, 'message' => 'Thiếu ngày nhận/trả phòng!'], 422);
            }
            
            // Convert ISO date strings to MySQL date format
            $checkInDate = date('Y-m-d', strtotime($dateRange[0]));
            $checkOutDate = date('Y-m-d', strtotime($dateRange[1]));
            
            if (empty($rooms)) {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin phòng!'], 422);
            }
            
            if (empty($representatives)) {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin đại diện!'], 422);
            }
            // Tạo booking code đúng format LAVISHSTAY_xxxxx
            $bookingCode = 'LAVISHSTAY_' . rand(100000, 999999);
            
            // Tạo booking chính (lấy thông tin người đại diện đầu tiên)
            $firstRepresentative = reset($representatives);
            
            $booking = Booking::create([
                'booking_code' => $bookingCode, // Lưu booking code vào cột booking_code
                'user_id' => null, // Reception booking không cần user_id
                'option_id' => null, // Không cần dùng option_id nữa
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'total_price_vnd' => $subtotal,
                'guest_count' => count($rooms),
                'status' => 'pending',
                'guest_name' => $firstRepresentative['fullName'] ?? 'Reception Guest',
                'guest_email' => $firstRepresentative['email'] ?? null,
                'guest_phone' => $firstRepresentative['phoneNumber'] ?? null,
                'quantity' => count($rooms),
                'room_id' => $rooms[0]['id'] ?? null, // Lấy room đầu tiên làm reference
            ]);

            // Lưu từng phòng vào bảng booking_rooms và representatives
            foreach ($rooms as $room) {
                // Tạo representative trước (nếu có)
                $representativeId = null;
                if (isset($representatives[$room['id']])) {
                    $representative = Representative::create([
                        'booking_id' => $booking->booking_id, // Sử dụng booking ID (int)
                        'booking_code' => $bookingCode, // Sử dụng booking code (string)
                        'room_id' => $room['id'],   
                        'full_name' => $representatives[$room['id']]['fullName'],
                        'phone_number' => $representatives[$room['id']]['phoneNumber'],
                        'email' => $representatives[$room['id']]['email'],
                        'id_card' => $representatives[$room['id']]['idCard'],
                    ]);
                    $representativeId = $representative->id;
                }
                
                // Tạo booking_room với representative_id
                $bookingRoom = BookingRoom::create([
                    'booking_id' => $booking->booking_id, // Sử dụng booking ID (int)
                    'booking_code' => $bookingCode, // Sử dụng booking code (string)
                    'room_id' => $room['id'],
                    'representative_id' => $representativeId,
                    'price_per_night' => $room['room_type']['adjusted_price'] ?? 0,
                    'nights' => $room['nights'] ?? 1,
                    'total_price' => $room['totalPrice'] ?? 0,
                    'check_in_date' => isset($room['checkIn']) ? date('Y-m-d', strtotime($room['checkIn'])) : $checkInDate,
                    'check_out_date' => isset($room['checkOut']) ? date('Y-m-d', strtotime($room['checkOut'])) : $checkOutDate,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'booking_code' => $bookingCode, 
                'payment_content' => $bookingCode, // Đã có format LAVISHSTAY_ rồi
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy chi tiết booking
    public function detail($booking_code)
    {
        $booking = Booking::with(['rooms', 'representatives'])->where('booking_code', $booking_code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy booking'], 404);
        }
        return response()->json(['success' => true, 'booking' => $booking]);
    }

    // Kiểm tra trạng thái thanh toán
    public function paymentStatus($booking_code)
    {
        // Tìm booking theo booking_code
        $booking = Booking::where('booking_code', $booking_code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy booking'], 404);
        }
        
        // Kiểm tra payment theo booking code trong Payment model
        $payment = Payment::where('booking_code', $booking_code)->orderByDesc('created_at')->first();
        $status = $payment ? $payment->payment_status : 'pending';
        
        return response()->json([
            'success' => true, 
            'payment_status' => $status,
            'booking_status' => $booking->status
        ]);
    }

    // Lấy danh sách booking theo ngày hoặc trạng thái
    public function list(Request $request)
    {
        $query = Booking::query()->whereNotNull('booking_code'); // Chỉ lấy booking có booking code
        if ($request->has('date')) {
            $query->whereDate('check_in_date', $request->input('date'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        $bookings = $query->with(['rooms', 'representatives'])->orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    // Lấy thông tin booking cho payment
    public function bookingInfo($booking_code)
    {
        $booking = Booking::with(['rooms.room.roomType', 'representatives'])->where('booking_code', $booking_code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy booking'], 404);
        }
        
        // Format dữ liệu cho frontend
        $rooms = [];
        $representatives = [];
        
        foreach ($booking->rooms as $bookingRoom) {
            $rooms[] = [
                'id' => $bookingRoom->room_id,
                'name' => $bookingRoom->room->name ?? 'N/A',
                'room_type' => [
                    'name' => $bookingRoom->room->roomType->name ?? 'N/A',
                    'adjusted_price' => $bookingRoom->price_per_night
                ],
                'checkIn' => $bookingRoom->check_in_date,
                'checkOut' => $bookingRoom->check_out_date,
                'nights' => $bookingRoom->nights,
                'totalPrice' => $bookingRoom->total_price
            ];
        }
        
        foreach ($booking->representatives as $rep) {
            $representatives[$rep->room_id] = [
                'fullName' => $rep->full_name,
                'phoneNumber' => $rep->phone_number,
                'email' => $rep->email,
                'idCard' => $rep->id_card
            ];
        }
        
        return response()->json([
            'success' => true,
            'booking' => [
                'booking_id' => $booking->booking_code, // Trả về booking code
                'check_in' => $booking->check_in_date,
                'check_out' => $booking->check_out_date,
                'total_amount' => $booking->total_price_vnd,
                'status' => $booking->status,
                'rooms' => $rooms,
                'representatives' => $representatives,
                'payment_content' => $booking->booking_code, // Đã có format LAVISHSTAY_ rồi
                'countdown' => 900 // 15 phút
            ]
        ]);
    }
}