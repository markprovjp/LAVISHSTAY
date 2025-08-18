
<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ReceptionController;
use App\Http\Controllers\Api\ChartReceptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\ReviewController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomOptionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\SitemapController;
use App\Http\Controllers\Api\BookingCancellationController;
use App\Http\Controllers\Api\BookingCheckinController;
use App\Http\Controllers\Api\BookingCheckoutController;
use App\Http\Controllers\Api\BookingExtensionController;
use App\Http\Controllers\Api\BookingRescheduleController;
use App\Http\Controllers\Api\BookingTransferController;
use App\Http\Controllers\Api\NewsCommentController;
use App\Http\Controllers\Api\NewsAPICategoryController;
use App\Http\Controllers\Api\NewsUserActionController;
use App\Http\Controllers\Api\PaymentSettingsController;
use App\Http\Controllers\NewsController\NewsCategoryController;

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
Route::middleware('auth:sanctum')->get('/user/bookings', [BookingController::class, 'getUserBookings']);
Route::middleware('auth:sanctum')->post('/booking/assign', [BookingController::class, 'assignBookingToUser']);
// Route test gửi email
Route::get('/test-email/{bookingId}', [PaymentController::class, 'testEmail']);

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google', [AuthController::class, 'googleLogin']); // Đổi từ google-login thành google
    

    Route::get('/room-types/{roomTypeId}/pricing', [SearchController::class, 'getRoomTypePricing']);
    Route::get('/pricing-rules', [SearchController::class, 'getPricingRules']);
});



Route::get('/rooms/available', [RoomAvailabilityController::class, 'getAvailableRooms']);
Route::get('/rooms/available/debug', [RoomAvailabilityController::class, 'debugDatabase']);
Route::get('/rooms/debug-images-amenities', [RoomAvailabilityController::class, 'debugImagesAndAmenities']);
// Thêm route mới cho customer search
Route::get('/room-packages/search', [RoomAvailabilityController::class, 'getAvailablePackages']);
Route::post('/room-packages/search', [RoomAvailabilityController::class, 'getAvailablePackages']);

Route::get('/room-type-packages/by-room-type/{room_type_id}', [App\Http\Controllers\RoomTypePackageController::class, 'getPackagesByRoomType']);


// Room Requests API

// Yêu cầu hủy


Route::post('/cancel-booking/{bookingId}', [BookingCancellationController::class, 'cancelBooking']);
Route::get('/cancel-booking/{bookingId}', [BookingCancellationController::class, 'getBookingCancellationInfo']);


//Yêu cầu gia hạn
Route::post('/bookings/{bookingId}/extend', [BookingExtensionController::class, 'extendBooking']);
Route::get('/bookings/{bookingId}/extend', [BookingExtensionController::class, 'getExtendBookingInfo']);

// Yêu cầu chuyển phòng
Route::post('/bookings/{bookingId}/transfer', [BookingTransferController::class, 'transferBooking']);
Route::get('/bookings/{bookingId}/transfer', [BookingTransferController::class, 'getTransferBookingInfo']);


//Yêu cầu rời lịch
Route::post('/bookings/{bookingId}/reschedule', [BookingRescheduleController::class, 'rescheduleBooking']);
Route::get('/bookings/{bookingId}/reschedule', [BookingRescheduleController::class, 'getRescheduleBookingInfo']);




// API Routes cho Check-in///////////////////////////////////////////////////
Route::prefix('checkin')->group(function () {
    Route::get('/today', [BookingCheckinController::class, 'getTodayCheckins'])->name('api.checkin.today');
    Route::get('/booking/{bookingId}/info', [BookingCheckinController::class, 'getCheckinInfo'])->name('api.checkin.info');
    Route::post('/booking/{bookingId}/process', [BookingCheckinController::class, 'processCheckin'])->name('api.checkin.process');
});




// Rooms API  
Route::apiResource('rooms', \App\Http\Controllers\Api\RoomController::class);

// Additional Room Routes
Route::get('/rooms/type/{roomTypeId}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByType']);
Route::get('/rooms/type-slug/{slug}', [\App\Http\Controllers\Api\RoomController::class, 'roomsByTypeSlug']);
Route::get('/rooms/{roomId}/calendar', [\App\Http\Controllers\Api\RoomController::class, 'getCalendarData']);

