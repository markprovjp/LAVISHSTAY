<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\CompensationPolicy;
use App\Models\CompensationRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Services\Checkout\CheckoutRuleEngine;
use App\Notifications\CheckoutCompletedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookingCheckoutController extends Controller
{
    private CheckoutRuleEngine $ruleEngine;

    public function __construct(CheckoutRuleEngine $ruleEngine)
    {
        $this->ruleEngine = $ruleEngine;
    }

    /**
     * Get checkout information for a specific booking
     */
    public function getCheckoutInfo(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@getCheckoutInfo START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Booking ID: ' . $bookingId);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                Log::error('Invalid booking ID', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking with relationships
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room', 'bookingServices.service'])
                ->find($bookingId);
            
            if (!$booking) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            Log::info('Found booking: ' . $booking->booking_code);

            // Get booking services and calculate total
            $bookingServices = $this->getBookingServices($booking);
            $amountCalculation = $this->calculateTotalAmount($booking, $bookingServices);

            // Run checkout validation using rule engine
            $validationResult = $this->ruleEngine->validate($booking, [
                'total_amount' => $amountCalculation['total_amount'],
                'booking_services' => $bookingServices,
                'amount_calculation' => $amountCalculation
            ]);

            // Get additional information
            $roomInfo = $this->getRoomInformation($booking);
            $hotelInfo = $this->getHotelInformation($booking);
            $guestInfo = $this->getGuestInformation($booking);

            // Get available services
            $availableServices = Service::active()
                ->included()
                ->orderBy('name')
                ->get()
                ->map(function ($service) {
                    return [
                        'service_id' => $service->service_id,
                        'name' => $service->name,
                        'description' => $service->description,
                        'price_vnd' => $service->price_vnd,
                        'unit' => $service->unit,
                        'formatted_price' => $service->formatted_price,
                        'price_with_unit' => $service->price_with_unit
                    ];
                });

            // Get payment status
            $paymentStatus = $this->getPaymentStatus($booking, $amountCalculation['total_amount']);

            // Get active compensation policies
            $compensationPolicies = CompensationPolicy::active()
                ->orderBy('name')
                ->get()
                ->map(function ($policy) {
                    return [
                        'compensation_policy_id' => $policy->compensation_policy_id,
                        'name' => $policy->name,
                        'description' => $policy->description,
                        'condition_type' => $policy->condition_type,
                        'condition_type_label' => $policy->condition_type_label,
                        'discount_type' => $policy->discount_type,
                        'discount_type_label' => $policy->discount_type_label,
                        'discount_value' => $policy->discount_value,
                        'formatted_discount_value' => $policy->formatted_discount_value,
                        'max_compensation_amount' => $policy->max_compensation_amount,
                        'applies_to_room_type_id' => $policy->applies_to_room_type_id
                    ];
                });

            // Check for existing compensation requests
            $existingCompensationRequests = CompensationRequest::where('booking_id', $booking->booking_id)
                ->with(['policy', 'requestedBy', 'approvedBy'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($request) {
                    return [
                        'request_id' => $request->request_id,
                        'policy_name' => $request->policy ? $request->policy->name : 'Yêu cầu khác',
                        'custom_reason' => $request->custom_reason,
                        'status' => $request->status,
                        'status_label' => $request->status_label,
                        'requested_amount' => $request->requested_amount,
                        'approved_amount' => $request->approved_amount,
                        'formatted_approved_amount' => $request->formatted_approved_amount,
                        'requested_by' => $request->requestedBy ? $request->requestedBy->name : null,
                        'approved_by' => 1,
                        'approved_at' => $request->approved_at,
                        'admin_note' => $request->admin_note,
                        'attachments' => $request->attachments,
                        'created_at' => $request->created_at
                    ];
                });

            // Prepare checkout information with rule validation
            $checkoutInfo = [
                'booking_info' => [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'status' => $booking->status,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'original_total_price_vnd' => $booking->total_price_vnd,
                    'guest_count' => $booking->guest_count,
                    'adults' => $booking->adults,
                    'children' => $booking->children,
                    'notes' => $booking->notes
                ],
                'guest_info' => $guestInfo,
                'room_info' => $roomInfo,
                'hotel_info' => $hotelInfo,
                'available_services' => [
                    'total_services' => $availableServices->count(),
                    'services' => $availableServices->toArray()
                ],
                'booking_services' => $bookingServices,
                'amount_calculation' => $amountCalculation,
                'payment_status' => $paymentStatus,
                
                // Rule-based validation results
                'checkout_validation' => $validationResult->getDetailedResults(),
                'checkout_conditions' => [
                    'payment_sufficient' => $paymentStatus['is_sufficient'],
                    'ready_for_checkout' => $validationResult->canCheckout,
                    'has_warnings' => $validationResult->hasWarnings(),
                    'has_blocking_issues' => $validationResult->hasBlockingFailures()
                ],

                // Compensation workflow
                'compensation_policies' => [
                    'total_policies' => $compensationPolicies->count(),
                    'policies' => $compensationPolicies->toArray()
                ],
                'existing_compensation_requests' => [
                    'total_requests' => $existingCompensationRequests->count(),
                    'requests' => $existingCompensationRequests->toArray()
                ],
                
                'checkout_summary' => [
                    'can_checkout' => $validationResult->canCheckout,
                    'validation_status' => $validationResult->canCheckout ? 'APPROVED' : 'BLOCKED',
                    'total_stay_amount' => $amountCalculation['total_amount'],
                    'remaining_payment' => $paymentStatus['remaining_amount'],
                    'checkout_date' => Carbon::now()->format('Y-m-d'),
                    'checkout_time' => Carbon::now()->format('H:i'),
                    'blocking_issues_count' => count($validationResult->blockingFailures),
                    'warnings_count' => count($validationResult->warnings),
                    'has_compensation_requests' => $existingCompensationRequests->count() > 0
                ]
            ];

            Log::info('=== BookingCheckoutController@getCheckoutInfo SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin check-out thành công',
                'data' => $checkoutInfo
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in getCheckoutInfo ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin check-out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process normal checkout for a booking
     */
    public function processCheckout(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@processCheckout START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Booking ID: ' . $bookingId);
            Log::info('Request data: ', $request->all());

            // Require authenticated user to create a compensation request.
            // If standard Auth is not populated, try to resolve a Sanctum personal access token
            if (!Auth::check()) {
                $bearer = $request->bearerToken();
                if ($bearer) {
                    try {
                        // Use fully-qualified class name to avoid import issues
                        $patClass = '\\Laravel\\Sanctum\\PersonalAccessToken';
                        if (class_exists($patClass)) {
                            $pat = $patClass::findToken($bearer);
                            if ($pat && $pat->tokenable) {
                                // Set the current user on the auth guard
                                auth()->setUser($pat->tokenable);
                                Log::info('Authenticated request via Sanctum token', ['user_id' => auth()->id()]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('Error resolving Sanctum token for compensation request', ['error' => $e->getMessage()]);
                    }
                }

                if (!Auth::check()) {
                    Log::warning('Unauthorized attempt to create compensation request', ['booking_id' => $bookingId]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 401);
                }
            }

            // Validate input
            $validated = $request->validate([
                'checkout_time' => 'nullable|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
                'final_payment_method' => 'nullable|string|in:cash,card,transfer,vietqr',
                'send_invoice' => 'nullable|boolean',
                'guest_email_for_invoice' => 'nullable|email',
                'override_warnings' => 'nullable|boolean',
                'force_checkout' => 'nullable|boolean'
            ]);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                Log::error('Invalid booking ID', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking with relationships
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room', 'bookingServices.service'])
                ->find($bookingId);
            
            if (!$booking) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Get booking services and calculate total
            $bookingServices = $this->getBookingServices($booking);
            $amountCalculation = $this->calculateTotalAmount($booking, $bookingServices);

            // Run checkout validation using rule engine
            $validationResult = $this->ruleEngine->validate($booking, [
                'total_amount' => $amountCalculation['total_amount'],
                'booking_services' => $bookingServices,
                'amount_calculation' => $amountCalculation
            ]);

            // Check if checkout is allowed
            if (!$validationResult->canCheckout && !($validated['force_checkout'] ?? false)) {
                Log::error('Checkout blocked by validation rules', [
                    'booking_id' => $bookingId,
                    'blocking_failures' => $validationResult->blockingFailures
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể check-out do không đáp ứng các điều kiện bắt buộc',
                    'validation_result' => $validationResult->getDetailedResults(),
                    'blocking_issues' => $validationResult->blockingFailures,
                    'can_force_checkout' => (Auth::check() && Auth::user()->hasRole('admin')), // Guard null user
                ], 400);
            }

            // If has warnings and not overridden, return warning
            if ($validationResult->hasWarnings() && !($validated['override_warnings'] ?? false)) {
                Log::warning('Checkout has warnings requiring confirmation', [
                    'booking_id' => $bookingId,
                    'warnings' => $validationResult->warnings
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Có cảnh báo cần xác nhận trước khi check-out',
                    'validation_result' => $validationResult->getDetailedResults(),
                    'warnings' => $validationResult->warnings,
                    'require_confirmation' => true,
                ], 422);
            }

            $paymentStatus = $this->getPaymentStatus($booking, $amountCalculation['total_amount']);

            // Get additional information
            $roomInfo = $this->getRoomInformation($booking);
            $hotelInfo = $this->getHotelInformation($booking);
            $guestInfo = $this->getGuestInformation($booking);

            // Process checkout within transaction
            $checkoutResult = null;
            DB::transaction(function () use ($booking, $validated, $amountCalculation, $paymentStatus, $bookingServices, $validationResult, &$checkoutResult) {
                
                $checkoutTime = $validated['checkout_time'] ?? Carbon::now()->format('H:i');
                $checkoutDateTime = Carbon::now();

                // Update booking status to Cleaning (not Completed)
                $booking->status = 'Cleaning';
                $booking->notes = ($booking->notes ? $booking->notes . "\n" : '') . 
                                ($validated['notes'] ?? 'Check-out completed at ' . $checkoutDateTime->format('Y-m-d H:i:s'));
                
                // Add validation summary to notes
                if ($validationResult->hasWarnings()) {
                    $booking->notes .= "\nWarnings overridden: " . count($validationResult->warnings) . " warnings";
                }
                
                // Update total price if there are additional services
                if ($amountCalculation['total_amount'] != $booking->total_price_vnd) {
                    $booking->total_price_vnd = $amountCalculation['total_amount'];
                }
                
                $booking->save();

                // Create final payment record if there was remaining amount
                $finalPaymentId = null;
                if ($paymentStatus['remaining_amount'] > 0) {
                    $finalPayment = Payment::create([
                        'booking_id' => $booking->booking_id,
                        'amount_vnd' => $paymentStatus['remaining_amount'],
                        'payment_type' => $validated['final_payment_method'] ?? 'cash',
                        'status' => 'completed',
                        'transaction_id' => 'CHECKOUT_' . $booking->booking_code . '_' . time(),
                        'created_at' => $checkoutDateTime,
                    ]);
                    $finalPaymentId = $finalPayment->payment_id;
                }

                // Create invoice using the Invoice model
                $invoice = Invoice::create([
                    'booking_id' => $booking->booking_id,
                    'total_amount_vnd' => $amountCalculation['total_amount'],
                    'issued_at' => $checkoutDateTime,
                    'status' => ($validated['send_invoice'] ?? false) ? 'Sent' : 'Draft'
                ]);

                // Update room status to cleaning
                $roomIds = $this->updateRoomStatusToCleaning($booking);

                // Create audit log with validation results
                $this->createAuditLog($booking, $checkoutTime, $amountCalculation, $validationResult);

                $checkoutResult = [
                    'invoice_id' => $invoice->invoice_id,
                    'final_payment_id' => $finalPaymentId,
                    'checkout_datetime' => $checkoutDateTime,
                    'room_ids' => $roomIds,
                    'validation_result' => $validationResult
                ];
            });

            // Send notification to guest user after successful checkout
            try {
                if ($booking->user) {
                    $booking->user->notify(new CheckoutCompletedNotification($booking));
                    Log::info('Checkout notification sent to user', ['user_id' => $booking->user->id, 'booking_id' => $booking->booking_id]);
                } else {
                    Log::warning('No user associated with booking for notification', ['booking_id' => $booking->booking_id]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send checkout notification', [
                    'booking_id' => $booking->booking_id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the checkout if notification fails
            }

            // Prepare comprehensive response
            $responseData = [
                'booking_info' => [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'status' => $booking->status, // Now "Cleaning"
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'final_total_price_vnd' => $booking->total_price_vnd,
                    'notes' => $booking->notes
                ],
                'guest_info' => $guestInfo,
                'room_info' => $roomInfo,
                'hotel_info' => $hotelInfo,
                'checkout_details' => [
                    'checkout_time' => $validated['checkout_time'] ?? Carbon::now()->format('H:i'),
                    'checkout_date' => Carbon::now()->format('Y-m-d'),
                    'checkout_datetime' => $checkoutResult['checkout_datetime']->toDateTimeString(),
                    'processed_by' => Auth::id(),
                    'invoice_id' => $checkoutResult['invoice_id'],
                    'validation_overrides' => [
                        'warnings_overridden' => $validated['override_warnings'] ?? false,
                        'force_checkout' => $validated['force_checkout'] ?? false
                    ]
                ],
                'booking_services' => $bookingServices,
                'amount_calculation' => $amountCalculation,
                'final_payment_status' => [
                    'total_amount' => $amountCalculation['total_amount'],
                    'total_paid' => $amountCalculation['total_amount'], // Now fully paid
                    'remaining_amount' => 0,
                    'is_sufficient' => true,
                    'final_payment_id' => $checkoutResult['final_payment_id']
                ],
                'invoice_info' => [
                    'invoice_id' => $checkoutResult['invoice_id'],
                    'total_amount_vnd' => $amountCalculation['total_amount'],
                    'status' => ($validated['send_invoice'] ?? false) ? 'Sent' : 'Draft',
                    'issued_at' => $checkoutResult['checkout_datetime']->toDateTimeString()
                ],
                'validation_summary' => $validationResult->getSummary(),
                'checkout_summary' => [
                    'checkout_completed' => true,
                    'booking_status' => 'Cleaning',
                    'invoice_created' => true,
                    'invoice_sent' => $validated['send_invoice'] ?? false,
                    'rooms_set_to_cleaning' => !empty($checkoutResult['room_ids']),
                    'validation_status' => $validationResult->canCheckout ? 'APPROVED' : 'FORCED',
                    'warnings_count' => count($validationResult->warnings),
                    'next_steps' => [
                        'Khách đã check-out thành công',
                        'Booking chuyển sang trạng thái "Cleaning"',
                        'Hóa đơn đã được tạo',
                        'Phòng đã được chuyển sang trạng thái dọn dẹp',
                        'Thông báo cho bộ phận housekeeping',
                        'Sau khi dọn dẹp xong, chuyển booking sang "Completed"'
                    ]
                ]
            ];

            Log::info('=== BookingCheckoutController@processCheckout SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công. Booking đã chuyển sang trạng thái dọn dẹp.',
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
            Log::error('=== ERROR in processCheckout ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi check-out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create compensation request for special cases
     */
    public function createCompensationRequest(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@createCompensationRequest START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Booking ID: ' . $bookingId);
            Log::info('Request data: ', $request->all());

            // Validate input
            $validated = $request->validate([
                'policy_id' => 'nullable|integer|exists:compensation_policies,compensation_policy_id',
                'custom_reason' => 'nullable|string|max:2000',
                'requested_amount' => 'nullable|numeric|min:0',
                'requested_by' => 'nullable|integer|exists:users,id',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB max per file
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

            // Check if policy_id or custom_reason is provided
            if (empty($validated['policy_id']) && empty($validated['custom_reason'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phải chọn chính sách bồi thường hoặc nhập lý do tùy chỉnh',
                    'errors' => [
                        'policy_id' => ['Phải chọn chính sách hoặc nhập lý do tùy chỉnh'],
                        'custom_reason' => ['Phải chọn chính sách hoặc nhập lý do tùy chỉnh']
                    ]
                ], 422);
            }

            // Handle file uploads
            $attachmentPaths = [];
            if (!empty($validated['attachments'])) {
                $targetDir = public_path('compensation_attachments');
                if (!is_dir($targetDir)) {
                    try {
                        mkdir($targetDir, 0755, true);
                    } catch (\Exception $e) {
                        Log::warning('Failed to create compensation attachments directory', ['dir' => $targetDir, 'error' => $e->getMessage()]);
                    }
                }

                foreach ($validated['attachments'] as $index => $file) {
                    // Capture metadata before moving the uploaded file
                    $originalName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    $mimeType = $file->getMimeType();

                    $fileName = time() . '_' . $index . '_' . $originalName;
                    $filePath = 'compensation_attachments/' . $fileName;

                    try {
                        // Move file to public directory
                        $file->move($targetDir, $fileName);
                    } catch (\Exception $e) {
                        // Log and continue; record a failed-upload marker
                        Log::error('Failed to move uploaded compensation attachment', ['error' => $e->getMessage(), 'original_name' => $originalName]);
                        // Optionally, you may throw here to abort the whole request
                        throw $e;
                    }

                    $attachmentPaths[] = [
                        'original_name' => $originalName,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'uploaded_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }

            // Get policy details if policy_id is provided
            $policy = null;
            $calculatedAmount = null;
            if (!empty($validated['policy_id'])) {
                $policy = CompensationPolicy::find($validated['policy_id']);
                if (!$policy || !$policy->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Chính sách bồi thường không tồn tại hoặc không hoạt động'
                    ], 404);
                }

                // Calculate compensation amount based on policy
                $bookingServices = $this->getBookingServices($booking);
                $amountCalculation = $this->calculateTotalAmount($booking, $bookingServices);
                $calculatedAmount = $policy->calculateCompensation($amountCalculation['total_amount']);
            }

            // Create compensation request within transaction
            $compensationRequest = null;
            DB::transaction(function () use ($booking, $validated, $attachmentPaths, $calculatedAmount, &$compensationRequest) {
                // Use validated requested_by when provided (fallback) to support CLI/testing flows
                $requestedBy = $validated['requested_by'] ?? Auth::id();

                $compensationRequest = CompensationRequest::create([
                    'booking_id' => $booking->booking_id,
                    'requested_by' => $requestedBy,
                    'policy_id' => $validated['policy_id'] ?? null,
                    'custom_reason' => $validated['custom_reason'] ?? null,
                    'status' => 'pending',
                    'requested_amount' => $validated['requested_amount'] ?? $calculatedAmount,
                    'attachments' => $attachmentPaths,
                    'created_at' => Carbon::now(),
                ]);

                // Create audit log (record the acting user or provided requested_by)
                    DB::table('audit_logs')->insert([
                        'user_id' => $requestedBy,
                        'action' => 'create',
                        'model' => 'CompensationRequest',
                        'model_id' => $compensationRequest->request_id,
                        'description' => "Created compensation request for booking {$booking->booking_code}. " . 
                                       ($validated['policy_id'] ? "Policy ID: {$validated['policy_id']}" : "Custom reason provided"),
                        'created_at' => Carbon::now(),
                    ]);
            });

            // Prepare response data
            $responseData = [
                'compensation_request' => [
                    'request_id' => $compensationRequest->request_id,
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'policy_id' => $compensationRequest->policy_id,
                    'policy_name' => $policy ? $policy->name : null,
                    'custom_reason' => $compensationRequest->custom_reason,
                    'status' => $compensationRequest->status,
                    'status_label' => $compensationRequest->status_label,
                    'requested_amount' => $compensationRequest->requested_amount,
                    'formatted_requested_amount' => $compensationRequest->formatted_requested_amount,
                    'attachments' => $compensationRequest->attachments,
                    // Auth::user() may be null when token resolution differs; fallback to stored requested_by name
                    'requested_by' => (Auth::user() ? Auth::user()->name : (\App\Models\User::find($compensationRequest->requested_by)?->name ?? null)),
                    'created_at' => $compensationRequest->created_at
                ],
                'next_steps' => [
                    'Yêu cầu bồi thường đã được gửi',
                    'Trạng thái: Chờ duyệt',
                    'Quản lý sẽ xem xét và phê duyệt',
                    'Bạn sẽ nhận được thông báo khi có kết quả',
                    'Có thể tiếp tục theo dõi trạng thái yêu cầu'
                ]
            ];

            Log::info('=== BookingCheckoutController@createCompensationRequest SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Tạo yêu cầu bồi thường thành công. Đang chờ quản lý phê duyệt.',
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
            Log::error('=== ERROR in createCompensationRequest ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo yêu cầu bồi thường',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available services for booking
     */
    public function getAvailableServices(Request $request)
    {
        try {
            Log::info('=== BookingCheckoutController@getAvailableServices START ===', ['query' => $request->query()]);

            // Optional: if booking_id is passed, include current selection
            $bookingId = $request->query('booking_id');
            $selectedMap = collect();

            if (!empty($bookingId) && is_numeric($bookingId)) {
                $selectedMap = BookingService::where('booking_id', (int) $bookingId)
                    ->get()
                    ->keyBy('service_id');
            }

            // Fetch add-on services from services table
            $services = Service::active()
                ->included()
                ->orderBy('name')
                ->get()
                ->map(function ($service) use ($selectedMap) {
                    $selected = $selectedMap->get($service->service_id);

                    $selectedQuantity = $selected ? (int) $selected->quantity : 0;
                    $unitPrice = (float) $service->price_vnd;
                    $selectedTotal = $selectedQuantity * $unitPrice;

                    return [
                        'service_id' => $service->service_id,
                        'name' => $service->name,
                        'description' => $service->description,
                        'price_vnd' => $unitPrice,
                        'unit' => $service->unit,
                        'formatted_price' => $service->formatted_price,
                        'price_with_unit' => $service->price_with_unit,

                        // Current selection (if any)
                        'selected_quantity' => $selectedQuantity,
                        'selected_total_price_vnd' => $selectedTotal,
                    ];
                });

            $summary = null;
            if ($selectedMap->isNotEmpty()) {
                $summary = [
                    'selected_count' => $selectedMap->count(),
                    'total_selected_amount_vnd' => $selectedMap->values()->sum(function ($bs) {
                        return (int) $bs->quantity * (float) $bs->price_vnd;
                    }),
                ];
            }

            Log::info('=== BookingCheckoutController@getAvailableServices SUCCESS ===', [
                'count' => $services->count(),
                'booking_id' => $bookingId ? (int) $bookingId : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách dịch vụ bổ sung thành công',
                'data' => [
                    'total_services' => $services->count(),
                    'services' => $services->toArray(),
                    'current_selection_summary' => $summary,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('=== ERROR in getAvailableServices ===', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách dịch vụ bổ sung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add service to booking
     */
    public function addBookingService(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@addBookingService START ===', ['booking_id' => $bookingId, 'payload' => $request->all()]);

            // Validate booking id
            if (!is_numeric($bookingId)) {
                return response()->json(['success' => false, 'message' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Validate input: accept multiple selected services with quantities
            $validated = $request->validate([
                'services' => 'required|array|min:1',
                'services.*.service_id' => 'required|integer|exists:services,service_id',
                'services.*.quantity' => 'required|integer|min:1',
            ]);

            // Find booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check status allows adding services
            if (!in_array($booking->status, ['Confirmed', 'Operational'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thêm dịch vụ cho booking ở trạng thái hiện tại',
                    'current_status' => $booking->status,
                    'allowed_statuses' => ['Confirmed', 'Operational'],
                ], 400);
            }

            // Normalize duplicate service_ids in payload by summing quantities
            $incoming = collect($validated['services'])
                ->groupBy('service_id')
                ->map(fn($items) => [
                    'service_id' => (int) $items->first()['service_id'],
                    'quantity' => (int) $items->sum('quantity'),
                ])
                ->values();

            // Load services and validate they are active and included
            $serviceIds = $incoming->pluck('service_id')->all();
            $serviceRecords = Service::whereIn('service_id', $serviceIds)->get()->keyBy('service_id');

            $invalid = [];
            foreach ($serviceIds as $sid) {
                $svc = $serviceRecords->get($sid);
                if (!$svc || !$svc->is_active || !$svc->included_services) {
                    $invalid[] = $sid;
                }
            }

            if (!empty($invalid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Một hoặc nhiều dịch vụ không hợp lệ (không phải dịch vụ bổ sung hoặc không hoạt động)',
                    'invalid_service_ids' => $invalid,
                ], 422);
            }

            // Apply create/update in a transaction
            $results = [
                'created' => [],
                'updated' => [],
            ];

            DB::transaction(function () use ($bookingId, $incoming, $serviceRecords, &$results) {
                foreach ($incoming as $item) {
                    $sid = (int) $item['service_id'];
                    $qtyToAdd = (int) $item['quantity'];
                    $svc = $serviceRecords->get($sid);

                    // Find existing selection
                    $existing = BookingService::where('booking_id', $bookingId)
                        ->where('service_id', $sid)
                        ->lockForUpdate()
                        ->first();

                    if ($existing) {
                        $oldQty = (int) $existing->quantity;
                        $newQty = $oldQty + $qtyToAdd;

                        $existing->update([
                            'quantity' => $newQty,
                        ]);

                        $results['updated'][] = [
                            'service_id' => $sid,
                            'service_name' => $svc->name,
                            'old_quantity' => $oldQty,
                            'added_quantity' => $qtyToAdd,
                            'new_quantity' => $newQty,
                            'unit_price_vnd' => (float) $existing->price_vnd,
                            'total_price_vnd' => $newQty * (float) $existing->price_vnd,
                        ];
                    } else {
                        $bs = BookingService::create([
                            'booking_id' => $bookingId,
                            'service_id' => $sid,
                            'quantity' => $qtyToAdd,
                            'price_vnd' => $svc->price_vnd,
                            'created_at' => Carbon::now(),
                        ]);

                        $results['created'][] = [
                            'booking_service_id' => $bs->id,
                            'service_id' => $sid,
                            'service_name' => $svc->name,
                            'quantity' => $qtyToAdd,
                            'unit_price_vnd' => (float) $svc->price_vnd,
                            'total_price_vnd' => $qtyToAdd * (float) $svc->price_vnd,
                            'unit' => $svc->unit,
                        ];
                    }
                }
            });

            // Build updated summary
            $bookingServicesSummary = $this->getBookingServices($booking);

            Log::info('=== BookingCheckoutController@addBookingService SUCCESS ===', [
                'booking_id' => (int) $bookingId,
                'created' => count($results['created']),
                'updated' => count($results['updated']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ bổ sung cho booking thành công',
                'data' => [
                    'booking_id' => (int) $bookingId,
                    'results' => $results,
                    'booking_services_summary' => $bookingServicesSummary,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in addBookingService', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in addBookingService ===', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật dịch vụ bổ sung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update booking service quantity
     */
    public function updateBookingService(Request $request, $bookingId, $serviceId)
    {
        try {
            Log::info('=== BookingCheckoutController@updateBookingService START ===');
            Log::info('Booking ID: ' . $bookingId . ', Service ID: ' . $serviceId);
            Log::info('Request data: ', $request->all());

            // Validate input
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            // Validate IDs
            if (!is_numeric($bookingId) || !is_numeric($serviceId)) {
                return response()->json(['error' => 'ID không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check booking status
            if (!in_array($booking->status, ['Confirmed', 'Operational'])) {
                return response()->json([
                    'error' => 'Không thể cập nhật dịch vụ cho booking này',
                    'current_status' => $booking->status
                ], 400);
            }

            // Find booking service
            $bookingService = BookingService::where('booking_id', $bookingId)
                ->where('service_id', $serviceId)
                ->first();

            if (!$bookingService) {
                return response()->json(['error' => 'Không tìm thấy dịch vụ trong booking'], 404);
            }

            $oldQuantity = $bookingService->quantity;

            // Update quantity
            $bookingService->update([
                'quantity' => $validated['quantity']
            ]);

            // Get service info
            $service = $bookingService->service;

            // Get updated booking services
            $bookingServices = $this->getBookingServices($booking);

            Log::info('=== BookingCheckoutController@updateBookingService SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ thành công',
                'data' => [
                    'service_info' => [
                        'service_id' => $service->service_id,
                        'service_name' => $service->name,
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $validated['quantity'],
                        'unit_price_vnd' => $service->price_vnd,
                        'new_total_price_vnd' => $bookingService->total_price,
                        'unit' => $service->unit
                    ],
                    'booking_services_summary' => $bookingServices
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in updateBookingService ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật dịch vụ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove service from booking
     */
    public function removeBookingService(Request $request, $bookingId, $serviceId)
    {
        try {
            Log::info('=== BookingCheckoutController@removeBookingService START ===');
            Log::info('Booking ID: ' . $bookingId . ', Service ID: ' . $serviceId);

            // Validate IDs
            if (!is_numeric($bookingId) || !is_numeric($serviceId)) {
                return response()->json(['error' => 'ID không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check booking status
            if (!in_array($booking->status, ['Confirmed', 'Operational'])) {
                return response()->json([
                    'error' => 'Không thể xóa dịch vụ cho booking này',
                    'current_status' => $booking->status
                ], 400);
            }

            // Find booking service
            $bookingService = BookingService::where('booking_id', $bookingId)
                ->where('service_id', $serviceId)
                ->first();

            if (!$bookingService) {
                return response()->json(['error' => 'Không tìm thấy dịch vụ trong booking'], 404);
            }

            // Get service info before deletion
            $service = $bookingService->service;
            $removedServiceInfo = [
                'service_id' => $service->service_id,
                'service_name' => $service->name,
                'quantity' => $bookingService->quantity,
                'total_price_vnd' => $bookingService->total_price
            ];

            // Remove service
            $bookingService->delete();

            // Get updated booking services
            $bookingServices = $this->getBookingServices($booking);

            Log::info('=== BookingCheckoutController@removeBookingService SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công',
                'data' => [
                    'removed_service' => $removedServiceInfo,
                    'booking_services_summary' => $bookingServices
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in removeBookingService ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa dịch vụ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Private helper methods...

    /**
     * Get room information for a booking
     */
    private function getRoomInformation($booking)
    {
        try {
            $rooms = collect();
            
            // Try multiple possible table structures
            try {
                $roomsFromBookingRooms = DB::table('booking_rooms as br')
                    ->join('room as r', 'br.room_id', '=', 'r.room_id')
                    ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                    ->where('br.booking_id', $booking->booking_id)
                    ->select('r.*', 'rt.name as room_type_name', 'rt.description as room_type_description')
                    ->get();
                
                if ($roomsFromBookingRooms->count() > 0) {
                    $rooms = $roomsFromBookingRooms;
                }
            } catch (\Exception $e) {
                Log::warning("booking_rooms table query failed: " . $e->getMessage());
            }
            
            if ($rooms->count() === 0) {
                try {
                    $roomsFromBookingRoom = DB::table('booking_room as br')
                        ->join('room as r', 'br.room_id', '=', 'r.room_id')
                        ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                        ->where('br.booking_id', $booking->booking_id)
                        ->select('r.*', 'rt.name as room_type_name', 'rt.description as room_type_description')
                        ->get();
                    
                    if ($roomsFromBookingRoom->count() > 0) {
                        $rooms = $roomsFromBookingRoom;
                    }
                } catch (\Exception $e) {
                    Log::warning("booking_room table query failed: " . $e->getMessage());
                }
            }
            
            if ($rooms->count() === 0 && $booking->room_id) {
                try {
                    $roomFromBooking = DB::table('room as r')
                        ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                        ->where('r.room_id', $booking->room_id)
                        ->select('r.*', 'rt.name as room_type_name', 'rt.description as room_type_description')
                        ->get();
                    
                    if ($roomFromBooking->count() > 0) {
                        $rooms = $roomFromBooking;
                    }
                } catch (\Exception $e) {
                    Log::warning("Direct room query failed: " . $e->getMessage());
                }
            }

            return [
                'total_rooms' => $rooms->count(),
                'rooms' => $rooms->map(function ($room) {
                    return [
                        'room_id' => $room->room_id,
                        'room_number' => $room->room_number ?? $room->name,
                        'room_type' => $room->room_type_name,
                        'room_type_id' => $room->room_type_id,
                        'floor' => $room->floor ?? null,
                        'status' => $room->status,
                        'description' => $room->description ?? null
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
     * Get hotel information for a booking
     */
    private function getHotelInformation($booking)
    {
        try {
            $hotelInfo = null;
            
            try {
                $hotelInfo = DB::table('booking_rooms as br')
                    ->join('room as r', 'br.room_id', '=', 'r.room_id')
                    ->join('hotel as h', 'r.hotel_id', '=', 'h.hotel_id')
                    ->where('br.booking_id', $booking->booking_id)
                    ->select('h.*')
                    ->first();
            } catch (\Exception $e) {
                Log::warning("booking_rooms hotel query failed: " . $e->getMessage());
            }
            
            if (!$hotelInfo) {
                try {
                    $hotelInfo = DB::table('booking_room as br')
                        ->join('room as r', 'br.room_id', '=', 'r.room_id')
                        ->join('hotel as h', 'r.hotel_id', '=', 'h.hotel_id')
                        ->where('br.booking_id', $booking->booking_id)
                        ->select('h.*')
                        ->first();
                } catch (\Exception $e) {
                    Log::warning("booking_room hotel query failed: " . $e->getMessage());
                }
            }
            
            if (!$hotelInfo && $booking->room_id) {
                try {
                    $hotelInfo = DB::table('room as r')
                        ->join('hotel as h', 'r.hotel_id', '=', 'h.hotel_id')
                        ->where('r.room_id', $booking->room_id)
                        ->select('h.*')
                        ->first();
                } catch (\Exception $e) {
                    Log::warning("Direct hotel query failed: " . $e->getMessage());
                }
            }

            // Fallback: try resolving via Eloquent relations when schema doesn't have r.hotel_id
            if (!$hotelInfo) {
                try {
                    // If booking has bookingRooms relation, try each room's hotel relation
                    if (method_exists($booking, 'bookingRooms')) {
                        foreach ($booking->bookingRooms as $br) {
                            $roomId = $br->room_id ?? ($br->room_id ?? null);
                            if ($roomId) {
                                $room = \App\Models\Room::where('room_id', $roomId)->first();
                                if ($room && method_exists($room, 'hotel')) {
                                    $hotel = $room->hotel()->first();
                                    if ($hotel) {
                                        $hotelInfo = $hotel;
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    // Last resort: try booking->room_id via Room model
                    if (!$hotelInfo && property_exists($booking, 'room_id') && $booking->room_id) {
                        $room = \App\Models\Room::where('room_id', $booking->room_id)->first();
                        if ($room && method_exists($room, 'hotel')) {
                            $hotel = $room->hotel()->first();
                            if ($hotel) {
                                $hotelInfo = $hotel;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Eloquent hotel relation fallback failed: " . $e->getMessage());
                }
            }

            if ($hotelInfo) {
                return [
                    'hotel_id' => $hotelInfo->hotel_id,
                    'hotel_name' => $hotelInfo->name ?? 'Không xác định',
                    'hotel_address' => $hotelInfo->address ?? 'Không xác định',
                    'hotel_phone' => $hotelInfo->phone ?? 'Không xác định',
                    'hotel_email' => $hotelInfo->email ?? null
                ];
            }

            return [
                'hotel_id' => null,
                'hotel_name' => 'Không xác định',
                'hotel_address' => 'Không xác định',
                'hotel_phone' => 'Không xác định',
                'hotel_email' => null
            ];
        } catch (\Exception $e) {
            Log::error("Error getting hotel information for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'hotel_id' => null,
                'hotel_name' => 'Không xác định',
                'hotel_address' => 'Không xác định',
                'hotel_phone' => 'Không xác định',
                'hotel_email' => null
            ];
        }
    }

    /**
     * Get guest information for a booking
     */
    private function getGuestInformation($booking)
    {
        // Ensure adults/children are populated even when stored at booking_rooms level
        $adults = $booking->adults;
        $children = $booking->children;

        // If booking.adults is null, try summing booking rooms' adults
        if ($adults === null) {
            try {
                $sumAdults = $booking->bookingRooms()->sum('adults');
                // treat 0 as null if guest_count doesn't match
                $adults = $sumAdults > 0 ? (int) $sumAdults : null;
            } catch (\Exception $e) {
                $adults = null;
            }
        }

        // If booking.children is null, try summing booking rooms' children
        if ($children === null) {
            try {
                $sumChildren = $booking->bookingRooms()->sum('children');
                $children = $sumChildren > 0 ? (int) $sumChildren : null;
            } catch (\Exception $e) {
                $children = null;
            }
        }

        return [
            'guest_name' => $booking->guest_name,
            'guest_email' => $booking->guest_email,
            'guest_phone' => $booking->guest_phone,
            'guest_count' => $booking->guest_count,
            'adults' => $adults,
            'children' => $children,
            'children_age' => $booking->children_age
        ];
    }

    /**
     * Get booking services (additional services during stay)
     */
    private function getBookingServices($booking)
    {
        try {
            // Use the relationship to get booking services with service details
            $bookingServices = $booking->bookingServices()->with('service')->get();

            $totalServiceAmount = BookingService::calculateTotalAmount($bookingServices);

            return [
                'total_services' => $bookingServices->count(),
                'total_service_amount' => $totalServiceAmount,
                'services' => $bookingServices->map(function ($bookingService) {
                    return [
                        'id' => $bookingService->id,
                        'service_id' => $bookingService->service_id,
                        'service_name' => $bookingService->service->name,
                        'service_description' => $bookingService->service->description,
                        'quantity' => $bookingService->quantity,
                        'unit_price_vnd' => $bookingService->price_vnd,
                        'total_price_vnd' => $bookingService->total_price,
                        'unit' => $bookingService->service->unit,
                        'created_at' => $bookingService->created_at
                    ];
                })->toArray()
            ];
        } catch (\Exception $e) {
            Log::error("Error getting booking services for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'total_services' => 0,
                'total_service_amount' => 0,
                'services' => []
            ];
        }
    }

    /**
     * Calculate total amount (room + services)
     */
    private function calculateTotalAmount($booking, $bookingServices)
    {
        $roomAmount = $booking->total_price_vnd;
        $serviceAmount = $bookingServices['total_service_amount'];
        $totalAmount = $roomAmount + $serviceAmount;

        return [
            'room_amount' => $roomAmount,
            'service_amount' => $serviceAmount,
            'total_amount' => $totalAmount,
            'breakdown' => [
                'original_booking_amount' => $roomAmount,
                'additional_services_amount' => $serviceAmount,
                'final_total_amount' => $totalAmount
            ]
        ];
    }

    /**
     * Get payment status for checkout
     */
    private function getPaymentStatus($booking, $totalAmount)
    {
        try {
            $totalPaid = $booking->payments()
                ->where('status', 'completed')
                ->sum('amount_vnd');

            $remainingAmount = $totalAmount - $totalPaid;

            $payments = $booking->payments()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($payment) {
                    return [
                        'payment_id' => $payment->payment_id,
                        'amount_vnd' => $payment->amount_vnd,
                        'payment_type' => $payment->payment_type,
                        'status' => $payment->status,
                        'transaction_id' => $payment->transaction_id,
                        'created_at' => $payment->created_at
                    ];
                });

            return [
                'total_required' => $totalAmount,
                'total_paid' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'is_sufficient' => $remainingAmount <= 0,
                'payment_percentage' => $totalAmount > 0 ? ($totalPaid / $totalAmount) * 100 : 100,
                'payments' => $payments->toArray(),
                'payment_summary' => [
                    'completed_payments' => $payments->where('status', 'completed')->count(),
                    'pending_payments' => $payments->where('status', 'pending')->count(),
                    'failed_payments' => $payments->where('status', 'failed')->count()
                ]
            ];
        } catch (\Exception $e) {
            Log::error("Error getting payment status for booking {$booking->booking_id}: " . $e->getMessage());
            return [
                'total_required' => $totalAmount,
                'total_paid' => 0,
                'remaining_amount' => $totalAmount,
                'is_sufficient' => false,
                'payment_percentage' => 0,
                'payments' => [],
                'payment_summary' => [
                    'completed_payments' => 0,
                    'pending_payments' => 0,
                    'failed_payments' => 0
                ]
            ];
        }
    }

    /**
     * Update room status to cleaning
     */
    private function updateRoomStatusToCleaning($booking)
    {
        try {
            $roomIds = [];
            
            // Try multiple table structures
            try {
                $roomIds = DB::table('booking_rooms')
                    ->where('booking_id', $booking->booking_id)
                    ->pluck('room_id')
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning("booking_rooms table query failed: " . $e->getMessage());
            }
            
            if (empty($roomIds)) {
                try {
                    $roomIds = DB::table('booking_room')
                        ->where('booking_id', $booking->booking_id)
                        ->pluck('room_id')
                        ->toArray();
                } catch (\Exception $e) {
                    Log::warning("booking_room table query failed: " . $e->getMessage());
                }
            }
            
            if (empty($roomIds) && $booking->room_id) {
                $roomIds = [$booking->room_id];
            }
            
            if (!empty($roomIds)) {
                DB::table('room')
                    ->whereIn('room_id', $roomIds)
                    ->where('status', '!=', 'maintenance')
                    ->update(['status' => 'cleaning']);
            }

            return $roomIds;
        } catch (\Exception $e) {
            Log::warning("Could not update room status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create audit log
     */
    private function createAuditLog($booking, $checkoutTime, $amountCalculation, $validationResult)
    {
        try {
            DB::table('audit_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Check-out',
                'table_name' => 'booking',
                'record_id' => $booking->booking_id,
                'description' => "Check-out completed for booking {$booking->booking_code} at {$checkoutTime}. Total amount: " . number_format($amountCalculation['total_amount']) . " VND. Status changed to Cleaning. Validation: " . ($validationResult->canCheckout ? 'APPROVED' : 'FORCED') . " with " . count($validationResult->warnings) . " warnings.",
                'created_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            Log::warning("Could not create audit log: " . $e->getMessage());
        }
    }
}