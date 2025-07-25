<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ReceptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomOptionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\ChatController;

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

// Route test gửi email
Route::get('/test-email/{bookingId}', [PaymentController::class, 'testEmail']);

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google', [AuthController::class, 'googleLogin']); // Đổi từ google-login thành google
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
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



Route::get('/rooms/available', [RoomAvailabilityController::class, 'getAvailableRooms']);
Route::get('/rooms/available/debug', [RoomAvailabilityController::class, 'debugDatabase']);
Route::get('/rooms/debug-images-amenities', [RoomAvailabilityController::class, 'debugImagesAndAmenities']);
// Thêm route mới cho customer search
Route::get('/room-packages/search', [RoomAvailabilityController::class, 'getAvailablePackages']);
Route::post('/room-packages/search', [RoomAvailabilityController::class, 'getAvailablePackages']);


// Rooms API  
Route::apiResource('rooms', \App\Http\Controllers\Api\RoomController::class);

// Additional Room Routes
Route::get('/rooms/type/{roomTypeId}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByType']);
Route::get('/rooms/type-slug/{slug}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByTypeSlug']);
Route::get('/rooms/{roomId}/calendar', [\App\Http\Controllers\Api\RoomController::class, 'getCalendarData']);






Route::apiResource('room-options', RoomOptionController::class);
Route::post('bookings', [BookingController::class, 'store']);
Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);

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
    
    // Get booking details with rooms and options
    Route::get('/booking-details/{bookingCode}', [PaymentController::class, 'getBookingWithRooms']);
    
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
        
        // Lấy tất cả booking với thông tin room và option chi tiết  
        Route::get('/all-bookings', [PaymentController::class, 'getAllBookingsWithOptions']);
        
        // Xác nhận thanh toán - sử dụng booking ID
        Route::post('/confirm/{bookingId}', [PaymentController::class, 'confirmPayment']);
    });

    // VietQR payment routes
    Route::post('/create-vietqr', [PaymentController::class, 'createVietQRPayment']);
    Route::post('/verify-vietqr', [PaymentController::class, 'verifyVietQRPayment']);
    
    // Complete booking after successful payment
    Route::post('/complete-booking', [PaymentController::class, 'completeBookingAfterSuccessfulPayment']);
    
    // CPay payment check route
    Route::post('/check-cpay', [PaymentController::class, 'checkCPayPayment']);
    
    // Debug route for CPay (development only)
});
Route::post('/test-cpay-payment', [PaymentController::class, 'testCPayPayment']);

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

// Reception Management API (Quản lý lễ tân)
Route::prefix('reception')->group(function () {
    // Room Management
    Route::get('/rooms', [ReceptionController::class, 'getRooms']);
    Route::get('/rooms/statistics', [ReceptionController::class, 'getRoomStatistics']);
    Route::get('/rooms/{roomId}/details', [ReceptionController::class, 'getRoomDetails']);
    Route::put('/rooms/{roomId}/status', [ReceptionController::class, 'updateRoomStatus']);
    
    // Booking Management
    Route::get('/bookings', [ReceptionController::class, 'getBookings']);
    Route::get('/bookings/statistics', [ReceptionController::class, 'getBookingStatistics']);
    Route::get('/bookings/{bookingId}', [ReceptionController::class, 'getBookingDetails']);
    Route::get('/bookings/{bookingId}/assignment-preview', [ReceptionController::class, 'getAssignmentPreview']);
    Route::post('/bookings/assign-multiple-rooms', [ReceptionController::class, 'assignMultipleRoomsToBooking']);
    Route::post('/bookings', [ReceptionController::class, 'createReceptionBooking']);
    Route::post('/bookings/create', [ReceptionController::class, 'createBooking']);
    Route::post('/bookings/confirm', [ReceptionController::class, 'confirmBooking']);
    Route::put('/bookings/{bookingId}/status', [ReceptionController::class, 'updateBookingStatus']);
    Route::put('/bookings/{bookingId}/cancel', [ReceptionController::class, 'cancelBooking']);
    Route::post('/bookings/transfer', [ReceptionController::class, 'transferBooking']);
    Route::post('/bookings/check-in', [ReceptionController::class, 'checkIn']);
    Route::post('/bookings/check-out', [ReceptionController::class, 'checkOut']);
    
    // Filters
    Route::get('/floors', [ReceptionController::class, 'getFloors']);
    Route::get('/room-types', [ReceptionController::class, 'getRoomTypes']);
    
    // Legacy booking routes (keep for compatibility)
    Route::post('/book', [\App\Http\Controllers\Api\ReceptionBookController::class, 'create']);
    Route::get('/booking/{booking_id}', [\App\Http\Controllers\Api\ReceptionBookController::class, 'detail']);
    Route::get('/payment-status/{booking_id}', [\App\Http\Controllers\Api\ReceptionBookController::class, 'paymentStatus']);
    Route::get('/bookings-legacy', [\App\Http\Controllers\Api\ReceptionBookController::class, 'list']);
});

// Test route to check database
Route::get('/test-db', function () {
    try {
        $tables = DB::select('SHOW TABLES');
        $result = [];
        
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            if (in_array($tableName, ['booking', 'booking_rooms', 'payment', 'room'])) {
                $columns = DB::select("DESCRIBE {$tableName}");
                $result[$tableName] = $columns;
            }
        }
        
        return response()->json([
            'success' => true,
            'tables' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

//FAQs API
Route::apiResource('faqs', FAQController::class);

//Chat API - New flow: FE calls AI, BE logs conversation
Route::prefix('chat')->name('chat.')->group(function () {
    // NEW: Provides the hotel info from markdown file to the frontend AI
    Route::get('/hotel-context', [ChatController::class, 'getHotelContext'])->name('context');
    
    // MODIFIED: Logs the user question and the AI response
    Route::post('/log', [ChatController::class, 'logConversation'])->name('log');
});