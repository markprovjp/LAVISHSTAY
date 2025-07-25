<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReceptionController_fix extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Create a new temporary booking record.
     * This only creates the main booking entry with a 'pending' status.
     */
    public function createBooking(Request $request): JsonResponse
    {
        Log::info('Create temporary booking request received:', $request->all());

        $validator = Validator::make($request->all(), [
            'booking_details' => 'required|array',
            'booking_details.check_in_date' => 'required|date_format:Y-m-d',
            'booking_details.check_out_date' => 'required|date_format:Y-m-d|after:booking_details.check_in_date',
            'booking_details.total_price' => 'required|numeric|min:0',
            'booking_details.adults' => 'required|integer|min:1',
            'booking_details.children' => 'present|array',
            'representative_info' => 'required|array',
            'representative_info.details' => 'required|array',
            'rooms' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            Log::error('Temporary booking validation failed:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ.', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $bookingDetails = $data['booking_details'];
            $representativeInfo = $data['representative_info'];
            $roomData = $data['rooms'];

            // Determine the main representative for the primary booking record
            $mainRepDetails = ($representativeInfo['mode'] === 'all')
                ? $representativeInfo['details']
                : reset($representativeInfo['details']);

            if (empty($mainRepDetails['fullName'])) {
                 throw new \Exception("Không có thông tin người đại diện.");
            }

            $bookingCode = 'LVS' . strtoupper(uniqid());
            $bookingId = DB::table('booking')->insertGetId([
                'booking_code' => $bookingCode,
                'guest_name' => $mainRepDetails['fullName'],
                'guest_email' => $mainRepDetails['email'] ?? null,
                'guest_phone' => $mainRepDetails['phoneNumber'] ?? null,
                'check_in_date' => $bookingDetails['check_in_date'],
                'check_out_date' => $bookingDetails['check_out_date'],
                'total_price_vnd' => $bookingDetails['total_price'],
                'guest_count' => $bookingDetails['adults'] + count($bookingDetails['children']),
                'adults' => $bookingDetails['adults'],
                'children' => count($bookingDetails['children']),
                'status' => 'pending', // Always pending initially
                'notes' => $bookingDetails['notes'] ?? null,
                'quantity' => count($roomData),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo đơn đặt phòng tạm thời thành công!',
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating temporary booking: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi khi tạo đặt phòng tạm thời.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm a booking after payment check.
     */
    public function confirmBooking(Request $request): JsonResponse
    {
        Log::info('Confirm booking request received:', $request->all());

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer|exists:booking,booking_id',
            'payment_method' => 'required|string',
            'booking_details' => 'required|array',
            'rooms' => 'required|array|min:1',
            'representative_info' => 'required|array',
        ]);

        if ($validator->fails()) {
            Log::error('Booking confirmation validation failed:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Dữ liệu xác nhận không hợp lệ.', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $bookingId = $data['booking_id'];
        $paymentMethod = $data['payment_method'];

        // ** Step 1: Check Payment Status (Simulated) **
        $paymentSuccessful = false;
        if ($paymentMethod === 'pay_at_hotel') {
            $paymentSuccessful = true;
        } else {
            // Placeholder for other payment gateway checks (e.g., VietQR)
            $paymentSuccessful = true;
        }

        if (!$paymentSuccessful) {
            return response()->json([
                'success' => false,
                'message' => 'Thanh toán chưa hoàn tất. Đơn đặt phòng vẫn ở trạng thái chờ.',
            ]);
        }

        // ** Step 2: If payment is successful, build the full booking **
        DB::beginTransaction();
        try {
            $bookingDetails = $data['booking_details'];
            $representativeInfo = $data['representative_info'];
            $roomData = $data['rooms'];

            $booking = DB::table('booking')->where('booking_id', $bookingId)->lockForUpdate()->first();

            if ($booking->status !== 'pending') {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Đặt phòng này đã được xử lý.'], 409);
            }

            foreach ($roomData as $room) {
                $repDetails = ($representativeInfo['mode'] === 'all')
                    ? $representativeInfo['details']
                    : ($representativeInfo['details'][$room['room_id']] ?? null);

                if (!$repDetails) continue;

                $representativeId = DB::table('representatives')->insertGetId([
                    'booking_id' => $bookingId,
                    'booking_code' => $booking->booking_code,
                    'room_id' => $room['room_id'],
                    'full_name' => $repDetails['fullName'],
                    'phone_number' => $repDetails['phoneNumber'],
                    'email' => $repDetails['email'],
                    'id_card' => $repDetails['idCard'],
                    'notes' => $repDetails['notes'] ?? null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $package = DB::table('packages')->where('package_id', $room['package_id'])->first();
                $nights = Carbon::parse($bookingDetails['check_out_date'])->diffInDays(Carbon::parse($bookingDetails['check_in_date']));

                $bookingRoomId = DB::table('booking_rooms')->insertGetId([
                    'booking_id' => $bookingId,
                    'booking_code' => $booking->booking_code,
                    'room_id' => $room['room_id'],
                    'representative_id' => $representativeId,
                    'option_name' => $package->package_name ?? 'Default Package',
                    'price_per_night' => $package->price_per_room_per_night ?? 0,
                    'nights' => $nights,
                    'total_price' => ($package->price_per_room_per_night ?? 0) * $nights,
                    'check_in_date' => $bookingDetails['check_in_date'],
                    'check_out_date' => $bookingDetails['check_out_date'],
                    'adults' => $room['adults'],
                    'children' => count($room['children']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if (!empty($room['children'])) {
                    $childrenData = array_map(function($child, $index) use ($bookingRoomId) {
                        return [
                            'booking_room_id' => $bookingRoomId,
                            'child_index' => $index + 1,
                            'age' => $child['age'],
                        ];
                    }, $room['children'], array_keys($room['children']));
                    DB::table('booking_room_children')->insert($childrenData);
                }
            }

            $checkInDate = Carbon::parse($booking->check_in_date);
            $newStatus = $checkInDate->isToday() ? 'operational' : 'confirmed';

            DB::table('booking')->where('booking_id', $bookingId)->update([
                'status' => $newStatus,
                'updated_at' => Carbon::now(),
            ]);

            $roomIds = array_column($roomData, 'room_id');
            DB::table('room')->whereIn('room_id', $roomIds)->update(['status' => 'occupied']);

            DB::table('payment')->insert([
                'booking_id' => $bookingId,
                'amount_vnd' => $bookingDetails['total_price'],
                'payment_type' => $paymentMethod,
                'status' => 'completed',
                'transaction_id' => 'RECEPTION_' . $booking->booking_code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt phòng đã được xác nhận thành công!',
                'booking_code' => $booking->booking_code,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming booking: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi khi xác nhận đặt phòng.', 'error' => $e->getMessage()], 500);
        }
    }
}
