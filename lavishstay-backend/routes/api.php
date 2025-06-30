<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\SearchController;
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

// Dashboard API
Route::get('/dashboard/room-statistics', [\App\Http\Controllers\Api\DashboardController::class, 'getRoomStatistics']);
Route::get('/dashboard/filter-options', [\App\Http\Controllers\Api\DashboardController::class, 'getFilterOptions']);

// Room Types API
Route::apiResource('room-types', RoomTypeController::class);

// Search API
Route::prefix('search')->group(function () {
    Route::post('/rooms', [SearchController::class, 'searchRooms']);
    Route::get('/room-types/{roomTypeId}/pricing', [SearchController::class, 'getRoomTypePricing']);
    Route::get('/pricing-rules', [SearchController::class, 'getPricingRules']);
});

// Rooms API  
Route::apiResource('rooms', \App\Http\Controllers\Api\RoomController::class);

// Additional Room Routes
Route::get('/rooms/type/{roomTypeId}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByType']);
Route::get('/rooms/type-slug/{slug}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByTypeSlug']);
Route::get('/rooms/{roomId}/calendar', [\App\Http\Controllers\Api\RoomController::class, 'getCalendarData']);



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
    
    // Update payment method
    Route::post('/update-method', [PaymentController::class, 'updatePaymentMethod']);
    
    // Get booking details
    Route::get('/booking/{bookingCode}', [PaymentController::class, 'getBookingDetails']);
      // VNPay create payment URL (new API)
    Route::post('/vnpay', [PaymentController::class, 'createVNPayPaymentAPI']);
    
    // VNPay callback/return
    Route::post('/vnpay/return', [PaymentController::class, 'handleVNPayReturn']);
    Route::get('/vnpay/return', [PaymentController::class, 'handleVNPayReturn']);
    
    // Legacy VNPay route (keep for compatibility)
    Route::post('/vnpay-legacy', [PaymentController::class, 'vnpayPayment']);
    
    // Test VNPay config (only in dev)
    Route::get('/test-vnpay-config', [PaymentController::class, 'testVNPayConfig']);
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        // Lấy danh sách booking chờ thanh toán
        Route::get('/pending', [PaymentController::class, 'getPendingPayments']);
        
        // Lấy lịch sử tất cả bookings với filters
        Route::get('/history', [PaymentController::class, 'getBookingHistory']);
        
        // Lấy thống kê bookings
        Route::get('/stats', [PaymentController::class, 'getBookingStats']);
        
        // Lấy danh sách booking đã được xác nhận (auto-approved)
        Route::get('/confirmed', [PaymentController::class, 'getConfirmedBookings']);
        
        // Xác nhận thanh toán - sử dụng booking ID
        Route::post('/confirm/{bookingId}', [PaymentController::class, 'confirmPayment']);
    });
});

// Pricing API Routes - Enhanced
Route::prefix('pricing')->group(function () {
    // Public pricing endpoints (for booking system)
    Route::post('/calculate', [PricingController::class, 'calculatePrice']);
    Route::post('/calculate-night', [PricingController::class, 'calculateNightPrice']);
    Route::get('/occupancy-rate', [PricingController::class, 'getOccupancyRate']);
    Route::get('/applicable-rules', [PricingController::class, 'getApplicableRules']);
    Route::get('/calendar', [PricingController::class, 'getPricingCalendar']);
    Route::get('/summary', [PricingController::class, 'getPricingSummary']);
    
    // Admin only endpoints
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/validate-conflicts', [PricingController::class, 'validateRuleConflicts']);
        Route::post('/clear-cache', [PricingController::class, 'clearCache']);
        Route::get('/stats', [PricingController::class, 'getPricingStats']);
    });
});
