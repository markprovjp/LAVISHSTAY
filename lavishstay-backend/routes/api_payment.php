<?php
// File: routes/api_payment.php - Thêm vào routes/api.php

use App\Http\Controllers\PaymentController;

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
