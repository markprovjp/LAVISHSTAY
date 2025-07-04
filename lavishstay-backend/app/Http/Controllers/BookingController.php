
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
}

