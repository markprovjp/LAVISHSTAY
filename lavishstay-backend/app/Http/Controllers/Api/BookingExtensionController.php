<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CancellationPolicy;
use App\Models\ExtensionPolicy;
use App\Models\ExtensionRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingExtensionController extends Controller
{
public function extendBooking(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingController@extendBooking START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Request data: ', $request->all());
            Log::info('Query parameters: ', $request->query());

            // Validate input
            $validated = $request->validate([
                'new_check_out_date' => 'required|date',
            ]);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                Log::error('Invalid booking ID', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            Log::info('Booking details:', [
                'booking_id' => $booking->booking_id,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'status' => $booking->status,
            ]);

            // Check booking status
            if (in_array($booking->status, ['Cancelled', 'Cancelled With Penalty', 'Completed'])) {
                Log::error('Booking cannot be extended due to status', ['status' => $booking->status]);
                return response()->json(['error' => 'Không thể gia hạn đặt phòng đã hủy hoặc đã hoàn thành'], 400);
            }
            if ($booking->status !== 'Confirmed') {
                Log::error('Booking status not Confirmed', ['status' => $booking->status]);
                return response()->json(['error' => 'Chỉ có thể gia hạn đặt phòng ở trạng thái Đã xác nhận (Confirmed), trạng thái hiện tại: ' . $booking->status], 400);
            }

            // Check new check-out date
            $newCheckOutDate = Carbon::parse($validated['new_check_out_date'])->startOfDay();
            $currentCheckOutDate = Carbon::parse($booking->check_out_date)->startOfDay();
            Log::info('Date comparison:', [
                'new_check_out_date' => $newCheckOutDate->toDateTimeString(),
                'current_check_out_date' => $currentCheckOutDate->toDateTimeString(),
                'is_lte' => $newCheckOutDate->lte($currentCheckOutDate),
            ]);
            if ($newCheckOutDate->lte($currentCheckOutDate)) {
                return response()->json(['error' => 'Ngày trả phòng mới phải sau ngày trả phòng hiện tại'], 400);
            }

            // Calculate extension days
            $extensionDays = $newCheckOutDate->diffInDays($currentCheckOutDate, true);
            $currentTime = Carbon::now()->startOfDay();
            $timeDiff = $currentTime->diffInDays($currentCheckOutDate, false);
            Log::info('Time diff calculation:', [
                'current_time' => $currentTime->toDateTimeString(),
                'current_check_out_date' => $currentCheckOutDate->toDateTimeString(),
                'time_diff' => $timeDiff,
                'extension_days' => $extensionDays,
            ]);

            // Find extension policy
            $isHoliday = DB::table('holidays')->where('start_date', $newCheckOutDate->format('Y-m-d'))->exists();
            $isWeekend = in_array($newCheckOutDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            $extensionPolicyQuery = ExtensionPolicy::where('is_active', true)
                ->where(function ($query) use ($isHoliday, $isWeekend, $timeDiff) {
                    $query->where(function ($q) use ($isHoliday) {
                        $q->where('applies_to_holiday', $isHoliday)->orWhere('applies_to_holiday', 0);
                    })
                    ->where(function ($q) use ($isWeekend) {
                        $q->where('applies_to_weekend', $isWeekend)->orWhere('applies_to_weekend', 0);
                    })
                    ->where(function ($q) use ($timeDiff) {
                        $q->where('min_days_before_checkout', '<=', $timeDiff)
                          ->orWhereNull('min_days_before_checkout');
                    });
                })
                ->where(function ($query) use ($extensionDays) {
                    $query->where('max_extension_days', '>=', $extensionDays)
                          ->orWhereNull('max_extension_days');
                })
                ->orderBy('max_extension_days', 'asc');

            Log::info('Extension Policy Query:', [
                'sql' => $extensionPolicyQuery->toSql(),
                'bindings' => $extensionPolicyQuery->getBindings(),
            ]);

            $extensionPolicy = $extensionPolicyQuery->first();
            if (!$extensionPolicy) {
                $extensionPolicy = ExtensionPolicy::where('is_active', true)
                    ->where('name', 'Gia hạn mặc định')
                    ->first();
                if (!$extensionPolicy) {
                    Log::error('No extension policy found, including default', [
                        'is_holiday' => $isHoliday,
                        'is_weekend' => $isWeekend,
                        'time_diff' => $timeDiff,
                        'extension_days' => $extensionDays,
                    ]);
                    return response()->json(['error' => 'Không tìm thấy chính sách gia hạn phù hợp'], 404);
                }
                Log::info('Using default extension policy', ['policy' => $extensionPolicy->toArray()]);
            }

            Log::info('Extension Policy:', [
                'policy' => $extensionPolicy->toArray(),
                'time_diff' => $timeDiff,
                'extension_days' => $extensionDays,
                'new_check_out_date' => $newCheckOutDate->format('Y-m-d'),
                'current_check_out_date' => $currentCheckOutDate->format('Y-m-d'),
                'is_holiday' => $isHoliday,
                'is_weekend' => $isWeekend,
            ]);

            // Check room availability
            $roomTable = DB::select("SHOW TABLES LIKE 'room'") ? 'room' : (DB::select("SHOW TABLES LIKE 'rooms'") ? 'rooms' : null);
            if (!$roomTable) {
                Log::error('No room table found');
                return response()->json(['error' => 'Bảng phòng không tồn tại trong database'], 500);
            }
            $roomIdColumn = $roomTable === 'room' ? 'room_id' : 'id';

            $bookingRooms = DB::table('booking_rooms')
                ->where('booking_id', $booking->booking_id)
                ->pluck('room_id')
                ->toArray();
            $roomTypeId = $booking->roomType()->first()->room_type_id ?? null;

            if (!$roomTypeId) {
                Log::error('Room type not found for booking_id: ' . $booking->booking_id);
                return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
            }

            $availableRooms = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('rt.room_type_id', $roomTypeId)
                ->where('r.status', 'available')
                ->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($currentCheckOutDate, $newCheckOutDate) {
                    $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['pending', 'confirmed'])
                        ->where('br.check_in_date', '<', $newCheckOutDate)
                        ->where('br.check_out_date', '>', $currentCheckOutDate)
                        ->whereNotNull('br.room_id');
                })
                ->count();

            if ($availableRooms < 1) {
                Log::warning('No available rooms for extension', [
                    'booking_id' => $booking->booking_id,
                    'room_type_id' => $roomTypeId,
                    'check_in_date' => $currentCheckOutDate->format('Y-m-d'),
                    'check_out_date' => $newCheckOutDate->format('Y-m-d'),
                    'available_rooms' => $availableRooms,
                ]);
                return response()->json(['error' => 'Phòng không khả dụng cho các ngày gia hạn yêu cầu'], 400);
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
                    Log::error('Room not found for room_id: ' . $booking->room_id);
                }
            }

            // Check if room type exists
            if (!$roomType) {
                Log::error('Room type not found for booking_id: ' . $booking->booking_id);
                return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
            }

            // Attempt to fetch hotel via Room model relationship
            $hotel = $room ? $room->hotel()->first() : null;
            if (!$hotel) {
                Log::warning('Hotel not found for room_id: ' . ($booking->room_id ?? 'null'));
            }

            // Log relations for debugging
            Log::info('Booking Relations:', [
                'booking_id' => $booking->booking_id,
                'booking_rooms' => $bookingRooms->toArray(),
                'room_names' => $roomNames,
                'room_type' => $roomType ? $roomType->toArray() : null,
                'hotel' => $hotel ? $hotel->toArray() : null,
            ]);

            // Calculate extension fee
            $roomPrice = $booking->total_price_vnd / max(1, Carbon::parse($booking->check_out_date)->diffInDays($booking->check_in_date));
            $extensionFee = $extensionPolicy->extension_fee_vnd ?? 0;
            if ($extensionPolicy->extension_percentage > 0) {
                $extensionFee += ($roomPrice * $extensionPolicy->extension_percentage / 100) * $extensionDays;
            } else {
                $extensionFee *= $extensionDays;
            }
            $extensionFee = max(0, $extensionFee);

            Log::info('Extension Fee Calculation:', [
                'room_price_per_night' => $roomPrice,
                'extension_fee_vnd' => $extensionPolicy->extension_fee_vnd,
                'extension_percentage' => $extensionPolicy->extension_percentage,
                'extension_days' => $extensionDays,
                'total_extension_fee' => $extensionFee,
            ]);

            // Update booking and save extension request
            DB::transaction(function () use ($booking, $extensionPolicy, $newCheckOutDate, $extensionDays, $extensionFee) {
                $booking->check_out_date = $newCheckOutDate->toDateTimeString();
                $booking->total_price_vnd += $extensionFee;
                $booking->save();

                ExtensionRequest::create([
                    'booking_id' => $booking->booking_id,
                    'extension_policy_id' => $extensionPolicy->policy_id,
                    'new_check_out_date' => $newCheckOutDate->toDateTimeString(),
                    'extension_days' => $extensionDays,
                    'extension_fee_vnd' => $extensionFee,
                    'status' => 'Approved',
                    'processed_by' => auth()->id(),
                ]);
            });

            // Prepare response data
            $bookingInfo = [
                'booking_code' => $booking->booking_code,
                'room_name' => $roomNames ?? 'Không xác định',
                'check_in_date' => Carbon::parse($booking->check_in_date)->toDateTimeString(),
                'check_out_date' => $newCheckOutDate->toDateTimeString(),
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

            $policyName = $extensionPolicy->name ?? 'Chính sách mặc định';
            $policyId = $extensionPolicy->policy_id ?? null;

            Log::info('=== BookingController@extendBooking SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Đặt phòng đã được gia hạn thành công',
                'reason' => "Bạn yêu cầu gia hạn vào ngày {$currentTime->format('Y-m-d')}, cách ngày trả phòng hiện tại ({$currentCheckOutDate->format('Y-m-d')}) {$timeDiff} ngày, thỏa mãn chính sách gia hạn {$policyName}. Số ngày gia hạn: {$extensionDays}, phí gia hạn: {$extensionFee} VND.",
                'formula' => $extensionPolicy->extension_percentage > 0
                    ? 'Phí gia hạn = (Phí cố định + (Giá phòng/đêm * Tỷ lệ phần trăm)) * Số ngày'
                    : 'Phí gia hạn = Phí cố định * Số ngày',
                'policy' => $policyName,
                'policy_id' => $policyId,
                'extension_fee' => $extensionFee,
                'extension_days' => $extensionDays,
                'fee_type' => $extensionPolicy->extension_percentage > 0 ? 'mixed' : 'fixed',
                'extension_percentage' => $extensionPolicy->extension_percentage,
                'extension_fixed_amount' => $extensionPolicy->extension_fee_vnd,
                'booking_info' => $bookingInfo,
                'room_info' => $roomInfo,
                'hotel_info' => $hotelInfo,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in extendBooking ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gia hạn đặt phòng',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }
}