// Reviews API
Route::get('/reviews/list', [ReviewController::class, 'apiReviewsList']);
Route::get('/reviews/detail/{id}', [ReviewController::class, 'apiReviewsDetail']);
Route::delete('/reviews/delete/{id}', [ReviewController::class, 'apiReviewsDelete']);
Route::patch('/reviews/reject/{id}', [ReviewController::class, 'apiReviewsReject']);
Route::patch('/reviews/approve/{id}', [ReviewController::class, 'apiReviewsApprove']);
Route::post('/reviews/create', [ReviewController::class, 'apiReviewsCreate']);
Route::put('/reviews/update/{id}', [ReviewController::class, 'apiReviewsUpdate']);
Route::get('/reviews/room-type/{id}', [ReviewController::class, 'apiRoomTypeDetails']);



// Route to get room options/packages by room_id or room_type_id
Route::get('/room-options', [RoomOptionController::class, 'getRoomOptions']);
Route::apiResource('room-options', RoomOptionController::class);
Route::post('bookings', [BookingController::class, 'store']);
Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);
Route::post('bookings/{id}/check-in', [BookingController::class, 'checkIn']);
Route::post('bookings/{id}/check-out', [BookingController::class, 'checkOut']);

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
    // Reception check-in: delegate to BookingCheckinController for check-in flows
// API Routes cho Check-in///////////////////////////////////////////////////
Route::prefix('checkin')->group(function () {
    Route::get('/today', [BookingCheckinController::class, 'getTodayCheckins'])->name('api.checkin.today');
    Route::get('/booking/{bookingId}/info', [BookingCheckinController::class, 'getCheckinInfo'])->name('api.checkin.info');
    Route::post('/booking/{bookingId}/process', [BookingCheckinController::class, 'processCheckin'])->name('api.checkin.process');
});
    // Reception check-out: run through BookingCheckoutController so services are calculated before finalizing
// Checkout
Route::get('/bookings/{id}/checkout-info', [BookingCheckoutController::class, 'getCheckoutInfo']);
Route::post('/bookings/{id}/checkout', [BookingCheckoutController::class, 'processCheckout']);
Route::post('/bookings/{id}/checkout/compensation ', [BookingCheckoutController::class, 'createCompensationRequest']);

    // Service management for checkout and the checkout flow itself handled by BookingCheckoutController
    Route::get('/services/available', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'getAvailableServices']);
    Route::post('/bookings/{id}/services', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'addBookingService']);
    Route::put('/bookings/{id}/services/{serviceId}', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'updateBookingService']);
    Route::delete('/bookings/{id}/services/{serviceId}', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'removeBookingService']);


    
    // Filters
    Route::get('/floors', [ReceptionController::class, 'getFloors']);
    Route::get('/room-types', [ReceptionController::class, 'getRoomTypes']);
    
    // Chart & Dashboard APIs
    Route::prefix('chart')->group(function () {
        Route::get('/revenue-by-month', [ChartReceptionController::class, 'getRevenueByMonth']);
        Route::get('/revenue-by-category', [ChartReceptionController::class, 'getRevenueByCategory']);
        Route::get('/activity-rate', [ChartReceptionController::class, 'getActivityRate']);
        Route::get('/today-schedule', [ChartReceptionController::class, 'getTodaySchedule']);
        Route::get('/notifications', [ChartReceptionController::class, 'getNotifications']);
        Route::get('/top-booked-services', [ChartReceptionController::class, 'getTopBookedServices']);
        Route::get('/dashboard-stats', [ChartReceptionController::class, 'getDashboardStats']);
    });
    
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

Route::get('/test-complete/{bookingCode}', [PaymentController::class, 'testCompleteBooking']);



