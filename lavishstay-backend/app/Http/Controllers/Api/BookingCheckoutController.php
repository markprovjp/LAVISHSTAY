<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingCheckoutController extends Controller
{
    /**
     * Get available services for booking
     */
    public function getAvailableServices(Request $request)
    {
        try {
            Log::info('=== BookingCheckoutController@getAvailableServices START ===');

            // Get all active services
            $services = Service::active()
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

            Log::info('=== BookingCheckoutController@getAvailableServices SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách dịch vụ thành công',
                'data' => [
                    'total_services' => $services->count(),
                    'services' => $services->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in getAvailableServices ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách dịch vụ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add service to booking
     */
    public function addBookingService(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@addBookingService START ===');
            Log::info('Booking ID: ' . $bookingId);
            Log::info('Request data: ', $request->all());

            // Validate input
            $validated = $request->validate([
                'service_id' => 'required|integer|exists:services,service_id',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:500'
            ]);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking
            $booking = Booking::find($bookingId);
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check if booking is in valid status for adding services
            if (!in_array($booking->status, ['Confirmed', 'Operational'])) {
                return response()->json([
                    'error' => 'Không thể thêm dịch vụ cho booking này',
                    'current_status' => $booking->status,
                    'allowed_statuses' => ['Confirmed', 'Operational']
                ], 400);
            }

            // Get service information
            $service = Service::find($validated['service_id']);
            if (!$service || !$service->is_active) {
                return response()->json(['error' => 'Dịch vụ không tồn tại hoặc không khả dụng'], 404);
            }

            // Check if service already exists for this booking
            $existingService = BookingService::where('booking_id', $bookingId)
                ->where('service_id', $validated['service_id'])
                ->first();

            if ($existingService) {
                return response()->json([
                    'error' => 'Dịch vụ đã được thêm cho booking này',
                    'message' => 'Sử dụng API cập nhật để thay đổi số lượng'
                ], 400);
            }

            // Add service to booking
            $bookingService = BookingService::create([
                'booking_id' => $bookingId,
                'service_id' => $validated['service_id'],
                'quantity' => $validated['quantity'],
                'price_vnd' => $service->price_vnd,
                'created_at' => Carbon::now()
            ]);

            // Get updated booking services
            $bookingServices = $this->getBookingServices($booking);

            Log::info('=== BookingCheckoutController@addBookingService SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Thêm dịch vụ thành công',
                'data' => [
                    'booking_service_id' => $bookingService->id,
                    'service_info' => [
                        'service_id' => $service->service_id,
                        'service_name' => $service->name,
                        'quantity' => $validated['quantity'],
                        'unit_price_vnd' => $service->price_vnd,
                        'total_price_vnd' => $bookingService->total_price,
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
            Log::error('=== ERROR in addBookingService ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm dịch vụ',
                'error' => $e->getMessage()
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

    /**
     * Get checkout information for a specific booking
     */
    public function getCheckoutInfo(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@getCheckoutInfo START ===');
            Log::info('Booking ID: ' . $bookingId);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking with relationships
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room', 'bookingServices.service'])
                ->find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            Log::info('Found booking: ' . $booking->booking_code);

            // Check if booking can be checked out
            if ($booking->status !== 'Operational') {
                return response()->json([
                    'error' => 'Booking không thể check-out',
                    'current_status' => $booking->status,
                    'required_status' => 'Operational',
                    'message' => 'Chỉ có thể check-out booking đang ở trạng thái "Đang lưu trú"'
                ], 400);
            }

            // Get room information
            $roomInfo = $this->getRoomInformation($booking);
            Log::info('Room info retrieved');

            // Get hotel information
            $hotelInfo = $this->getHotelInformation($booking);
            Log::info('Hotel info retrieved');

            // Get guest information
            $guestInfo = $this->getGuestInformation($booking);
            Log::info('Guest info retrieved');

            // Get available services
            $availableServices = Service::active()
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

            // Get booking services (additional services during stay)
            $bookingServices = $this->getBookingServices($booking);
            Log::info('Booking services retrieved');

            // Calculate total amounts
            $amountCalculation = $this->calculateTotalAmount($booking, $bookingServices);
            Log::info('Amount calculation completed');

            // Get payment status
            $paymentStatus = $this->getPaymentStatus($booking, $amountCalculation['total_amount']);
            Log::info('Payment status retrieved');

            // Check if ready for checkout
            $readyForCheckout = $paymentStatus['is_sufficient'];

            // Prepare checkout information
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
                'checkout_conditions' => [
                    'payment_sufficient' => $paymentStatus['is_sufficient'],
                    'ready_for_checkout' => $readyForCheckout
                ],
                'warnings' => $this->getCheckoutWarnings($paymentStatus),
                'checkout_summary' => [
                    'can_checkout' => $readyForCheckout,
                    'total_stay_amount' => $amountCalculation['total_amount'],
                    'remaining_payment' => $paymentStatus['remaining_amount'],
                    'checkout_date' => Carbon::now()->format('Y-m-d'),
                    'checkout_time' => Carbon::now()->format('H:i')
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
     * Process checkout for a booking
     */
    public function processCheckout(Request $request, $bookingId)
    {
        try {
            Log::info('=== BookingCheckoutController@processCheckout START ===');
            Log::info('Booking ID: ' . $bookingId);
            Log::info('Request data: ', $request->all());

            // Validate input
            $validated = $request->validate([
                'checkout_time' => 'nullable|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
                'final_payment_method' => 'nullable|string|in:cash,card,transfer,vietqr',
                'send_invoice' => 'nullable|boolean',
                'guest_email_for_invoice' => 'nullable|email'
            ]);

            // Validate booking ID
            if (!is_numeric($bookingId)) {
                return response()->json(['error' => 'ID đặt phòng không hợp lệ'], 400);
            }

            // Find booking with relationships
            $booking = Booking::with(['user', 'roomOption', 'payments', 'room', 'bookingServices.service'])
                ->find($bookingId);
            
            if (!$booking) {
                return response()->json(['error' => 'Không tìm thấy đặt phòng'], 404);
            }

            // Check if booking can be checked out
            if ($booking->status !== 'Operational') {
                return response()->json([
                    'error' => 'Booking không thể check-out',
                    'current_status' => $booking->status,
                    'required_status' => 'Operational'
                ], 400);
            }

            // Get booking services and calculate total
            $bookingServices = $this->getBookingServices($booking);
            $amountCalculation = $this->calculateTotalAmount($booking, $bookingServices);
            $paymentStatus = $this->getPaymentStatus($booking, $amountCalculation['total_amount']);

            // Check if payment is sufficient
            if (!$paymentStatus['is_sufficient']) {
                return response()->json([
                    'error' => 'Chưa thanh toán đủ để check-out',
                    'payment_details' => $paymentStatus,
                    'remaining_amount' => $paymentStatus['remaining_amount'],
                    'message' => 'Vui lòng thanh toán đầy đủ trước khi check-out'
                ], 400);
            }

            // Get additional information
            $roomInfo = $this->getRoomInformation($booking);
            $hotelInfo = $this->getHotelInformation($booking);
            $guestInfo = $this->getGuestInformation($booking);

            // Process checkout within transaction
            $checkoutResult = null;
            DB::transaction(function () use ($booking, $validated, $amountCalculation, $paymentStatus, $bookingServices, &$checkoutResult) {
                
                $checkoutTime = $validated['checkout_time'] ?? Carbon::now()->format('H:i');
                $checkoutDateTime = Carbon::now();

                // Update booking status to Cleaning (not Completed)
                $booking->status = 'Cleaning';
                $booking->notes = ($booking->notes ? $booking->notes . "\n" : '') . 
                                ($validated['notes'] ?? 'Check-out completed at ' . $checkoutDateTime->format('Y-m-d H:i:s'));
                
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
                } catch (\Exception $e) {
                    Log::warning("Could not update room status: " . $e->getMessage());
                }

                // Create audit log
                try {
                    DB::table('audit_logs')->insert([
                        'user_id' => Auth::id(),
                        'action' => 'Check-out',
                        'table_name' => 'booking',
                        'record_id' => $booking->booking_id,
                        'description' => "Check-out completed for booking {$booking->booking_code} at {$checkoutTime}. Total amount: " . number_format($amountCalculation['total_amount']) . " VND. Status changed to Cleaning.",
                        'created_at' => $checkoutDateTime,
                    ]);
                } catch (\Exception $e) {
                    Log::warning("Could not create audit log: " . $e->getMessage());
                }

                $checkoutResult = [
                    'invoice_id' => $invoice->invoice_id,
                    'final_payment_id' => $finalPaymentId,
                    'checkout_datetime' => $checkoutDateTime,
                    'room_ids' => $roomIds ?? []
                ];
            });

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
                    'invoice_id' => $checkoutResult['invoice_id']
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
                'checkout_summary' => [
                    'checkout_completed' => true,
                    'booking_status' => 'Cleaning',
                    'invoice_created' => true,
                    'invoice_sent' => $validated['send_invoice'] ?? false,
                    'rooms_set_to_cleaning' => !empty($checkoutResult['room_ids']),
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
        return [
            'guest_name' => $booking->guest_name,
            'guest_email' => $booking->guest_email,
            'guest_phone' => $booking->guest_phone,
            'guest_count' => $booking->guest_count,
            'adults' => $booking->adults,
            'children' => $booking->children,
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
     * Get checkout warnings
     */
    private function getCheckoutWarnings($paymentStatus)
    {
        $warnings = [];

        if (!$paymentStatus['is_sufficient']) {
            $warnings[] = [
                'type' => 'payment',
                'message' => 'Chưa thanh toán đủ',
                'details' => "Còn thiếu " . number_format($paymentStatus['remaining_amount']) . " VND"
            ];
        }

        return $warnings;
    }
}