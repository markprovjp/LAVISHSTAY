<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingReschedule;
use App\Models\Payment;
use App\Models\ReschedulePolicy;
use App\Models\RoomOption;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingRescheduleController extends Controller
{
        public function rescheduleBooking(Request $request, $bookingId)
        {
        try {
                Log::info('=== BookingController@rescheduleBooking START ===');
                Log::info('Request URL: ' . $request->fullUrl());
                Log::info('Request data: ', $request->all());
                Log::info('Query parameters: ', $request->query());

                // Validate input
                $validated = $request->validate([
                'new_check_in_date' => 'required|date|after_or_equal:today',
                'new_check_out_date' => 'required|date|after:new_check_in_date',
                'new_room_id' => 'nullable|array',
                'new_room_id.*' => 'integer',
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
                if (in_array($booking->status, ['Cancelled', 'Cancelled With Penalty', 'Completed'])) {
                Log::error('Booking cannot be rescheduled due to status', ['status' => $booking->status]);
                return response()->json(['error' => 'Không thể rời lịch do trạng thái đặt phòng không phù hợp'], 400);
                }

                // Find room option
                $roomOption = RoomOption::find($booking->option_id);
                if (!$roomOption) {
                Log::error('Room option not found', ['option_id' => $booking->option_id]);
                return response()->json(['error' => 'Không tìm thấy tùy chọn phòng cho đặt phòng này'], 404);
                }

                // Parse dates
                $newCheckInDate = Carbon::parse($validated['new_check_in_date'])->startOfDay();
                $newCheckOutDate = Carbon::parse($validated['new_check_out_date'])->startOfDay();
                $stayDays = $newCheckOutDate->diffInDays($newCheckInDate, true);

                // Get current rooms from booking_rooms
                $currentRooms = DB::table('booking_rooms')
                ->where('booking_id', $booking->booking_id)
                ->pluck('room_id')
                ->toArray();
                $currentRoomCount = count($currentRooms);

                // Determine rooms to check
                $newRoomIds = $validated['new_room_id'] ?? $currentRooms;
                if (count($newRoomIds) !== $currentRoomCount) {
                Log::error('Number of new rooms does not match current rooms', [
                        'current_room_count' => $currentRoomCount,
                        'new_room_count' => count($newRoomIds),
                ]);
                return response()->json(['error' => 'Số lượng phòng mới phải bằng số lượng phòng hiện tại'], 400);
                }

                // Find room table
                $roomTable = DB::select("SHOW TABLES LIKE 'room'") ? 'room' : (DB::select("SHOW TABLES LIKE 'rooms'") ? 'rooms' : null);
                if (!$roomTable) {
                Log::error('No room table found');
                return response()->json(['error' => 'Bảng phòng không tồn tại trong database'], 500);
                }
                $roomIdColumn = $roomTable === 'room' ? 'room_id' : 'id';

                // Find package
                $package = DB::table('room_type_package')
                ->where('package_id', $roomOption->package_id)
                ->first();
                if (!$package) {
                Log::error('Package not found', ['package_id' => $roomOption->package_id]);
                return response()->json(['error' => 'Gói phòng không tồn tại'], 404);
                }

                // Find target rooms and validate room type
                $targetRooms = DB::table($roomTable)
                ->whereIn($roomIdColumn, $newRoomIds)
                ->get();
                if ($targetRooms->count() !== count($newRoomIds)) {
                Log::error('Some target rooms not found', ['new_room_ids' => $newRoomIds]);
                return response()->json(['error' => 'Một hoặc nhiều phòng đích không tồn tại'], 404);
                }

                foreach ($targetRooms as $room) {
                if ($room->room_type_id !== $package->room_type_id) {
                        Log::error('Room not compatible with package', [
                        'room_id' => $room->$roomIdColumn,
                        'room_type_id' => $room->room_type_id,
                        'package_room_type_id' => $package->room_type_id,
                        ]);
                        return response()->json(['error' => 'Phòng mới không phù hợp với gói phòng hiện tại'], 400);
                }
                }

                // Check room availability
                $unavailableRooms = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->whereIn('r.' . $roomIdColumn, $newRoomIds)
                ->where('r.status', 'available')
                ->whereIn('r.' . $roomIdColumn, function ($subQuery) use ($newCheckInDate, $newCheckOutDate) {
                        $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                        ->where('br.check_in_date', '<', $newCheckOutDate)
                        ->where('br.check_out_date', '>', $newCheckInDate)
                        ->whereNotNull('br.room_id');
                })
                ->pluck('r.' . $roomIdColumn)
                ->toArray();

                $suggestedRooms = [];
                if (!empty($unavailableRooms)) {
                Log::warning('Some target rooms not available', [
                        'unavailable_room_ids' => $unavailableRooms,
                        'new_check_in_date' => $newCheckInDate->format('Y-m-d'),
                        'new_check_out_date' => $newCheckOutDate->format('Y-m-d'),
                ]);

                // Suggest similar rooms
                $suggestedRooms = DB::table($roomTable . ' as r')
                        ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                        ->where('r.room_type_id', $package->room_type_id)
                        ->where('r.status', 'available')
                        ->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($newCheckInDate, $newCheckOutDate) {
                        $subQuery->select('br.room_id')
                                ->from('booking_rooms as br')
                                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                                ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                                ->where('br.check_in_date', '<', $newCheckOutDate)
                                ->where('br.check_out_date', '>', $newCheckInDate)
                                ->whereNotNull('br.room_id');
                        })
                        ->select('r.' . $roomIdColumn . ' as room_id', 'r.name', 'rt.name as room_type_name')
                        ->limit($currentRoomCount)
                        ->get()
                        ->toArray();

                if (count($suggestedRooms) < $currentRoomCount) {
                        return response()->json([
                        'error' => 'Không đủ phòng tương tự khả dụng cho yêu cầu rời lịch',
                        'suggested_rooms' => $suggestedRooms
                        ], 400);
                }

                return response()->json([
                        'error' => 'Một hoặc nhiều phòng hiện tại không khả dụng',
                        'suggested_rooms' => $suggestedRooms
                ], 400);
                }

                // Find reschedule policy
                $isHoliday = DB::table('holidays')->where('start_date', Carbon::today()->format('Y-m-d'))->exists();
                $isWeekend = in_array(Carbon::today()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
                $timeDiff = Carbon::today()->diffInDays($newCheckInDate, false);

                $reschedulePolicyQuery = ReschedulePolicy::where('is_active', true)
                ->where(function ($query) use ($isHoliday, $isWeekend, $timeDiff, $package) {
                        $query->where(function ($q) use ($isHoliday) {
                        $q->where('applies_to_holiday', $isHoliday)->orWhere('applies_to_holiday', 0);
                        })
                        ->where(function ($q) use ($isWeekend) {
                        $q->where('applies_to_weekend', $isWeekend)->orWhere('applies_to_weekend', 0);
                        })
                        ->where(function ($q) use ($timeDiff) {
                        $q->where('min_days_before_checkin', '<=', $timeDiff)
                        ->orWhereNull('min_days_before_checkin');
                        })
                        ->where(function ($q) use ($package) {
                        $q->where('room_type_id', $package->room_type_id)
                        ->orWhereNull('room_type_id');
                        });
                })
                ->orderByRaw('room_type_id DESC, min_days_before_checkin DESC');

                $reschedulePolicy = $reschedulePolicyQuery->first();
                if (!$reschedulePolicy) {
                Log::error('No reschedule policy found', [
                        'is_holiday' => $isHoliday,
                        'is_weekend' => $isWeekend,
                        'time_diff' => $timeDiff,
                        'room_type_id' => $package->room_type_id,
                ]);
                return response()->json(['error' => 'Không tìm thấy chính sách rời lịch phù hợp'], 404);
                }

                // Calculate price difference
                $currentPrice = $booking->total_price_vnd;
                $roomType = DB::table('room_types')->where('room_type_id', $package->room_type_id)->first();
                if (!$roomType) {
                Log::error('Room type not found', ['room_type_id' => $package->room_type_id]);
                return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
                }

                $roomPricePerNight = $roomType->base_price + $package->price_modifier_vnd;
                $newPrice = $roomPricePerNight * $stayDays * $currentRoomCount;
                $priceDifference = $newPrice - $currentPrice;

                // Calculate reschedule fee
                $rescheduleFee = $reschedulePolicy->reschedule_fee_vnd ?? 0;
                if ($reschedulePolicy->reschedule_fee_percentage > 0) {
                $rescheduleFee += ($newPrice * $reschedulePolicy->reschedule_fee_percentage / 100);
                }
                $totalPriceDifference = $priceDifference + $rescheduleFee;

                // Prepare room info for response
                $roomInfo = $targetRooms->map(function ($room) use ($roomType, $package, $roomIdColumn) {
                return [
                        'room_id' => $room->$roomIdColumn,
                        'room_name' => $room->name ?? 'Không xác định',
                        'room_type' => $roomType->name ?? 'Không xác định',
                        'room_type_id' => $roomType->room_type_id ?? 'Không xác định',
                        'room_description' => $room->description ?? 'Không có mô tả',
                        'package_name' => $package->name ?? 'Không xác định',
                        'package_id' => $package->package_id ?? 'Không xác định',
                        'package_description' => $package->description ?? 'Không có mô tả',
                ];
                })->toArray();

                // Update booking and create reschedule request within a transaction
                $paymentId = null;
                DB::transaction(function () use ($booking, $validated, $newRoomIds, $roomOption, $reschedulePolicy, $totalPriceDifference, $roomPricePerNight, $stayDays, $newCheckInDate, $newCheckOutDate, $roomIdColumn, &$paymentId, $currentRoomCount) {
                // Create payment if there is a price difference
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

                // Create reschedule request
                $reschedule = BookingReschedule::create([
                        'booking_id' => $booking->booking_id,
                        'new_check_in_date' => $newCheckInDate,
                        'new_check_out_date' => $newCheckOutDate,
                        'new_room_id' => count($newRoomIds) === 1 ? $newRoomIds[0] : json_encode($newRoomIds), // Store as integer if single room
                        'new_option_id' => $roomOption->option_id,
                        'reschedule_policy_id' => $reschedulePolicy->policy_id,
                        'price_difference_vnd' => $totalPriceDifference,
                        'payment_id' => $paymentId,
                        'status' => 'Approved',
                        'reason' => $validated['reason'] ?? 'Rời lịch theo yêu cầu khách',
                        'suggested_rooms' => json_encode([]),
                        'processed_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                ]);

                // Update booking
                $booking->check_in_date = $newCheckInDate;
                $booking->check_out_date = $newCheckOutDate;
                $booking->total_price_vnd += $totalPriceDifference;
                $booking->save();

                // Update booking_rooms
                DB::table('booking_rooms')
                        ->where('booking_id', $booking->booking_id)
                        ->delete();

                $bookingRoomsData = [];
                foreach ($newRoomIds as $roomId) {
                        $bookingRoomsData[] = [
                        'booking_id' => $booking->booking_id,
                        'room_id' => $roomId,
                        'check_in_date' => $newCheckInDate,
                        'check_out_date' => $newCheckOutDate,
                        'option_id' => $roomOption->option_id,
                        'price_per_night' => $roomPricePerNight,
                        'nights' => $stayDays,
                        'total_price' => $roomPricePerNight * $stayDays,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        ];
                }
                DB::table('booking_rooms')->insert($bookingRoomsData);


                // Create audit log
                DB::table('audit_logs')->insert([
                        'user_id' => Auth::id(),
                        'action' => 'Reschedule Booking',
                        'table_name' => 'booking',
                        'record_id' => $booking->booking_id,
                        'description' => "Rescheduled booking from {$booking->check_in_date} to {$newCheckInDate->format('Y-m-d')} and {$booking->check_out_date} to {$newCheckOutDate->format('Y-m-d')}",
                        'created_at' => Carbon::now(),
                ]);
                });

                // Prepare response data
                $bookingInfo = [
                'booking_code' => $booking->booking_code,
                'check_in_date' => $newCheckInDate->toDateTimeString(),
                'check_out_date' => $newCheckOutDate->toDateTimeString(),
                'total_price' => $booking->total_price_vnd,
                ];

                Log::info('=== BookingController@rescheduleBooking SUCCESS ===');

                return response()->json([
                'success' => true,
                'message' => 'Rời lịch thành công',
                'reason' => $validated['reason'] ?? 'Rời lịch theo yêu cầu khách',
                'formula' => $reschedulePolicy->reschedule_fee_percentage > 0
                        ? 'Phí rời lịch = Phí cố định + (Giá phòng mới * Tỷ lệ phần trăm)'
                        : 'Phí rời lịch = Phí cố định',
                'policy' => $reschedulePolicy->name,
                'policy_id' => $reschedulePolicy->policy_id,
                'reschedule_fee' => $rescheduleFee,
                'price_difference' => $priceDifference,
                'total_price_difference' => $totalPriceDifference,
                'fee_type' => $reschedulePolicy->reschedule_fee_percentage > 0 ? 'mixed' : 'fixed',
                'reschedule_percentage' => $reschedulePolicy->reschedule_fee_percentage,
                'reschedule_fixed_amount' => $reschedulePolicy->reschedule_fee_vnd,
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
                Log::error('=== ERROR in rescheduleBooking ===');
                Log::error('Error message: ' . $e->getMessage());
                Log::error('File: ' . $e->getFile());
                Log::error('Line: ' . $e->getLine());
                Log::error('Stack trace: ' . $e->getTraceAsString());

                return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi rời lịch',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
                ], 500);
        }
        }

        public function getRescheduleBookingInfo(Request $request, $bookingId)
        {
        try {
                Log::info('=== BookingController@getRescheduleBookingInfo START ===');
                Log::info('Request URL: ' . $request->fullUrl());
                Log::info('Request data: ', $request->all());
                Log::info('Query parameters: ', $request->query());

                // Validate input
                $validated = $request->validate([
                'new_check_in_date' => 'required|date|after_or_equal:today',
                'new_check_out_date' => 'required|date|after:new_check_in_date',
                'new_room_id' => 'nullable|array',
                'new_room_id.*' => 'integer',
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
                if (in_array($booking->status, ['Cancelled', 'Cancelled With Penalty', 'Completed'])) {
                Log::error('Booking cannot be rescheduled due to status', ['status' => $booking->status]);
                return response()->json(['error' => 'Không thể rời lịch do trạng thái đặt phòng không phù hợp'], 400);
                }

                // Find room option
                $roomOption = RoomOption::find($booking->option_id);
                if (!$roomOption) {
                Log::error('Room option not found', ['option_id' => $booking->option_id]);
                return response()->json(['error' => 'Không tìm thấy tùy chọn phòng cho đặt phòng này'], 404);
                }

                // Parse dates
                $newCheckInDate = Carbon::parse($validated['new_check_in_date'])->startOfDay();
                $newCheckOutDate = Carbon::parse($validated['new_check_out_date'])->startOfDay();
                $stayDays = $newCheckOutDate->diffInDays($newCheckInDate, true);

                // Get current rooms from booking_rooms
                $currentRooms = DB::table('booking_rooms')
                ->where('booking_id', $booking->booking_id)
                ->pluck('room_id')
                ->toArray();
                $currentRoomCount = count($currentRooms);

                // Determine rooms to check
                $newRoomIds = $validated['new_room_id'] ?? $currentRooms;
                if (count($newRoomIds) !== $currentRoomCount) {
                Log::error('Number of new rooms does not match current rooms', [
                        'current_room_count' => $currentRoomCount,
                        'new_room_count' => count($newRoomIds),
                ]);
                return response()->json(['error' => 'Số lượng phòng mới phải bằng số lượng phòng hiện tại'], 400);
                }

                // Find room table
                $roomTable = DB::select("SHOW TABLES LIKE 'room'") ? 'room' : (DB::select("SHOW TABLES LIKE 'rooms'") ? 'rooms' : null);
                if (!$roomTable) {
                Log::error('No room table found');
                return response()->json(['error' => 'Bảng phòng không tồn tại trong database'], 500);
                }
                $roomIdColumn = $roomTable === 'room' ? 'room_id' : 'id';

                // Find package
                $package = DB::table('room_type_package')
                ->where('package_id', $roomOption->package_id)
                ->first();
                if (!$package) {
                Log::error('Package not found', ['package_id' => $roomOption->package_id]);
                return response()->json(['error' => 'Gói phòng không tồn tại'], 404);
                }

                // Find target rooms and validate room type
                $targetRooms = DB::table($roomTable)
                ->whereIn($roomIdColumn, $newRoomIds)
                ->get();
                if ($targetRooms->count() !== count($newRoomIds)) {
                Log::error('Some target rooms not found', ['new_room_ids' => $newRoomIds]);
                return response()->json(['error' => 'Một hoặc nhiều phòng đích không tồn tại'], 404);
                }

                foreach ($targetRooms as $room) {
                if ($room->room_type_id !== $package->room_type_id) {
                        Log::error('Room not compatible with package', [
                        'room_id' => $room->$roomIdColumn,
                        'room_type_id' => $room->room_type_id,
                        'package_room_type_id' => $package->room_type_id,
                        ]);
                        return response()->json(['error' => 'Phòng mới không phù hợp với gói phòng hiện tại'], 400);
                }
                }

                // Check room availability
                $unavailableRooms = DB::table($roomTable . ' as r')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->whereIn('r.' . $roomIdColumn, $newRoomIds)
                ->where('r.status', 'available')
                ->whereIn('r.' . $roomIdColumn, function ($subQuery) use ($newCheckInDate, $newCheckOutDate) {
                        $subQuery->select('br.room_id')
                        ->from('booking_rooms as br')
                        ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                        ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                        ->where('br.check_in_date', '<', $newCheckOutDate)
                        ->where('br.check_out_date', '>', $newCheckInDate)
                        ->whereNotNull('br.room_id');
                })
                ->pluck('r.' . $roomIdColumn)
                ->toArray();

                $suggestedRooms = [];
                if (!empty($unavailableRooms)) {
                Log::warning('Some target rooms not available', [
                        'unavailable_room_ids' => $unavailableRooms,
                        'new_check_in_date' => $newCheckInDate->format('Y-m-d'),
                        'new_check_out_date' => $newCheckOutDate->format('Y-m-d'),
                ]);

                // Suggest similar rooms
                $suggestedRooms = DB::table($roomTable . ' as r')
                        ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                        ->where('r.room_type_id', $package->room_type_id)
                        ->where('r.status', 'available')
                        ->whereNotIn('r.' . $roomIdColumn, function ($subQuery) use ($newCheckInDate, $newCheckOutDate) {
                        $subQuery->select('br.room_id')
                                ->from('booking_rooms as br')
                                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                                ->whereIn('b.status', ['Pending', 'Confirmed', 'Operational'])
                                ->where('br.check_in_date', '<', $newCheckOutDate)
                                ->where('br.check_out_date', '>', $newCheckInDate)
                                ->whereNotNull('br.room_id');
                        })
                        ->select('r.' . $roomIdColumn . ' as room_id', 'r.name', 'rt.name as room_type_name')
                        ->limit($currentRoomCount)
                        ->get()
                        ->toArray();

                if (count($suggestedRooms) < $currentRoomCount) {
                        return response()->json([
                        'error' => 'Không đủ phòng tương tự khả dụng cho yêu cầu rời lịch',
                        'suggested_rooms' => $suggestedRooms
                        ], 400);
                }

                return response()->json([
                        'error' => 'Một hoặc nhiều phòng hiện tại không khả dụng',
                        'suggested_rooms' => $suggestedRooms
                ], 400);
                }

                // Find reschedule policy
                $isHoliday = DB::table('holidays')->where('start_date', Carbon::today()->format('Y-m-d'))->exists();
                $isWeekend = in_array(Carbon::today()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
                $timeDiff = Carbon::today()->diffInDays($newCheckInDate, false);

                $reschedulePolicyQuery = ReschedulePolicy::where('is_active', true)
                ->where(function ($query) use ($isHoliday, $isWeekend, $timeDiff, $package) {
                        $query->where(function ($q) use ($isHoliday) {
                        $q->where('applies_to_holiday', $isHoliday)->orWhere('applies_to_holiday', 0);
                        })
                        ->where(function ($q) use ($isWeekend) {
                        $q->where('applies_to_weekend', $isWeekend)->orWhere('applies_to_weekend', 0);
                        })
                        ->where(function ($q) use ($timeDiff) {
                        $q->where('min_days_before_checkin', '<=', $timeDiff)
                        ->orWhereNull('min_days_before_checkin');
                        })
                        ->where(function ($q) use ($package) {
                        $q->where('room_type_id', $package->room_type_id)
                        ->orWhereNull('room_type_id');
                        });
                })
                ->orderByRaw('room_type_id DESC, min_days_before_checkin DESC');

                $reschedulePolicy = $reschedulePolicyQuery->first();
                if (!$reschedulePolicy) {
                Log::error('No reschedule policy found', [
                        'is_holiday' => $isHoliday,
                        'is_weekend' => $isWeekend,
                        'time_diff' => $timeDiff,
                        'room_type_id' => $package->room_type_id,
                ]);
                return response()->json(['error' => 'Không tìm thấy chính sách rời lịch phù hợp'], 404);
                }

                // Calculate price difference
                $currentPrice = $booking->total_price_vnd;
                $roomType = DB::table('room_types')->where('room_type_id', $package->room_type_id)->first();
                if (!$roomType) {
                Log::error('Room type not found', ['room_type_id' => $package->room_type_id]);
                return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
                }

                $roomPricePerNight = $roomType->base_price + $package->price_modifier_vnd;
                $newPrice = $roomPricePerNight * $stayDays * $currentRoomCount;
                $priceDifference = $newPrice - $currentPrice;

                // Calculate reschedule fee
                $rescheduleFee = $reschedulePolicy->reschedule_fee_vnd ?? 0;
                if ($reschedulePolicy->reschedule_fee_percentage > 0) {
                $rescheduleFee += ($newPrice * $reschedulePolicy->reschedule_fee_percentage / 100);
                }
                $totalPriceDifference = $priceDifference + $rescheduleFee;

                // Prepare room info for response
                $roomInfo = $targetRooms->map(function ($room) use ($roomType, $package, $roomIdColumn) {
                return [
                        'room_id' => $room->$roomIdColumn,
                        'room_name' => $room->name ?? 'Không xác định',
                        'room_type' => $roomType->name ?? 'Không xác định',
                        'room_type_id' => $roomType->room_type_id ?? 'Không xác định',
                        'room_description' => $room->description ?? 'Không có mô tả',
                        'package_name' => $package->name ?? 'Không xác định',
                        'package_id' => $package->package_id ?? 'Không xác định',
                        'package_description' => $package->description ?? 'Không có mô tả',
                ];
                })->toArray();

                // Prepare booking info for response
                $bookingInfo = [
                'booking_code' => $booking->booking_code,
                'check_in_date' => $newCheckInDate->toDateTimeString(),
                'check_out_date' => $newCheckOutDate->toDateTimeString(),
                'total_price' => $booking->total_price_vnd + $totalPriceDifference,
                ];


                Log::info('=== BookingController@getRescheduleBookingInfo SUCCESS ===');

                return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin rời lịch thành công',
                'formula' => $reschedulePolicy->reschedule_fee_percentage > 0
                        ? 'Phí rời lịch = Phí cố định + (Giá phòng mới * Tỷ lệ phần trăm)'
                        : 'Phí rời lịch = Phí cố định',
                'policy' => $reschedulePolicy->name,
                'policy_id' => $reschedulePolicy->policy_id,
                'reschedule_fee' => $rescheduleFee,
                'price_difference' => $priceDifference,
                'total_price_difference' => $totalPriceDifference,
                'fee_type' => $reschedulePolicy->reschedule_fee_percentage > 0 ? 'mixed' : 'fixed',
                'reschedule_percentage' => $reschedulePolicy->reschedule_fee_percentage,
                'reschedule_fixed_amount' => $reschedulePolicy->reschedule_fee_vnd,
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
                Log::error('=== ERROR in getRescheduleBookingInfo ===');
                Log::error('Error message: ' . $e->getMessage());
                Log::error('File: ' . $e->getFile());
                Log::error('Line: ' . $e->getLine());
                Log::error('Stack trace: ' . $e->getTraceAsString());

                return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin rời lịch',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
                ], 500);
        }
        }
}