// News API Routes
Route::prefix('news')->name('news.')->group(function () {
    // Public routes
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/popular', [NewsController::class, 'getPopular'])->name('popular');
    Route::get('/featured', [NewsController::class, 'getFeatured'])->name('featured');
    Route::get('/trending', [NewsController::class, 'getTrending'])->name('trending');
    Route::get('/search-by-tags', [NewsController::class, 'searchByTags'])->name('search-tags');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
    Route::get('/{slug}/related', [NewsController::class, 'getRelated'])->name('related');
    
    // Admin routes (with authentication if needed)
    Route::post('/', [NewsController::class, 'store'])->name('store');
    Route::put('/{id}', [NewsController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
});

// News Categories API Routes  
Route::prefix('news-categories')->name('news-categories.')->group(function () {
    Route::get('/', [NewsAPICategoryController::class, 'index'])->name('index');
    Route::post('/', [NewsAPICategoryController::class, 'store'])->name('store');
    Route::get('/{id}', [NewsAPICategoryController::class, 'show'])->name('show');
    Route::put('/{id}', [NewsAPICategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsAPICategoryController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/news', [NewsAPICategoryController::class, 'getNews'])->name('news');
});

// News Comments API Routes
Route::prefix('news/{newsId}/comments')->name('news.comments.')->group(function () {
    Route::get('/', [NewsCommentController::class, 'index'])->name('index');
    Route::post('/', [NewsCommentController::class, 'store'])->name('store');
    Route::get('/{id}', [NewsCommentController::class, 'show'])->name('show');
    Route::put('/{id}', [NewsCommentController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsCommentController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/like', [NewsCommentController::class, 'toggleLike'])->name('like');
    Route::get('/{id}/replies', [NewsCommentController::class, 'getReplies'])->name('replies');
});

// News User Actions API Routes
Route::prefix('news-actions')->name('news.actions.')->group(function () {
    Route::get('/{newsId}', [NewsUserActionController::class, 'show'])->name('show');
    Route::post('/{newsId}/like', [NewsUserActionController::class, 'toggleLike'])->name('like');
    Route::post('/{newsId}/bookmark', [NewsUserActionController::class, 'toggleBookmark'])->name('bookmark');
    Route::post('/{newsId}/rate', [NewsUserActionController::class, 'rate'])->name('rate');
    Route::delete('/{newsId}/rate', [NewsUserActionController::class, 'removeRating'])->name('remove-rate');
    Route::get('/{newsId}/stats', [NewsUserActionController::class, 'getStats'])->name('stats');
    
    // User's personal lists
    Route::get('/user/liked', [NewsUserActionController::class, 'getLikedNews'])->name('user.liked');
    Route::get('/user/bookmarked', [NewsUserActionController::class, 'getBookmarkedNews'])->name('user.bookmarked');
});

// Legacy routes (keep for compatibility)
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news/categories', [NewsCategoryController::class, 'index']);
// php artisan make:observer NewsObserver --model=News
Route::get('/sitemap', [SitemapController::class, 'index']);
Route::get('/sitemap-main', [SitemapController::class, 'main']);
Route::get('/sitemap-categories', [SitemapController::class, 'categories']);
Route::get('/sitemap-news', [SitemapController::class, 'news']);            









// Payment Settings API Routes (Public - no auth required for reading settings)
Route::prefix('payment-settings')->name('api.payment-settings.')->group(function () {
    // Get all payment settings
    Route::get('/', [PaymentSettingsController::class, 'index'])->name('index');
    
    // Get specific payment method settings
    Route::get('/{method}', [PaymentSettingsController::class, 'getByMethod'])
        ->where('method', 'vietqr|cpay|vnpay|pay_at_hotel|general')
        ->name('method');
    
    // Test VietQR connection (public for frontend testing)
    Route::post('/test-vietqr', [PaymentSettingsController::class, 'testVietQR'])->name('test-vietqr');
});

// Payment Processing API Routes (existing)
Route::prefix('payment')->name('api.payment.')->group(function () {
    Route::post('/generate-vietqr', [PaymentController::class, 'generateVietQR'])->name('generate-vietqr');
    Route::post('/check-payment', [PaymentController::class, 'checkPayment'])->name('check-payment');
    Route::get('/methods', [PaymentController::class, 'getPaymentMethods'])->name('methods');
    Route::get('/config', [PaymentController::class, 'getPaymentConfig'])->name('config');
    Route::post('/vnpay', [PaymentController::class, 'processVNPay'])->name('vnpay');
    Route::post('/pay-at-hotel', [PaymentController::class, 'processPayAtHotel'])->name('pay-at-hotel');
});

