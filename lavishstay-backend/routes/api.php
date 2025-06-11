<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\PaymentController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Route::apiResource('room-types', RoomTypeController::class);

//FAQs API
Route::apiResource('faqs', FAQController::class);

// Payment Routes
Route::prefix('payment')->group(function () {
    // Tạo booking mới
    Route::post('/create-booking', [PaymentController::class, 'createBooking']);
    
    // Check payment status (Frontend polling)
    Route::get('/status/{bookingCode}', [PaymentController::class, 'checkPaymentStatus']);
    
    // Admin confirm payment (Manual)
    Route::post('/admin/confirm/{bookingCode}', [PaymentController::class, 'adminConfirmPayment']);
    
    // Lấy danh sách booking chờ thanh toán (Admin panel)
    Route::get('/admin/pending', [PaymentController::class, 'getPendingPayments']);
});