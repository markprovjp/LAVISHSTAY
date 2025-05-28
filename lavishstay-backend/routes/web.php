<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypesController;
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
    Route::get('/admin/room-types', [RoomTypesController::class, 'index'])->name('admin.room-types');
    Route::get('/admin/room-types/create', [RoomTypesController::class, 'create'])->name('admin.room-types.create');
    Route::post('/admin/room-types/store', [RoomTypesController::class, 'create'])->name('admin.room-types.store');
    Route::get('/admin/room-types/store', [RoomTypesController::class, 'create'])->name('admin.room-types.auto-save');
    Route::get('/admin/room-types/{id}/edit', [RoomTypesController::class, 'edit'])->name('admin.room-types.edit');

       
});
