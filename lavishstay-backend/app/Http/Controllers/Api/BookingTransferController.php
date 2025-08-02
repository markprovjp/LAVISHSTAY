<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomOption;
use App\Models\RoomTransfer;
use App\Models\RoomTransferPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingTransferController extends Controller
{
    public function transferBooking(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingController@transferRoom START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Request data: ', $request->all());
            Log::info('Query parameters: ', $request->query());

            // Validate input
            $validated = $request->validate([
                'new_room_id' => 'required|integer',
                'new_option_id' => 'required|integer', // new_option_id là package_id từ room_type_package
                'reason' => 'nullable|string|max:500',
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
                'current_room_id' => $booking->room_id,
                'current_option_id' => $booking->option_id,
            ]);

            // Check booking status
            if ($booking->status !== 'Operational') {
                Log::error('Booking cannot be transferred due to status', ['status' => $booking->status]);
                return response()->json(['error' => 'Chỉ có thể chuyển phòng ở trạng thái Đang lưu trú (Operational)'], 400);
            }

            // Kiểm tra hoặc tạo room_option nếu booking.option_id là NULL
            if (!$booking->option_id) {
                Log::warning('Booking has no option_id, creating new room_option', ['booking_id' => $bookingId]);
                $newRoomOption = RoomOption::create([
                    'room_id' => $booking->room_id,
                    'package_id' => 1, // Giá trị mặc định
                    'bed_type' => 1,
                    'deposit_policy_id' => 10,
                    'cancellation_policy_id' => 10,
                    'check_out_policy_id' => 4,
                ]);
                $booking->option_id = $newRoomOption->option_id;
                $booking->save();
            }

            // Find room_option
            $roomOption = RoomOption::where('option_id', $booking->option_id)->first();
            if (!$roomOption) {
                Log::error('Room option not found', ['option_id' => $booking->option_id]);
                return response()->json(['error' => 'Không tìm thấy tùy chọn phòng cho đặt phòng này'], 404);
            }

            // Find new room
            $roomTable = DB::select("SHOW TABLES LIKE 'room'") ? 'room' : (DB::select("SHOW TABLES LIKE 'rooms'") ? 'rooms' : null);
            if (!$roomTable) {
                Log::error('No room table found');
                return response()->json(['error' => 'Bảng phòng không tồn tại trong database'], 500);
            }
            $roomIdColumn = $roomTable === 'room' ? 'room_id' : 'id';

            $newRoom = DB::table($roomTable)->where($roomIdColumn, $validated['new_room_id'])->first();
            if (!$newRoom) {
                Log::error('New room not found', ['new_room_id' => $validated['new_room_id']]);
                return response()->json(['error' => 'Phòng mới không tồn tại'], 404);
            }

            // Find new package
            $newPackage = DB::table('room_type_package')
                ->where('package_id', $validated['new_option_id'])
                ->where('room_type_id', $newRoom->room_type_id)
                ->first();
            if (!$newPackage) {
                Log::error('New package not found', ['new_option_id' => $validated['new_option_id']]);
                return response()->json(['error' => 'Gói phòng mới không tồn tại hoặc không phù hợp với loại phòng'], 404);
            }

            // Check room availability
            $checkInDate = Carbon::parse($booking->check_in_date)->startOfDay();
            $checkOutDate = Carbon::parse($booking->check_out_date)->startOfDay();

            $isRoomAvailable = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('r.' . $roomIdColumn, $validated['new_room_id'])
                ->where('r.status', 'available')
                ->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($checkInDate, $checkOutDate) {
                    $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                        ->where('br.check_in_date', '<', $checkOutDate)
                        ->where('br.check_out_date', '>', $checkInDate)
                        ->whereNotNull('br.room_id');
                })
                ->exists();

            if (!$isRoomAvailable) {
                Log::warning('Room not available for transfer', [
                    'new_room_id' => $validated['new_room_id'],
                    'new_option_id' => $validated['new_option_id'],
                    'check_in_date' => $checkInDate->format('Y-m-d'),
                    'check_out_date' => $checkOutDate->format('Y-m-d'),
                ]);
                return response()->json(['error' => 'Phòng không khả dụng cho thời gian yêu cầu'], 400);
            }

            // Find transfer policy
            $isHoliday = DB::table('holidays')->where('start_date', Carbon::today()->format('Y-m-d'))->exists();
            $isWeekend = in_array(Carbon::today()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            $timeDiff = Carbon::today()->diffInDays($checkInDate, false);

            $transferPolicyQuery = RoomTransferPolicy::where('is_active', true)
                ->where(function ($query) use ($isHoliday, $isWeekend, $timeDiff, $newRoom) {
                    $query->where(function ($q) use ($isHoliday) {
                        $q->where('applies_to_holiday', $isHoliday)->orWhere('applies_to_holiday', 0);
                    })
                    ->where(function ($q) use ($isWeekend) {
                        $q->where('applies_to_weekend', $isWeekend)->orWhere('applies_to_weekend', 0);
                    })
                    ->where(function ($q) use ($timeDiff) {
                        $q->where('min_days_before_check_in', '<=', $timeDiff)
                          ->orWhereNull('min_days_before_check_in');
                    })
                    ->where(function ($q) use ($newRoom) {
                        $q->where('room_type_id', $newRoom->room_type_id)
                          ->orWhereNull('room_type_id');
                    });
                })
                ->orderByRaw('room_type_id DESC, min_days_before_check_in DESC');

            $transferPolicy = $transferPolicyQuery->first();
            if (!$transferPolicy) {
                $transferPolicy = RoomTransferPolicy::where('is_active', true)
                    ->where('name', 'Chuyển phòng cùng loại miễn phí')
                    ->first();
                if (!$transferPolicy) {
                    Log::error('No transfer policy found', [
                        'is_holiday' => $isHoliday,
                        'is_weekend' => $isWeekend,
                        'time_diff' => $timeDiff,
                        'new_room_type_id' => $newRoom->room_type_id,
                    ]);
                    return response()->json([
                        'error' => 'Không tìm thấy chính sách chuyển phòng phù hợp'
                    ], 404);
                }
            }

            // Calculate price difference
            $stayDays = $checkOutDate->diffInDays($checkInDate, true);
            $currentPrice = $booking->total_price_vnd;

            $newRoomType = DB::table('room_types')->where('room_type_id', $newRoom->room_type_id)->first();
            if (!$newRoomType) {
                Log::error('New room type not found', ['room_type_id' => $newRoom->room_type_id]);
                return response()->json(['error' => 'Loại phòng mới không tồn tại'], 404);
            }
            $newPrice = ($newRoomType->base_price + $newPackage->price_modifier_vnd) * $stayDays;
            $priceDifference = $newPrice - $currentPrice;

            $transferFee = $transferPolicy->transfer_fee_vnd ?? 0;
            if ($transferPolicy->transfer_fee_percentage > 0) {
                $transferFee += ($newPrice * $transferPolicy->transfer_fee_percentage / 100);
            }
            $totalPriceDifference = $priceDifference + $transferFee;

            // Create payment if there is a price difference
            $paymentId = null;
            if ($totalPriceDifference != 0) {
                $payment = Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => abs($totalPriceDifference),
                    'payment_type' => $totalPriceDifference > 0 ? 'additional' : 'refund',
                    'status' => 'pending',
                    'created_at' => Carbon::now(),
                ]);
                $paymentId = $payment->payment_id;
            }

            // Update booking and room_option
            DB::transaction(function () use ($booking, $transferPolicy, $validated, $totalPriceDifference, $paymentId, $newRoom, $newPackage, $roomOption) {
                // Update room_option
                $roomOption->room_id = $validated['new_room_id'];
                $roomOption->package_id = $validated['new_option_id']; // Gán package_id từ request
                $roomOption->save();

                // Update booking
                $booking->room_id = $validated['new_room_id'];
                $booking->room_type_id = $newRoom->room_type_id;
                $booking->total_price_vnd += $totalPriceDifference;
                $booking->save();

                // Update booking_rooms
                DB::table('booking_rooms')
                    ->where('booking_id', $booking->booking_id)
                    ->update([
                        'room_id' => $validated['new_room_id'],
                        'check_in_date' => $booking->check_in_date,
                        'check_out_date' => $booking->check_out_date,
                        'option_id' => $booking->option_id,
                    ]);

                // Create transfer request
                RoomTransfer::create([
                    'booking_id' => $booking->booking_id,
                    'old_room_id' => $booking->room_id,
                    'new_room_id' => $validated['new_room_id'],
                    'new_option_id' => $roomOption->option_id, // Sử dụng option_id từ room_option
                    'transfer_policy_id' => $transferPolicy->policy_id,
                    'status' => $transferPolicy->requires_guest_confirmation ? 'Pending' : 'Approved',
                    'price_difference_vnd' => $totalPriceDifference,
                    'payment_id' => $paymentId,
                    'processed_by' => Auth::id(),
                    'reason' => $validated['reason'] ?? 'Chuyển phòng theo yêu cầu khách',
                    'created_at' => Carbon::now(),
                ]);

                // Create audit log
                DB::table('audit_logs')->insert([
                    'user_id' => Auth::id(),
                    'action' => 'Room Transfer',
                    'table_name' => 'booking',
                    'record_id' => $booking->booking_id,
                    'description' => "Transferred from room {$booking->room_id} to room {$validated['new_room_id']}",
                    'created_at' => Carbon::now(),
                ]);

                
            });

            // Prepare response data
            $newRoomType = DB::table('room_types')->where('room_type_id', $newRoom->room_type_id)->first();
            $bookingInfo = [
                'booking_code' => $booking->booking_code,
                'room_name' => $newRoom->name ?? 'Không xác định',
                'check_in_date' => Carbon::parse($booking->check_in_date)->toDateTimeString(),
                'check_out_date' => Carbon::parse($booking->check_out_date)->toDateTimeString(),
                'total_price' => $booking->total_price_vnd,
            ];

            $roomInfo = [
                'room_name' => $newRoom->name ?? 'Không xác định',
                'room_type' => $newRoomType->name ?? 'Không xác định',
                'room_description' => $newRoom->description ?? 'Không có mô tả',
                'package_name' => $newPackage->package_name ?? 'Không xác định',
            ];

            

            Log::info('=== BookingController@transferRoom SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Chuyển phòng thành công' . ($transferPolicy->requires_guest_confirmation ? ' (đang chờ xác nhận)' : ''),
                'reason' => $validated['reason'] ?? 'Chuyển phòng theo yêu cầu khách',
                'formula' => $transferPolicy->transfer_fee_percentage > 0
                    ? 'Phí chuyển = Phí cố định + (Giá phòng mới * Tỷ lệ phần trăm)'
                    : 'Phí chuyển = Phí cố định',
                'policy' => $transferPolicy->name,
                'policy_id' => $transferPolicy->policy_id,
                'transfer_fee' => $transferFee,
                'price_difference' => $priceDifference,
                'total_price_difference' => $totalPriceDifference,
                'fee_type' => $transferPolicy->transfer_fee_percentage > 0 ? 'mixed' : 'fixed',
                'transfer_percentage' => $transferPolicy->transfer_fee_percentage,
                'transfer_fixed_amount' => $transferPolicy->transfer_fee_vnd,
                'booking_info' => $bookingInfo,
                'room_info' => $roomInfo,
                
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in transferRoom ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chuyển phòng',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    public function getTransferBookingInfo(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingController@previewTransferBooking START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Request data: ', $request->all());
            Log::info('Query parameters: ', $request->query());

            // Validate input
            $validated = $request->validate([
                'new_room_id' => 'required|integer',
                'new_option_id' => 'required|integer', // new_option_id là package_id từ room_type_package
                'reason' => 'nullable|string|max:500',
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
                'current_room_id' => $booking->room_id,
                'current_option_id' => $booking->option_id,
            ]);

            // Check booking status
            if ($booking->status !== 'Operational') {
                Log::error('Booking cannot be transferred due to status', ['status' => $booking->status]);
                return response()->json(['error' => 'Chỉ có thể chuyển phòng ở trạng thái Đang lưu trú (Operational)'], 400);
            }

            // Find room_option
            $roomOption = RoomOption::where('option_id', $booking->option_id)->first();
            if (!$roomOption) {
                Log::error('Room option not found', ['option_id' => $booking->option_id]);
                return response()->json(['error' => 'Không tìm thấy tùy chọn phòng cho đặt phòng này'], 404);
            }

            // Find new room
            $roomTable = DB::select("SHOW TABLES LIKE 'room'") ? 'room' : (DB::select("SHOW TABLES LIKE 'rooms'") ? 'rooms' : null);
            if (!$roomTable) {
                Log::error('No room table found');
                return response()->json(['error' => 'Bảng phòng không tồn tại trong database'], 500);
            }
            $roomIdColumn = $roomTable === 'room' ? 'room_id' : 'id';

            $newRoom = DB::table($roomTable)->where($roomIdColumn, $validated['new_room_id'])->first();
            if (!$newRoom) {
                Log::error('New room not found', ['new_room_id' => $validated['new_room_id']]);
                return response()->json(['error' => 'Phòng mới không tồn tại'], 404);
            }

            // Find new package
            $newPackage = DB::table('room_type_package')
                ->where('package_id', $validated['new_option_id'])
                ->where('room_type_id', $newRoom->room_type_id)
                ->first();
            if (!$newPackage) {
                Log::error('New package not found', ['new_option_id' => $validated['new_option_id']]);
                return response()->json(['error' => 'Gói phòng mới không tồn tại hoặc không phù hợp với loại phòng'], 404);
            }

            // Check room availability
            $checkInDate = Carbon::parse($booking->check_in_date)->startOfDay();
            $checkOutDate = Carbon::parse($booking->check_out_date)->startOfDay();

            $isRoomAvailable = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('r.' . $roomIdColumn, $validated['new_room_id'])
                ->where('r.status', 'available')
                ->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($checkInDate, $checkOutDate) {
                    $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                        ->where('br.check_in_date', '<', $checkOutDate)
                        ->where('br.check_out_date', '>', $checkInDate)
                        ->whereNotNull('br.room_id');
                })
                ->exists();

            if (!$isRoomAvailable) {
                Log::warning('Room not available for transfer', [
                    'new_room_id' => $validated['new_room_id'],
                    'new_option_id' => $validated['new_option_id'],
                    'check_in_date' => $checkInDate->format('Y-m-d'),
                    'check_out_date' => $checkOutDate->format('Y-m-d'),
                ]);
                return response()->json(['error' => 'Phòng không khả dụng cho thời gian yêu cầu'], 400);
            }

            // Find transfer policy
            $isHoliday = DB::table('holidays')->where('start_date', Carbon::today()->format('Y-m-d'))->exists();
            $isWeekend = in_array(Carbon::today()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            $timeDiff = Carbon::today()->diffInDays($checkInDate, false);

            $transferPolicyQuery = RoomTransferPolicy::where('is_active', true)
                ->where(function ($query) use ($isHoliday, $isWeekend, $timeDiff, $newRoom) {
                    $query->where(function ($q) use ($isHoliday) {
                        $q->where('applies_to_holiday', $isHoliday)->orWhere('applies_to_holiday', 0);
                    })
                    ->where(function ($q) use ($isWeekend) {
                        $q->where('applies_to_weekend', $isWeekend)->orWhere('applies_to_weekend', 0);
                    })
                    ->where(function ($q) use ($timeDiff) {
                        $q->where('min_days_before_check_in', '<=', $timeDiff)
                          ->orWhereNull('min_days_before_check_in');
                    })
                    ->where(function ($q) use ($newRoom) {
                        $q->where('room_type_id', $newRoom->room_type_id)
                          ->orWhereNull('room_type_id');
                    });
                })
                ->orderByRaw('room_type_id DESC, min_days_before_check_in DESC');

            $transferPolicy = $transferPolicyQuery->first();
            if (!$transferPolicy) {
                $transferPolicy = RoomTransferPolicy::where('is_active', true)
                    ->where('name', 'Chuyển phòng cùng loại miễn phí')
                    ->first();
                if (!$transferPolicy) {
                    Log::error('No transfer policy found', [
                        'is_holiday' => $isHoliday,
                        'is_weekend' => $isWeekend,
                        'time_diff' => $timeDiff,
                        'new_room_type_id' => $newRoom->room_type_id,
                    ]);
                    return response()->json([
                        'error' => 'Không tìm thấy chính sách chuyển phòng phù hợp'
                    ], 404);
                }
            }

            // Calculate price difference
            $stayDays = $checkOutDate->diffInDays($checkInDate, true);
            $currentPrice = $booking->total_price_vnd;

            $newRoomType = DB::table('room_types')->where('room_type_id', $newRoom->room_type_id)->first();
            if (!$newRoomType) {
                Log::error('New room type not found', ['room_type_id' => $newRoom->room_type_id]);
                return response()->json(['error' => 'Loại phòng mới không tồn tại'], 404);
            }
            $newPrice = ($newRoomType->base_price + $newPackage->price_modifier_vnd) * $stayDays;
            $priceDifference = $newPrice - $currentPrice;

            $transferFee = $transferPolicy->transfer_fee_vnd ?? 0;
            if ($transferPolicy->transfer_fee_percentage > 0) {
                $transferFee += ($newPrice * $transferPolicy->transfer_fee_percentage / 100);
            }
            $totalPriceDifference = $priceDifference + $transferFee;

            // Prepare response data
            $bookingInfo = [
                'booking_code' => $booking->booking_code,
                'room_name' => $newRoom->name ?? 'Không xác định',
                'check_in_date' => Carbon::parse($booking->check_in_date)->toDateTimeString(),
                'check_out_date' => Carbon::parse($booking->check_out_date)->toDateTimeString(),
                'total_price' => $booking->total_price_vnd + $totalPriceDifference,
            ];

            $roomInfo = [
                'room_name' => $newRoom->name ?? 'Không xác định',
                'room_type' => $newRoomType->name ?? 'Không xác định',
                'room_description' => $newRoom->description ?? 'Không có mô tả',
                'package_name' => $newPackage->package_name ?? 'Không xác định',
            ];

            $hotel = null;
            if (property_exists($newRoom, 'hotel_id') && $newRoom->hotel_id) {
                $hotel = DB::table('hotel')->where('hotel_id', $newRoom->hotel_id)->first();
            }
            $hotelInfo = [
                'hotel_name' => $hotel ? ($hotel->name ?? 'Không xác định') : 'Không xác định',
                'hotel_address' => $hotel ? ($hotel->address ?? 'Không xác định') : 'Không xác định',
                'hotel_phone' => $hotel ? ($hotel->phone ?? 'Không xác định') : 'Không xác định',
            ];

            Log::info('=== BookingController@previewTransferBooking SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Xem trước chuyển phòng thành công' . ($transferPolicy->requires_guest_confirmation ? ' (sẽ cần xác nhận khi thực hiện)' : ''),
                'reason' => $validated['reason'] ?? 'Chuyển phòng theo yêu cầu khách',
                'formula' => $transferPolicy->transfer_fee_percentage > 0
                    ? 'Phí chuyển = Phí cố định + (Giá phòng mới * Tỷ lệ phần trăm)'
                    : 'Phí chuyển = Phí cố định',
                'policy' => $transferPolicy->name,
                'policy_id' => $transferPolicy->policy_id,
                'transfer_fee' => $transferFee,
                'price_difference' => $priceDifference,
                'total_price_difference' => $totalPriceDifference,
                'fee_type' => $transferPolicy->transfer_fee_percentage > 0 ? 'mixed' : 'fixed',
                'transfer_percentage' => $transferPolicy->transfer_fee_percentage,
                'transfer_fixed_amount' => $transferPolicy->transfer_fee_vnd,
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
            Log::error('=== ERROR in previewTransferBooking ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xem trước chuyển phòng',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }
}
