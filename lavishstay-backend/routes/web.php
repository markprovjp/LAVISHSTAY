<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\UserController;

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Phần này giữ nguyên
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/analytics/{id}', [DashboardController::class, 'analytics'])->name('analytics_id');
    Route::get('/settings/account', function () {
        return view('pages/settings/account');
    })->name('account');  
    Route::get('/settings/notifications', function () {
        return view('pages/settings/notifications');
    })->name('notifications');  
    
    



    //Bắt đầu code từ đây
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');

    Route::get('/admin/rooms', [RoomController::class, 'index'])->name('admin.rooms');







    //Rooms Types
    Route::get('/admin/room-types', [RoomTypeController::class, 'index'])->name('admin.room-types');

    Route::get('/admin/room-types/show/{room_type_id}', [RoomTypeController::class, 'show'])->name('admin.room-types.show');
    Route::get('/admin/room-types/create', [RoomTypeController::class, 'create'])->name('admin.room-types.create');
    Route::post('/admin/room-types/store', [RoomTypeController::class, 'create'])->name('admin.room-types.store');
    Route::get('/admin/room-types/store', [RoomTypeController::class, 'create'])->name('admin.room-types.auto-save');
    Route::get('/admin/room-types/{id}/edit', [RoomTypeController::class, 'edit'])->name('admin.room-types.edit');

       


    //FAQs
    Route::get('/admin/faqs', [FAQController::class, 'index'])->name('admin.faqs');
    Route::get('/admin/faqs/create', [FAQController::class, 'create'])->name('admin.faqs.create');
    Route::post('/admin/faqs/store', [FAQController::class, 'store'])->name('admin.faqs.store');
    Route::get('/admin/faqs/edit/{faqId}', [FAQController::class, 'edit'])->name('admin.faqs.edit');    
    Route::put('/admin/faqs/updat/{faqId}', [FAQController::class, 'update'])->name('admin.faqs.update');
    Route::post('/admin/faqs/destroy/{faqId}', [FAQController::class, 'destroy'])->name('admin.faqs.destroy');
    Route::patch('/admin/faqs/toggle-status/{faqId}', [FAQController::class, 'toggleStatus'])->name('faqs.toggle-status');
});
