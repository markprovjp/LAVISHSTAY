<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\RoomTypeDetailController;
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
use Illuminate\Support\Facades\Broadcast;
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
use App\Http\Controllers\Api\BookingServicePaymentController;
use App\Http\Controllers\NewsController\NewsCategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\PaymentSettingsController;
use App\Http\Controllers\Api\RoomTypeOverviewController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AdminCouponController;

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

// Broadcasting routes for notifications
Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::middleware('auth:sanctum')->get('/user/bookings', [BookingController::class, 'getUserBookings']);
Route::middleware('auth:sanctum')->post('/booking/assign', [BookingController::class, 'assignBookingToUser']);

// Public booking lookup routes (no auth required, with throttling)
Route::prefix('public')->middleware(['throttle:20,1'])->group(function () {
    Route::get('/bookings/search', [\App\Http\Controllers\Api\PublicBookingController::class, 'searchBookings'])
        ->name('public.bookings.search');
    Route::get('/bookings/{bookingId}/detail', [\App\Http\Controllers\Api\PublicBookingController::class, 'getBookingDetail'])
        ->name('public.bookings.detail');
    
    // Review routes
    Route::get('/bookings/{bookingId}/review-eligibility', [\App\Http\Controllers\Api\PublicReviewController::class, 'checkEligibility'])
        ->name('public.bookings.review.eligibility');
    Route::get('/bookings/{bookingId}/review', [\App\Http\Controllers\Api\PublicReviewController::class, 'getReview'])
        ->name('public.bookings.review.get');
    Route::post('/bookings/{bookingId}/review', [\App\Http\Controllers\Api\PublicReviewController::class, 'submitReview'])
        ->name('public.bookings.review.submit');
    Route::post('/review-media/upload', [\App\Http\Controllers\Api\PublicReviewController::class, 'uploadMedia'])
        ->name('public.review.media.upload');
});

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

// Room Types Overview API (for homepage)
Route::get('/room-types/overview', [\App\Http\Controllers\Api\RoomTypeOverviewController::class, 'overview']);
Route::post('/room-types/overview/clear-cache', [\App\Http\Controllers\Api\RoomTypeOverviewController::class, 'clearCache']);

// Room Type Detail API
Route::get('/room-types/{slug}', [\App\Http\Controllers\Api\RoomTypeDetailController::class, 'show']);
Route::post('/room-types/clear-cache', [\App\Http\Controllers\Api\RoomTypeDetailController::class, 'clearCache']);

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
Route::post('/bookings/{id}/checkout/compensation', [BookingCheckoutController::class, 'createCompensationRequest']);

    // Invoice PDF generation
    Route::get('/bookings/{bookingId}/invoice', [ReceptionController::class, 'generateInvoice']);

    // Service management for checkout and the checkout flow itself handled by BookingCheckoutController
    Route::get('/services/available', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'getAvailableServices']);
    Route::post('/bookings/{id}/services', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'addBookingService']);
    Route::put('/bookings/{id}/services/{serviceId}', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'updateBookingService']);
    Route::delete('/bookings/{id}/services/{serviceId}', [\App\Http\Controllers\Api\BookingCheckoutController::class, 'removeBookingService']);

    // Service Payment Management - VietQR + CPay integration
    Route::get('/bookings/{bookingId}/services/payment-info', [BookingServicePaymentController::class, 'getServicePaymentInfo']);
    Route::post('/bookings/{bookingId}/services/payment/qr', [BookingServicePaymentController::class, 'generateServicePaymentQR']);
    Route::post('/bookings/services/payment/check', [BookingServicePaymentController::class, 'checkServicePayment']);
    Route::get('/bookings/{bookingId}/services/payment-history', [BookingServicePaymentController::class, 'getServicePaymentHistory']);

    // Notifications (with auth middleware)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    });


    
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
        Route::get('/room-status', [ChartReceptionController::class, 'getRoomStatus']);
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

// Notification routes - require authentication and notification permissions
Route::middleware(['auth:sanctum', 'notification.owner'])->prefix('notifications')->group(function () {
    // Basic notification endpoints
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::get('/statistics', [NotificationController::class, 'statistics'])->name('notifications.statistics');
    
    // Mark as read endpoints
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/mark-multiple-read', [NotificationController::class, 'markMultipleAsRead'])->name('notifications.mark-multiple');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all');
    
    // Delete notifications
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // User notification settings
    Route::get('/settings', [NotificationController::class, 'getSettings'])->name('notifications.settings.get');
    Route::post('/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings.update');
});

