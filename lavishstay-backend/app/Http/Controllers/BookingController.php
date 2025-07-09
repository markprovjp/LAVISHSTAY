<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\RoomAvailability;
use App\Models\RoomOption;
use App\Models\RoomOptionPromotion;
use App\Models\RoomMealOption;
use App\Models\Currency;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{


    // public function index(){
    //     // $bookings = Booking::with('option')->get();
    //     return view('admin.bookings.index');
    // }


    //////////// Trading//////////////////////////////////////
    public function trading(){
        return view('admin.bookings.trading.index');
    }

















    //////////// Transaction History/////////////////////////////////////////
    public function transaction_history(){
        return view('admin.bookings.transaction_history.index');
    }








































    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'option_id' => 'required|exists:room_option,option_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_count' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'currency' => 'nullable|exists:currency,currency_code'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $days = $checkIn->diffInDays($checkOut);

        // Kiểm tra phòng trống
        $available = RoomAvailability::where('option_id', $request->option_id)
            ->whereBetween('date', [$checkIn, $checkOut])
            ->where('available_rooms', '>', 0)
            ->count();

        if ($available < $days) {
            return response()->json(['error' => 'No rooms available'], 400);
        }

        // Tính tổng giá
        $option = RoomOption::with(['meal', 'promotions'])->findOrFail($request->option_id);
        $basePrice = $option->price_per_night_vnd * $days;
        $mealPrice = $option->meal ? $option->meal->price_vnd * $days : 0;
        $discount = $option->promotions->first() ? ($basePrice + $mealPrice) * ($option->promotions->first()->discount / 100) : 0;
        $totalPriceVnd = $basePrice + $mealPrice - $discount;

        // Quy đổi tiền tệ (nếu cần)
        $currencyCode = $request->currency ?? 'VND';
        $currency = Currency::find($currencyCode);
        $exchangeRate = $currency ? $currency->exchange_rate : 1.0;
        $totalPrice = $totalPriceVnd * $exchangeRate;

        // Tạo đặt phòng
        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'option_id' => $request->option_id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'total_price_vnd' => $totalPriceVnd,
                'guest_count' => $request->guest_count,
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'status' => 'pending'
            ]);

            // Tạo thanh toán đặt cọc (nếu có)
            if ($option->deposit_percentage) {
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => $totalPriceVnd * ($option->deposit_percentage / 100),
                    'payment_type' => 'deposit',
                    'status' => 'pending'
                ]);
            }

            // Cập nhật phòng trống
            RoomAvailability::where('option_id', $request->option_id)
                ->whereBetween('date', [$checkIn, $checkOut])
                ->decrement('available_rooms');

            DB::commit();
            return new BookingResource($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Booking failed: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        $booking = Booking::with('option')->findOrFail($id);
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['error' => 'Cannot cancel this booking'], 400);
        }

        $option = $booking->option;
        $now = Carbon::now();
        $freeUntil = $option->free_until ? Carbon::parse($option->free_until) : null;

        if ($option->cancellation_policy_type === 'non_refundable') {
            return response()->json(['error' => 'Non-refundable booking'], 400);
        }

        DB::beginTransaction();
        try {
            $booking->update(['status' => 'cancelled']);
            RoomAvailability::where('option_id', $booking->option_id)
                ->whereBetween('date', [$booking->check_in_date, $booking->check_out_date])
                ->increment('available_rooms');

            // Xử lý hoàn tiền
            if ($option->cancellation_policy_type === 'partial_refunded' && (!$freeUntil || $now->greaterThan($freeUntil))) {
                $penalty = $booking->total_price_vnd * ($option->cancellation_penalty / 100);
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => -($booking->total_price_vnd - $penalty),
                    'payment_type' => 'refunded',
                    'status' => 'completed'
                ]);
            } elseif ($option->cancellation_policy_type === 'free' || ($freeUntil && $now->lessThanOrEqualTo($freeUntil))) {
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => -$booking->total_price_vnd,
                    'payment_type' => 'refunded',
                    'status' => 'completed'
                ]);
            }

            DB::commit();
            return new BookingResource($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Cancellation failed: ' . $e->getMessage()], 500);
        }
    }

     /**
     * Admin: Get all bookings with rooms and options for management
     */
    public function getAllBookingsWithOptions(Request $request)
    {
        try {
            $query = DB::table('booking as b')
                ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
                ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('room_option as ro', 'br.option_id', '=', 'ro.option_id')
                ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->leftJoin('representatives as rep', 'b.booking_id', '=', 'rep.booking_id')
                ->select([
                    'b.booking_id',
                    'b.booking_code',
                    'b.guest_name',
                    'b.guest_email',
                    'b.guest_phone',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.guest_count',
                    'b.total_price_vnd',
                    'b.status as booking_status',
                    'b.created_at',
                    'br.room_id',
                    'br.option_id',
                    'br.option_name',
                    'br.option_price',
                    'br.adults',
                    'br.children',
                    'br.price_per_night',
                    'br.nights',
                    'br.total_price as room_total_price',
                    'r.name as room_name',
                    'rt.name as room_type_name',
                    'ro.name as selected_option_name',
                    'ro.price_per_night_vnd as selected_option_price_per_night',
                    'ro.cancellation_policy_type',
                    'ro.payment_policy_type',
                    'p.amount_vnd as payment_amount',
                    'p.status as payment_status',
                    'p.payment_type',
                    'rep.full_name as representative_name',
                    'rep.phone_number as representative_phone'
                ]);

            // Filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('b.status', $request->status);
            }

            if ($request->has('payment_status') && $request->payment_status !== '') {
                $query->where('p.status', $request->payment_status);
            }

            if ($request->has('date_from') && $request->date_from !== '') {
                $query->whereDate('b.created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to !== '') {
                $query->whereDate('b.created_at', '<=', $request->date_to);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('b.booking_code', 'like', "%{$search}%")
                      ->orWhere('b.guest_name', 'like', "%{$search}%")
                      ->orWhere('b.guest_email', 'like', "%{$search}%")
                      ->orWhere('b.guest_phone', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('b.created_at', 'desc')
                            ->paginate($request->get('per_page', 20));

            // Group by booking_id để tránh trùng lặp
            $groupedBookings = $bookings->getCollection()->groupBy('booking_id')->map(function ($rooms, $bookingId) {
                $firstRoom = $rooms->first();
                return [
                    'booking_id' => $bookingId,
                    'booking_code' => $firstRoom->booking_code,
                    'guest_name' => $firstRoom->guest_name,
                    'guest_email' => $firstRoom->guest_email,
                    'guest_phone' => $firstRoom->guest_phone,
                    'check_in_date' => $firstRoom->check_in_date,
                    'check_out_date' => $firstRoom->check_out_date,
                    'guest_count' => $firstRoom->guest_count,
                    'total_price_vnd' => $firstRoom->total_price_vnd,
                    'booking_status' => $firstRoom->booking_status,
                    'payment_amount' => $firstRoom->payment_amount,
                    'payment_status' => $firstRoom->payment_status,
                    'payment_type' => $firstRoom->payment_type,
                    'representative_name' => $firstRoom->representative_name,
                    'representative_phone' => $firstRoom->representative_phone,
                    'created_at' => $firstRoom->created_at,
                    'rooms' => $rooms->map(function ($room) {
                        return [
                            'room_id' => $room->room_id,
                            'room_name' => $room->room_name,
                            'room_type_name' => $room->room_type_name,
                            'option_id' => $room->option_id,
                            'option_name' => $room->option_name ?? $room->selected_option_name,
                            'option_price' => $room->option_price ?? $room->selected_option_price_per_night,
                            'adults' => $room->adults,
                            'children' => $room->children,
                            'price_per_night' => $room->price_per_night,
                            'nights' => $room->nights,
                            'total_price' => $room->room_total_price,
                            'cancellation_policy_type' => $room->cancellation_policy_type,
                            'payment_policy_type' => $room->payment_policy_type,
                        ];
                    })
                ];
            })->values();

            $bookings->setCollection($groupedBookings);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting all bookings with options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting bookings'
            ], 500);
        }
    }

    /**
     * Extract price from various formats (number, object with vnd, etc.)
     */
    private function extractPrice($price)
    {
        if (is_numeric($price)) {
            return $price;
        }
        
        if (is_array($price) && isset($price['vnd'])) {
            return $price['vnd'];
        }
        
        if (is_object($price) && isset($price->vnd)) {
            return $price->vnd;
        }
        
        // Try to get numerical value from string
        if (is_string($price)) {
            $numericPrice = preg_replace('/[^0-9.]/', '', $price);
            return is_numeric($numericPrice) ? (float)$numericPrice : 0;
        }
        
        return 0;
    }

    /**
     * Process children ages from various formats
     */
    private function processChildrenAge($childrenAge)
    {
        if (empty($childrenAge)) {
            return null;
        }
        
        if (is_string($childrenAge)) {
            return $childrenAge; // Already JSON string
        }
        
        if (is_array($childrenAge)) {
            // Extract ages from array of objects or simple array
            $ages = [];
            foreach ($childrenAge as $child) {
                if (is_array($child) && isset($child['age'])) {
                    $ages[] = $child['age'];
                } elseif (is_object($child) && isset($child->age)) {
                    $ages[] = $child->age;
                } elseif (is_numeric($child)) {
                    $ages[] = $child;
                }
            }
            return json_encode($ages);
        }
        
        return json_encode([]);
    }

    /**
     * Check CPay payment status via Google Sheets
     */
    public function checkCPayPayment(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            $bookingCode = $request->booking_code;
            $expectedAmount = $request->amount;

            Log::info("🔍 Checking CPay payment for booking", [
                'booking_code' => $bookingCode,
                'expected_amount' => $expectedAmount
            ]);

            // Find booking
            $booking = Booking::where('booking_code', $bookingCode)->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Multiple CPay URLs for redundancy
            $cpayUrls = [
                'https://script.google.com/macros/s/AKfycbx4F-yvXHfFifvP4JkunVHRiTwgL9cZNg7yE6CgcXZs3hmAjVtr6-1qKIa7ZEk52d00/exec',
                // Backup URL can be added here if needed
            ];
            
            $paymentFound = false;
            $transactionData = null;
            $lastError = null;

            foreach ($cpayUrls as $index => $cpayUrl) {
                try {
                    Log::info("🌐 Trying CPay URL #" . ($index + 1), ['url' => $cpayUrl]);

                    // Try GET with query parameters first (more common for Google Apps Script)
                    $response = Http::timeout(5) // Reduce timeout for demo
                        ->withoutVerifying() // Skip SSL verification for Google Apps Script
                        ->withHeaders([
                            'User-Agent' => 'LavishStay-Payment-Checker/1.0',
                            'Accept' => 'application/json, text/plain, */*'
                        ])
                        ->get($cpayUrl, [
                            'action' => 'checkPayment',
                            'booking_code' => $bookingCode,
                            'amount' => $expectedAmount,
                            'timestamp' => time()
                        ]);

                    Log::info("📨 CPay API Response", [
                        'status' => $response->status(),
                        'headers' => $response->headers(),
                        'body_preview' => substr($response->body(), 0, 200)
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['status']) && $data['status'] === 'success') {
                            // Check if payment transaction exists in the data
                            $transactions = $data['data'] ?? [];
                            
                            foreach ($transactions as $transaction) {
                                // Look for matching booking code in transaction content
                                $content = strtolower($transaction['content'] ?? '');
                                
                                // Multiple search patterns to be flexible
                                $searchPatterns = [
                                    strtolower("trace{$bookingCode}"),
                                    strtolower($bookingCode),
                                    strtolower("dat phong {$bookingCode}"),
                                    strtolower("thanh toan dat phong {$bookingCode}")
                                ];
                                
                                // Also check for amount match (allow small variance)
                                $transactionAmount = floatval($transaction['amount'] ?? 0);
                                $amountMatch = abs($transactionAmount - $expectedAmount) <= 1000; // 1000 VND tolerance
                                
                                $patternMatch = false;
                                foreach ($searchPatterns as $pattern) {
                                    if (strpos($content, $pattern) !== false) {
                                        $patternMatch = true;
                                        break;
                                    }
                                }
                                
                                if ($patternMatch && $amountMatch) {
                                    $paymentFound = true;
                                    $transactionData = $transaction;
                                    break 2; // Break both loops
                                }
                            }
                        }
                        
                        // If no payment found, continue to next URL
                        break;
                    } else {
                        $lastError = "HTTP {$response->status()}: " . $response->body();
                    }
                    
                } catch (\Exception $httpError) {
                    $lastError = $httpError->getMessage();
                    Log::warning("⚠️ CPay URL failed", [
                        'url' => $cpayUrl,
                        'error' => $lastError
                    ]);
                    continue; // Try next URL
                }
            }

            if ($paymentFound && $transactionData) {
                // Payment found, update booking status
                DB::beginTransaction();
                
                try {
                    $booking->update([
                        'status' => 'confirmed',
                        'updated_at' => now()
                    ]);

                    // Update payment status
                    $payment = Payment::where('booking_id', $booking->booking_id)->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'transaction_id' => $transactionData['reference_code'] ?? 'CPAY_' . time(),
                            'updated_at' => now()
                        ]);
                    }

                    DB::commit();

                    Log::info("✅ CPay payment verified successfully", [
                        'booking_code' => $bookingCode,
                        'transaction' => $transactionData
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment verified successfully',
                        'transaction' => $transactionData,
                        'booking_code' => $bookingCode
                    ]);
                } catch (\Exception $dbError) {
                    DB::rollBack();
                    throw $dbError;
                }
            } else {
                Log::info("❌ CPay payment not found", [
                    'booking_code' => $bookingCode,
                    'expected_amount' => $expectedAmount,
                    'last_error' => $lastError
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found or not completed yet',
                    'payment_found' => false,
                    'debug_info' => [
                        'searched_pattern' => "trace{$bookingCode}",
                        'expected_amount' => $expectedAmount,
                        'last_error' => $lastError
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('💥 Error checking CPay payment: ' . $e->getMessage(), [
                'booking_code' => $request->booking_code ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}