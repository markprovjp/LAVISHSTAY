<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomOption;
use App\Models\CheckinPolicy;
use App\Models\CheckInRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingCheckinController extends Controller
{
    /**
     * Safely parse time string to Carbon instance
     */
    private function safeParseTime($timeString, $defaultTime = null)
    {
        if (empty($timeString) || is_null($timeString)) {
            return $defaultTime ? Carbon::createFromFormat('H:i', $defaultTime) : null;
        }

        try {
            // Clean the time string
            $cleanTime = trim($timeString);
            
            // Handle different time formats
            if (preg_match('/^(\d{1,2}):(\d{2})(:(\d{2}))?/', $cleanTime, $matches)) {
                $hour = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $minute = $matches[2];
                return Carbon::createFromFormat('H:i', $hour . ':' . $minute);
            }
            
            // Try parsing as full datetime and extract time
            if (strlen($cleanTime) > 5) {
                $carbonInstance = Carbon::parse($cleanTime);
                return Carbon::createFromFormat('H:i', $carbonInstance->format('H:i'));
            }
            
            return Carbon::createFromFormat('H:i', $cleanTime);
        } catch (\Exception $e) {
            Log::warning("Failed to parse time: {$timeString}, Error: " . $e->getMessage());
            return $defaultTime ? Carbon::createFromFormat('H:i', $defaultTime) : null;
        }
    }

    /**
     * Safely parse date string to Carbon instance
     */
    private function safeParseDate($dateString, $defaultDate = null)
    {
        if (empty($dateString) || is_null($dateString)) {
            return $defaultDate ? Carbon::parse($defaultDate) : null;
        }

        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: {$dateString}, Error: " . $e->getMessage());
            return $defaultDate ? Carbon::parse($defaultDate) : Carbon::today();
        }
    }

    /**
     * Get today's bookings that need check-in
     */
    public function getTodayCheckins(Request $request)
    {
        try {
            Log::info('=== BookingCheckinController@getTodayCheckins START ===');
            
            $today = Carbon::today();
            
            // Get bookings for today's check-in
            $bookings = Booking::with(['user', 'roomOption', 'payments', 'room'])
                ->where('check_in_date', $today->format('Y-m-d'))
                ->whereIn('status', ['Confirmed', 'Pending'])
                ->orderBy('check_in_date')
                ->get();

            $checkinList = [];
            
            foreach ($bookings as $booking) {
                try {
                    // Get room information
                    $roomInfo = $this->getRoomInformation($booking);
                    
                    // Check payment status
                    $paymentStatus = $this->checkPaymentStatus($booking);
                    
                    // Check if rooms are assigned
                    $roomsAssigned = $this->checkRoomsAssigned($booking);
                    
                    // Check documents verification (if needed)
                    $documentsVerified = $this->checkDocumentsVerification($booking);
                    
                    // Determine if ready for check-in
                    $readyForCheckin = $paymentStatus['is_sufficient'] && 
                                     $roomsAssigned['has_rooms'] && 
                                     $documentsVerified['is_verified'];
                    
                    $checkinList[] = [
                        'booking_id' => $booking->booking_id,
                        'booking_code' => $booking->booking_code,
                        'guest_name' => $booking->guest_name,
                        'guest_email' => $booking->guest_email,
                        'guest_phone' => $booking->guest_phone,
                        'check_in_date' => $booking->check_in_date,
                        'check_out_date' => $booking->check_out_date,
                        'status' => $booking->status,
                        'total_price_vnd' => $booking->total_price_vnd,
                        'guest_count' => $booking->guest_count,
                        'adults' => $booking->adults,
                        'children' => $booking->children,
                        'room_info' => $roomInfo,
                        'payment_status' => $paymentStatus,
                        'rooms_assigned' => $roomsAssigned,
                        'documents_verified' => $documentsVerified,
                        'ready_for_checkin' => $readyForCheckin,
                        'warnings' => $this->getCheckinWarnings($booking, $paymentStatus, $roomsAssigned, $documentsVerified)
                    ];
                } catch (\Exception $e) {
                    Log::error("Error processing booking {$booking->booking_id}: " . $e->getMessage());
                    // Continue with next booking instead of failing completely
                    continue;
                }
            }

            Log::info('=== BookingCheckinController@getTodayCheckins SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách check-in hôm nay thành công',
                'data' => [
                    'date' => $today->format('Y-m-d'),
                    'total_bookings' => count($checkinList),
                    'ready_count' => collect($checkinList)->where('ready_for_checkin', true)->count(),
                    'bookings' => $checkinList
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in getTodayCheckins ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách check-in',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed check-in information for a specific booking
     */
    public function getCheckinInfo(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckinController@getCheckinInfo START ===');
            Log::info('Booking ID: ' . $bookingId);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room'])->find($bookingId);
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            Log::info('Found booking: ' . $booking->booking_code);

            // Check if booking can be checked in
            if (!in_array($booking->status, ['Confirmed', 'Pending'])) {
                return response()->json([
                    'error' => 'Booking không thể check-in',
                    'current_status' => $booking->status,
                    'allowed_statuses' => ['Confirmed', 'Pending']
                ], 400);
            }

            // Get applicable check-in policies
            $applicablePolicies = $this->getApplicableCheckinPolicies($booking);
            Log::info('Found ' . count($applicablePolicies) . ' applicable policies');
            
            // Get room information
            $roomInfo = $this->getRoomInformation($booking);
            Log::info('Room info retrieved');
            
            // Check all conditions
            $paymentStatus = $this->checkPaymentStatus($booking);
            $roomsAssigned = $this->checkRoomsAssigned($booking);
            $documentsVerified = $this->checkDocumentsVerification($booking);
            Log::info('Conditions checked');
            
            // Check early check-in policy if applicable
            $earlyCheckinInfo = $this->checkEarlyCheckinPolicy($booking, $applicablePolicies);
            Log::info('Early checkin policy checked');
            
            // Determine if ready for check-in
            $readyForCheckin = $paymentStatus['is_sufficient'] && 
                             $roomsAssigned['has_rooms'] && 
                             $documentsVerified['is_verified'];

            $checkinInfo = [
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'status' => $booking->status,
                'total_price_vnd' => $booking->total_price_vnd,
                'guest_count' => $booking->guest_count,
                'adults' => $booking->adults,
                'children' => $booking->children,
                'children_age' => $booking->children_age,
                'notes' => $booking->notes,
                'room_info' => $roomInfo,
                'payment_status' => $paymentStatus,
                'rooms_assigned' => $roomsAssigned,
                'documents_verified' => $documentsVerified,
                'early_checkin_info' => $earlyCheckinInfo,
                'applicable_policies' => $applicablePolicies,
                'ready_for_checkin' => $readyForCheckin,
                'warnings' => $this->getCheckinWarnings($booking, $paymentStatus, $roomsAssigned, $documentsVerified),
                'requirements' => $this->getCheckinRequirements($booking, $applicablePolicies)
            ];

            Log::info('=== BookingCheckinController@getCheckinInfo SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin check-in thành công',
                'data' => $checkinInfo
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in getCheckinInfo ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin check-in',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process check-in for a booking
     */
    /**
     * Process check-in for a booking
     */
    public function processCheckin(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckinController@processCheckin START ===');
            Log::info('Booking ID: ' . $bookingId);
            Log::info('Request data: ', $request->all());

            // Validate input
            $validated = $request->validate([
                'actual_checkin_time' => 'nullable|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
                'documents_verified' => 'nullable|boolean',
                'special_requests' => 'nullable|string|max:500',
                'early_checkin_fee_accepted' => 'nullable|boolean'
            ]);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room'])->find($bookingId);
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check if booking can be checked in
            if (!in_array($booking->status, ['Confirmed', 'Pending'])) {
                return response()->json([
                    'error' => 'Booking không thể check-in',
                    'current_status' => $booking->status
                ], 400);
            }

            // Check all conditions before processing
            $paymentStatus = $this->checkPaymentStatus($booking);
            $roomsAssigned = $this->checkRoomsAssigned($booking);
            $documentsVerified = $this->checkDocumentsVerification($booking);

            // Validate conditions
            if (!$paymentStatus['is_sufficient']) {
                return response()->json([
                    'error' => 'Chưa thanh toán đủ để check-in',
                    'payment_details' => $paymentStatus
                ], 400);
            }

            if (!$roomsAssigned['has_rooms']) {
                return response()->json([
                    'error' => 'Chưa có phòng được gán cho booking này',
                    'room_details' => $roomsAssigned
                ], 400);
            }

            if (!$documentsVerified['is_verified'] && !($validated['documents_verified'] ?? false)) {
                return response()->json([
                    'error' => 'Chưa xác nhận giấy tờ khách hàng',
                    'document_details' => $documentsVerified
                ], 400);
            }

            // Get applicable policies
            $applicablePolicies = $this->getApplicableCheckinPolicies($booking);
            
            // Check early check-in and calculate fees
            $actualCheckinTime = $validated['actual_checkin_time'] ?? Carbon::now()->format('H:i');
            $earlyCheckinInfo = $this->checkEarlyCheckinPolicy($booking, $applicablePolicies, $actualCheckinTime);
            
            // If early check-in fee required but not accepted
            if ($earlyCheckinInfo['has_fee'] && !($validated['early_checkin_fee_accepted'] ?? false)) {
                return response()->json([
                    'error' => 'Cần xác nhận thanh toán phí check-in sớm',
                    'early_checkin_info' => $earlyCheckinInfo
                ], 400);
            }

            // Process check-in within transaction
            $checkinResult = null;
            DB::transaction(function () use ($booking, $validated, $earlyCheckinInfo, $actualCheckinTime, &$checkinResult) {
                
                // Create early check-in payment if needed
                $earlyCheckinPaymentId = null;
                if ($earlyCheckinInfo['has_fee'] && $earlyCheckinInfo['fee_amount'] > 0) {
                    $earlyCheckinPayment = Payment::create([
                        'booking_id' => $booking->booking_id,
                        'amount_vnd' => $earlyCheckinInfo['fee_amount'],
                        'payment_type' => 'early_checkin_fee',
                        'status' => 'completed',
                        'transaction_id' => 'EARLY_CHECKIN_' . $booking->booking_code . '_' . time(),
                        'created_at' => Carbon::now(),
                    ]);
                    $earlyCheckinPaymentId = $earlyCheckinPayment->payment_id;
                    
                    // Update booking total price
                    $booking->total_price_vnd += $earlyCheckinInfo['fee_amount'];
                }

                // Update booking status to checked-in
                $booking->status = 'Operational';
                $booking->notes = ($booking->notes ? $booking->notes . "\n" : '') . 
                                ($validated['notes'] ?? 'Check-in completed at ' . Carbon::now()->format('Y-m-d H:i:s'));
                $booking->save();

                // Get room IDs for response (but don't update room status)
                $roomIds = DB::table('booking_rooms')
                    ->where('booking_id', $booking->booking_id)
                    ->pluck('room_id')
                    ->toArray();

                // Create check-in request record with only existing columns
                $checkinRequestData = [
                    'booking_id' => $booking->booking_id,
                    'status' => 'approved',
                    'notes' => $validated['notes'] ?? 'Check-in processed successfully',
                    'created_at' => Carbon::now(),
                ];

                // Add optional fields only if they exist in the table
                if ($earlyCheckinInfo['policy_id']) {
                    $checkinRequestData['policy_id'] = $earlyCheckinInfo['policy_id'];
                }

                if ($earlyCheckinInfo['fee_amount'] ?? 0 > 0) {
                    $checkinRequestData['fee_amount_vnd'] = $earlyCheckinInfo['fee_amount'];
                }

                if ($earlyCheckinPaymentId) {
                    $checkinRequestData['payment_id'] = $earlyCheckinPaymentId;
                }

                if (Auth::id()) {
                    $checkinRequestData['processed_by'] = Auth::id();
                }

                if ($validated['special_requests'] ?? null) {
                    $checkinRequestData['special_requests'] = $validated['special_requests'];
                }

                // Try to create the check-in request, but handle missing columns gracefully
                $checkinRequestId = null;
                try {
                    $checkinRequest = CheckInRequest::create($checkinRequestData);
                    $checkinRequestId = $checkinRequest->id;
                } catch (\Exception $e) {
                    Log::warning("Could not create check-in request: " . $e->getMessage());
                    // Try with minimal data
                    try {
                        $minimalData = [
                            'booking_id' => $booking->booking_id,
                            'status' => 'approved',
                            'created_at' => Carbon::now(),
                        ];
                        $checkinRequest = CheckInRequest::create($minimalData);
                        $checkinRequestId = $checkinRequest->id;
                    } catch (\Exception $e2) {
                        Log::error("Failed to create check-in request with minimal data: " . $e2->getMessage());
                        // Continue without check-in request record
                    }
                }

                // Create audit log if audit_logs table exists
                try {
                    DB::table('audit_logs')->insert([
                        'user_id' => Auth::id(),
                        'action' => 'Check-in',
                        'table_name' => 'booking',
                        'record_id' => $booking->booking_id,
                        'description' => "Check-in completed for booking {$booking->booking_code} at {$actualCheckinTime}" . 
                                       ($earlyCheckinInfo['has_fee'] ? " with early check-in fee: " . number_format($earlyCheckinInfo['fee_amount']) . " VND" : ""),
                        'created_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    // Log the error but don't fail the transaction
                    Log::warning("Could not create audit log: " . $e->getMessage());
                }

                $checkinResult = [
                    'checkin_request_id' => $checkinRequestId,
                    'early_checkin_payment_id' => $earlyCheckinPaymentId,
                    'room_ids' => $roomIds
                ];
            });

            // Prepare response
            $responseData = [
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'guest_name' => $booking->guest_name,
                'status' => $booking->status,
                'checkin_time' => $actualCheckinTime,
                'checkin_date' => Carbon::now()->format('Y-m-d'),
                'total_price_vnd' => $booking->total_price_vnd,
                'early_checkin_info' => $earlyCheckinInfo,
                'room_ids' => $checkinResult['room_ids'],
                'checkin_request_id' => $checkinResult['checkin_request_id'],
                'processed_by' => Auth::id(),
                'processed_at' => Carbon::now()->toDateTimeString()
            ];

            Log::info('=== BookingCheckinController@processCheckin SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công' . ($earlyCheckinInfo['has_fee'] ? ' (đã thu phí check-in sớm)' : ''),
                'data' => $responseData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in processCheckin ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi check-in',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room information for a booking
     */
    private function getRoomInformation($booking)
    {
        try {
            $rooms = DB::table('booking_rooms as br')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('br.booking_id', $booking->booking_id)
                ->select('r.*', 'rt.name as room_type_name', 'rt.description as room_type_description')
                ->get();

            return [
                'total_rooms' => $rooms->count(),
                'rooms' => $rooms->map(function ($room) {
                    return [
                        'room_id' => $room->room_id,
                        'room_number' => $room->room_number ?? $room->name,
                        'room_type' => $room->room_type_name,
                        'floor' => $room->floor ?? null,
                        'status' => $room->status
                    ];
                })->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Error getting room information for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'total_rooms' => 0,
                'rooms' => []
            ];
        }
    }

    /**
     * Check payment status for a booking
     */
    private function checkPaymentStatus($booking)
    {
        try {
            $totalPaid = Payment::where('booking_id', $booking->booking_id)
                ->where('status', 'completed')
                ->sum('amount_vnd');

            $totalRequired = $booking->total_price_vnd;
            $remainingAmount = $totalRequired - $totalPaid;

            return [
                'total_required' => $totalRequired,
                'total_paid' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'is_sufficient' => $remainingAmount <= 0,
                'payment_percentage' => $totalRequired > 0 ? ($totalPaid / $totalRequired) * 100 : 100
            ];
        } catch (\Exception $e) {
            Log::error("Error checking payment status for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'total_required' => $booking->total_price_vnd,
                'total_paid' => 0,
                'remaining_amount' => $booking->total_price_vnd,
                'is_sufficient' => false,
                'payment_percentage' => 0
            ];
        }
    }

    /**
     * Check if rooms are assigned to the booking
     */
    private function checkRoomsAssigned($booking)
    {
        try {
            $assignedRooms = DB::table('booking_rooms')
                ->where('booking_id', $booking->booking_id)
                ->whereNotNull('room_id')
                ->count();

            return [
                'has_rooms' => $assignedRooms > 0,
                'assigned_rooms_count' => $assignedRooms,
                'required_rooms_count' => $booking->quantity ?? 1
            ];
        } catch (\Exception $e) {
            Log::error("Error checking room assignment for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'has_rooms' => false,
                'assigned_rooms_count' => 0,
                'required_rooms_count' => $booking->quantity ?? 1
            ];
        }
    }

    /**
     * Check documents verification status
     */
    private function checkDocumentsVerification($booking)
    {
        try {
            // This would typically check a documents table or booking field
            // For now, we'll assume it's verified if guest info is complete
            $isVerified = !empty($booking->guest_name) && 
                         !empty($booking->guest_email) && 
                         !empty($booking->guest_phone);

            return [
                'is_verified' => $isVerified,
                'verification_details' => [
                    'has_name' => !empty($booking->guest_name),
                    'has_email' => !empty($booking->guest_email),
                    'has_phone' => !empty($booking->guest_phone)
                ]
            ];
        } catch (\Exception $e) {
            Log::error("Error checking document verification for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'is_verified' => false,
                'verification_details' => [
                    'has_name' => false,
                    'has_email' => false,
                    'has_phone' => false
                ]
            ];
        }
    }

    /**
     * Get applicable check-in policies
     */
    private function getApplicableCheckinPolicies($booking)
    {
        try {
            $checkInDate = $this->safeParseDate($booking->check_in_date);
            if (!$checkInDate) {
                Log::warning("Invalid check-in date for booking {$booking->booking_id}: {$booking->check_in_date}");
                return [];
            }

            $isHoliday = DB::table('holidays')
                ->where('start_date', '<=', $checkInDate->format('Y-m-d'))
                ->where('end_date', '>=', $checkInDate->format('Y-m-d'))
                ->exists();
            $isWeekend = in_array($checkInDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);

            $policies = CheckinPolicy::where('is_active', true)
                ->orderBy('priority', 'desc')
                ->get();

            return $policies->map(function ($policy) use ($isHoliday, $isWeekend) {
                return [
                    'policy_id' => $policy->policy_id,
                    'name' => $policy->name,
                    'description' => $policy->description,
                    'standard_check_in_time' => $policy->standard_check_in_time,
                    'early_check_in_fee_vnd' => $policy->early_check_in_fee_vnd,
                    'priority' => $policy->priority,
                    'conditions' => $policy->conditions,
                    'action' => $policy->action
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error("Error getting applicable policies for booking {$booking->booking_id}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check early check-in policy and calculate fees
     */
    private function checkEarlyCheckinPolicy($booking, $policies, $actualTime = null)
    {
        try {
            if (empty($policies)) {
                return [
                    'is_early' => false,
                    'has_fee' => false,
                    'fee_amount' => 0,
                    'policy_id' => null,
                    'policy_name' => null
                ];
            }

            $actualTime = $actualTime ?? Carbon::now()->format('H:i');
            
            // Find the most applicable policy (highest priority)
            $applicablePolicy = collect($policies)->first();
            
            if (!$applicablePolicy || !$applicablePolicy['standard_check_in_time']) {
                return [
                    'is_early' => false,
                    'has_fee' => false,
                    'fee_amount' => 0,
                    'policy_id' => $applicablePolicy['policy_id'] ?? null,
                    'policy_name' => $applicablePolicy['name'] ?? null
                ];
            }

            $standardTime = $this->safeParseTime($applicablePolicy['standard_check_in_time'], '14:00');
            $requestedTime = $this->safeParseTime($actualTime);
            
            if (!$standardTime || !$requestedTime) {
                Log::warning("Failed to parse times for early checkin policy check");
                return [
                    'is_early' => false,
                    'has_fee' => false,
                    'fee_amount' => 0,
                    'policy_id' => $applicablePolicy['policy_id'],
                    'policy_name' => $applicablePolicy['name']
                ];
            }
            
            $isEarly = $requestedTime->lt($standardTime);
            $feeAmount = $isEarly ? ($applicablePolicy['early_check_in_fee_vnd'] ?? 0) : 0;

            return [
                'is_early' => $isEarly,
                'has_fee' => $feeAmount > 0,
                'fee_amount' => $feeAmount,
                'policy_id' => $applicablePolicy['policy_id'],
                'policy_name' => $applicablePolicy['name'],
                'standard_time' => $standardTime->format('H:i'),
                'actual_time' => $requestedTime->format('H:i'),
                'time_difference_minutes' => $isEarly ? $standardTime->diffInMinutes($requestedTime) : 0
            ];
        } catch (\Exception $e) {
            Log::error("Error checking early checkin policy: " . $e->getMessage());
            return [
                'is_early' => false,
                'has_fee' => false,
                'fee_amount' => 0,
                'policy_id' => null,
                'policy_name' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get check-in warnings
     */
    private function getCheckinWarnings($booking, $paymentStatus, $roomsAssigned, $documentsVerified)
    {
        $warnings = [];

        if (!$paymentStatus['is_sufficient']) {
            $warnings[] = [
                'type' => 'payment',
                'message' => 'Chưa thanh toán đủ',
                'details' => "Còn thiếu " . number_format($paymentStatus['remaining_amount']) . " VND"
            ];
        }

        if (!$roomsAssigned['has_rooms']) {
            $warnings[] = [
                'type' => 'room',
                'message' => 'Chưa có phòng được gán',
                'details' => 'Cần gán phòng trước khi check-in'
            ];
        }

        if (!$documentsVerified['is_verified']) {
            $warnings[] = [
                'type' => 'documents',
                'message' => 'Chưa xác nhận giấy tờ',
                'details' => 'Cần xác nhận giấy tờ khách hàng'
            ];
        }

        return $warnings;
    }

    /**
     * Get check-in requirements
     */
    private function getCheckinRequirements($booking, $policies)
    {
        $requirements = [
            'payment_required' => true,
            'room_assignment_required' => true,
            'document_verification_required' => true,
            'early_checkin_fee_may_apply' => !empty($policies),
            'policies' => $policies
        ];

        return $requirements;
    }
}