// Admin-only notification management routes
Route::middleware(['auth:sanctum', 'notification.manage'])->prefix('notifications/admin')->group(function () {
    // Notification types management
    Route::get('/types', [NotificationController::class, 'getTypes'])->name('notifications.types');
    
    // Send test notifications
    Route::post('/send-test', [NotificationController::class, 'sendTest'])->name('notifications.send-test');
    
    // View all users' notifications (admin only)
    Route::get('/all', [NotificationController::class, 'getAllNotifications'])->name('notifications.all');
    
    // Notification statistics for all users
    Route::get('/statistics/global', [NotificationController::class, 'getGlobalStatistics'])->name('notifications.statistics.global');
    
    // Bulk operations
    Route::post('/bulk-delete', [NotificationController::class, 'bulkDelete'])->name('notifications.bulk-delete');
    Route::post('/bulk-mark-read', [NotificationController::class, 'bulkMarkAsRead'])->name('notifications.bulk-mark-read');
});

// Manager-level notification sending routes
Route::middleware(['auth:sanctum', 'notification.send'])->prefix('notifications/send')->group(function () {
    // Send notifications to specific users or roles
    Route::post('/to-users', [NotificationController::class, 'sendToUsers'])->name('notifications.send.users');
    Route::post('/to-roles', [NotificationController::class, 'sendToRoles'])->name('notifications.send.roles');
    Route::post('/broadcast', [NotificationController::class, 'broadcastNotification'])->name('notifications.broadcast');
});

// Webhook endpoints for external services (if needed)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('notifications/webhooks')->group(function () {
    // Payment service webhooks
    Route::post('/payment-success', [NotificationController::class, 'handlePaymentSuccess'])->name('notifications.webhook.payment.success');
    Route::post('/payment-failed', [NotificationController::class, 'handlePaymentFailed'])->name('notifications.webhook.payment.failed');
    
    // Booking service webhooks
    Route::post('/booking-created', [NotificationController::class, 'handleBookingCreated'])->name('notifications.webhook.booking.created');
    Route::post('/booking-cancelled', [NotificationController::class, 'handleBookingCancelled'])->name('notifications.webhook.booking.cancelled');
    
    // Review service webhooks
    Route::post('/review-submitted', [NotificationController::class, 'handleReviewSubmitted'])->name('notifications.webhook.review.submitted');
});

// Public notification endpoints (no auth required)
Route::prefix('notifications/public')->group(function () {
    // System status notifications
    Route::get('/system-status', [NotificationController::class, 'getSystemStatus'])->name('notifications.system-status');
    
    // Maintenance announcements
    Route::get('/maintenance', [NotificationController::class, 'getMaintenanceAnnouncements'])->name('notifications.maintenance');
});

// ================== COUPON ROUTES ==================
// Public coupon endpoints
Route::prefix('coupons')->group(function () {
    Route::post('/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
    Route::post('/check-code', [CouponController::class, 'checkCode'])->name('coupons.check');
    
    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/my-redemptions', [CouponController::class, 'userRedemptions'])->name('coupons.my-redemptions');
    });
});

// Apply coupon to booking
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings/{booking}/apply-coupon', [CouponController::class, 'applyToBooking'])->name('bookings.apply-coupon');
});

// Admin coupon management
Route::middleware(['auth:sanctum'])->prefix('admin/coupons')->group(function () {
    Route::get('/', [AdminCouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/', [AdminCouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('/statistics', [AdminCouponController::class, 'statistics'])->name('admin.coupons.statistics');
    Route::get('/{coupon}', [AdminCouponController::class, 'show'])->name('admin.coupons.show');
    Route::put('/{coupon}', [AdminCouponController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/{coupon}', [AdminCouponController::class, 'destroy'])->name('admin.coupons.destroy');
});

// Real-time notification testing endpoints (development only)
if (app()->environment(['local', 'staging'])) {
    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('notifications/dev')->group(function () {
        Route::post('/trigger-event/{event}', [NotificationController::class, 'triggerTestEvent'])->name('notifications.dev.trigger');
        Route::get('/pusher-test', [NotificationController::class, 'pusherTest'])->name('notifications.dev.pusher');
        Route::post('/fake-notification', [NotificationController::class, 'createFakeNotification'])->name('notifications.dev.fake');
    });
}