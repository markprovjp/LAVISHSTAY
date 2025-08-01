<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CancellationPolicy;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingCancellationController extends Controller
{

public function cancelBooking(Request $request, $bookingId)
{
    // Validate booking ID
    if (!is_numeric($bookingId)) {
        return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
    }

    // Find booking
    $booking = Booking::find($bookingId);
    if (!$booking) {
        return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
    }

    // Check booking status
    if ($booking->status === 'Cancelled' || $booking->status === 'Cancelled With Penalty') {
        return response()->json(['error' => 'Đặt phòng đã được hủy trước đó'], 400);
    }
    if ($booking->status !== 'Confirmed') {
        return response()->json(['error' => 'Chỉ có thể hủy đặt phòng ở trạng thái Đã thanh toán (Confirmed), trạng thái hiện tại: ' . $booking->status], 400);
    }

    // Calculate time difference
    $standardCheckInTime = Carbon::parse($booking->check_in_date)->addHours(14);
    $cancelTime = Carbon::now();
    $timeDiff = $cancelTime->diffInDays($standardCheckInTime, false); // Exact days

    // Find cancellation policy
    $cancellationPolicy = CancellationPolicy::where('free_cancellation_days', '<=', $timeDiff)
        ->whereNotNull('free_cancellation_days')
        ->where('is_active', true)
        ->orderBy('free_cancellation_days', 'desc')
        ->first()
        ?? CancellationPolicy::where('is_active', true)
        ->orderBy('penalty_days', 'desc')
        ->first();

    // Log policy for debugging
    \Log::info('Cancellation Policy:', [
        'policy' => $cancellationPolicy ? $cancellationPolicy->toArray() : null,
        'time_diff' => $timeDiff,
        'check_in_date' => $booking->check_in_date->format('Y-m-d H:i:s'),
        'cancel_time' => $cancelTime->format('Y-m-d H:i:s'),
    ]);

    if (!$cancellationPolicy) {
        return response()->json(['error' => 'Không tìm thấy chính sách hủy đặt phòng phù hợp'], 404);
    }

    // Fetch booking rooms with room details
    $bookingRooms = DB::table('booking_rooms')
        ->leftJoin('room', 'booking_rooms.room_id', '=', 'room.room_id')
        ->where('booking_rooms.booking_id', $booking->booking_id)
        ->select(
            'booking_rooms.*',
            'room.name as room_name',
            'room.description as room_description'
        )
        ->get();

    // Initialize room and room type
    $roomType = $booking->roomType()->first();
    $roomNames = $bookingRooms->isNotEmpty()
        ? $bookingRooms->pluck('room_name')->filter()->implode(', ')
        : null;

    // Fallback to booking->room_id if no booking_rooms records
    $room = null;
    if ($bookingRooms->isEmpty() && $booking->room_id) {
        $room = Room::where('room_id', $booking->room_id)->first();
        if ($room) {
            $roomNames = $room->name;
            $roomType = $roomType ?? $room->room_type()->first();
        } else {
            \Log::error('Room not found for room_id: ' . $booking->room_id);
        }
    }

    // Check if room type exists
    if (!$roomType) {
        \Log::error('Room type not found for booking_id: ' . $booking->booking_id);
        return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
    }

    // Attempt to fetch hotel via Room model relationship (if exists)
    $hotel = $room ? $room->hotel()->first() : null;

    // Log relations for debugging
    \Log::info('Booking Relations:', [
        'booking_id' => $booking->booking_id,
        'booking_rooms' => $bookingRooms->toArray(),
        'room_names' => $roomNames,
        'room_type' => $roomType ? $roomType->toArray() : null,
        'hotel' => $hotel ? $hotel->toArray() : null,
    ]);

    // Common booking information
    $bookingInfo = [
        'booking_code' => $booking->booking_code,
        'room_name' => $roomNames ?? 'Không xác định',
        'check_in_date' => $booking->check_in_date,
        'check_out_date' => $booking->check_out_date,
        'total_price' => $booking->total_price_vnd,
    ];

    $roomInfo = [
        'room_name' => $roomNames ?? 'Không xác định',
        'room_type' => $roomType->name ?? 'Không xác định',
        'room_description' => $bookingRooms->isNotEmpty() ? ($bookingRooms->first()->room_description ?? 'Không có mô tả') : ($room ? ($room->description ?? 'Không có mô tả') : 'Không có mô tả'),
    ];

    $hotelInfo = [
        'hotel_name' => $hotel ? ($hotel->name ?? 'Không xác định') : 'Không xác định',
        'hotel_address' => $hotel ? ($hotel->address ?? 'Không xác định') : 'Không xác định',
        'hotel_phone' => $hotel ? ($hotel->phone ?? 'Không xác định') : 'Không xác định',
    ];

    // Get policy name safely
    $policyName = $cancellationPolicy->name ?? 'Chính sách mặc định';
    $pilicyId   = $cancellationPolicy->policy_id ?? null;
    // Check if cancellation is free
    if ($timeDiff >= ($cancellationPolicy->free_cancellation_days ?? PHP_INT_MAX)) {
        // Free cancellation
        DB::transaction(function () use ($booking) {
            $booking->status = 'Cancelled';
            $booking->save();
        });

        return response()->json([
            'message' => 'Đặt phòng đã được hủy miễn phí',
            'reason' => "Bạn hủy đặt phòng vào ngày {$cancelTime->format('Y-m-d')}, cách ngày nhận phòng ({$booking->check_in_date->format('Y-m-d')} 14:00) " . round($timeDiff, 2) . " ngày, thỏa mãn chính sách hủy miễn phí {$policyName} (trước {$cancellationPolicy->free_cancellation_days} ngày).",
            'formula' => 'Không áp dụng',
            'policy' => $policyName,
            'policy_id' => $pilicyId,
            'penalty' => 0,
            'penalty_type' => 'Không áp dụng',
            'penalty_percentage' => 0,
            'penalty_fixed_amount' => 0,
            'booking_info' => $bookingInfo,
            'room_info' => $roomInfo,
            'hotel_info' => $hotelInfo,
        ]);
    }

    // Non-free cancellation
    $penaltyType = $cancellationPolicy->penalty_type ?? 'percentage';
    $penaltyPercentage = $cancellationPolicy->penalty_percentage ?? 0;
    $penaltyFixedAmount = $cancellationPolicy->penalty_fixed_amount_vnd ?? 0;
    $penaltyAmount = $penaltyType === 'percentage'
        ? $booking->total_price_vnd * $penaltyPercentage / 100
        : $penaltyFixedAmount;

    // Ensure penalty is applied correctly
    if ($penaltyAmount === 0 && $penaltyFixedAmount > 0) {
        $penaltyAmount = $penaltyFixedAmount;
        $penaltyType = 'fixed';
    }

    // Update status to Cancelled With Penalty
    DB::transaction(function () use ($booking, $penaltyAmount) {
        $booking->status = $penaltyAmount > 0 ? 'Cancelled With Penalty' : 'Cancelled';
        $booking->save();
    });

    return response()->json([
        'message' => $penaltyAmount > 0
            ? "Đặt phòng đã được hủy với phí. Số tiền phạt là {$penaltyAmount} VND"
            : 'Đặt phòng đã được hủy miễn phí',
        'reason' => $penaltyAmount > 0
            ? "Bạn hủy đặt phòng vào ngày {$cancelTime->format('Y-m-d')}, cách ngày nhận phòng ({$booking->check_in_date->format('Y-m-d')} 14:00) " . round($timeDiff, 2) . " ngày, vi phạm chính sách hủy {$policyName}. Chính sách này yêu cầu hủy trước {$cancellationPolicy->penalty_days} ngày, nếu không sẽ bị phạt " . ($penaltyType === 'percentage' ? "{$penaltyPercentage}% trên tổng giá" : "{$penaltyFixedAmount} VND cố định")
            : "Bạn hủy đặt phòng vào ngày {$cancelTime->format('Y-m-d')}, cách ngày nhận phòng ({$booking->check_in_date->format('Y-m-d')} 14:00) " . round($timeDiff, 2) . " ngày, thỏa mãn chính sách hủy miễn phí {$policyName}.",
        'formula' => $penaltyAmount > 0
            ? ($penaltyType === 'percentage' ? 'Số tiền phạt = Tổng giá * Tỷ lệ phạt' : 'Số tiền phạt = Số tiền cố định')
            : 'Không áp dụng',
        'policy' => $policyName,
        'policy_id' => $pilicyId,
        'penalty' => $penaltyAmount,
        'penalty_type' => $penaltyAmount > 0 ? $penaltyType : 'Không áp dụng',
        'penalty_percentage' => $penaltyType === 'percentage' ? $penaltyPercentage : 0,
        'penalty_fixed_amount' => $penaltyType === 'fixed' ? $penaltyFixedAmount : 0,
        'booking_info' => $bookingInfo,
        'room_info' => $roomInfo,
        'hotel_info' => $hotelInfo,
    ]);
}
}
