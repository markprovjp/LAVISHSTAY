<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\PaymentController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomOptionController;
use App\Http\Controllers\BookingController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Room Types API
Route::apiResource('room-types', RoomTypeController::class);



//FAQs API
Route::apiResource('faqs', FAQController::class);



Route::apiResource('room-options', RoomOptionController::class);
Route::post('bookings', [BookingController::class, 'store']);
Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);

Route::get('/rooms/available', [RoomAvailabilityController::class, 'getAvailableRooms']);

// Payment Management Routes
Route::prefix('payment')->group(function () {
    // Tạo booking mới từ frontend
    Route::post('/create-booking', [PaymentController::class, 'createBooking']);
    
    // Check payment status (Frontend polling) - sử dụng booking ID
    Route::get('/status/{bookingId}', [PaymentController::class, 'checkPaymentStatus']);
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        // Lấy danh sách booking chờ thanh toán
        Route::get('/pending', [PaymentController::class, 'getPendingPayments']);
        
        // Lấy lịch sử tất cả bookings với filters
        Route::get('/history', [PaymentController::class, 'getBookingHistory']);
        
        // Lấy thống kê bookings
        Route::get('/stats', [PaymentController::class, 'getBookingStats']);
        
        // Xác nhận thanh toán - sử dụng booking ID
        Route::post('/confirm/{bookingId}', [PaymentController::class, 'adminConfirmPayment']);
    });
});
