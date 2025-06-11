<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancellationPolicyController;
use App\Http\Controllers\CheckinPolicyController;
use App\Http\Controllers\CheckoutPolicyController;
use App\Http\Controllers\RoomTypeAmenityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ServiceAmenityController;
use App\Http\Controllers\ServiceBedController;
use App\Http\Controllers\ServiceMealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DepositPolicyController;
use App\Http\Controllers\RoomModificationController;
use App\Http\Controllers\TranslationController;

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




    //User//////////////////////////////////
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/admin/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::delete('/admin/users/destroy/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');


    //Rooms Types/////////////////////////////////
    Route::get('/admin/room-types', [RoomTypeController::class, 'index'])->name('admin.room-types');
    Route::get('/admin/room-types/create', [RoomTypeController::class, 'create'])->name('admin.room-types.create');
    Route::post('/admin/room-types/store', [RoomTypeController::class, 'store'])->name('admin.room-types.store');
    Route::post('/admin/room-types/auto-save', [RoomTypeController::class, 'autoSave'])->name('admin.room-types.auto-save');
    Route::get('/admin/room-types/edit/{roomTypeId}', [RoomTypeController::class, 'edit'])->name('admin.room-types.edit');
    Route::put('/admin/room-types/update/{roomTypeId}', [RoomTypeController::class, 'update'])->name('admin.room-types.update');
    Route::post('/admin/room-types/destroy/{roomTypeId}', [RoomTypeController::class, 'destroy'])->name('admin.room-types.destroy');
    Route::get('/admin/room-types/show/{roomTypeId}', [RoomTypeController::class, 'show'])->name('admin.room-types.show');
        

    

    // Room //////////////////////////////////////////
    Route::get('/admin/rooms/type/{room_type_id}', [RoomController::class, 'roomsByType'])->name('admin.rooms.by-type');
    Route::get('/admin/rooms/show/{room_id}', [RoomController::class, 'show'])->name('admin.rooms.show');
    Route::get('/admin/rooms/{room_id}/calendar-data', [RoomController::class, 'getCalendarData'])->name('rooms.calendar-data');

    Route::get('/admin/rooms/type/{roomType}/create', [RoomController::class, 'create'])->name('admin.rooms.create');
    Route::post('/admin/rooms/type/{roomType}/store', [RoomController::class, 'store'])->name('admin.rooms.store');
    Route::get('/admin/rooms/type/{room}/edit', [RoomController::class, 'edit'])->name('admin.rooms.edit');
    Route::put('/admin/rooms/type/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
    Route::delete('/admin/rooms/type/{room}/delete', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');


    // Cách liiiiiiiiiiiiiiiiiiii

   //Room Types Images Management
    Route::get('/admin/room-types/{roomType}/images', [RoomTypeController::class, 'images'])->name('admin.room-types.images');
    Route::post('/admin/room-types/{roomType}/images/upload', [RoomTypeController::class, 'uploadImages'])->name('admin.room-types.images.upload');
    Route::patch('/admin/room-types/{roomType}/images/{imageId}/update', [RoomTypeController::class, 'updateImage'])->name('admin.room-types.images.update');
    Route::patch('/admin/room-types/{roomType}/images/{imageId}/set-main', [RoomTypeController::class, 'setMainImage'])->name('admin.room-types.images.set-main');
    Route::delete('/admin/room-types/{roomType}/images/{imageId}', [RoomTypeController::class, 'deleteImage'])->name('admin.room-types.images.delete');
    
    // Room Type Amenities Management
    Route::get('/admin/room-types/{roomType}/amenities', [RoomTypeAmenityController::class, 'index'])
        ->name('admin.room-types.amenities');
    Route::post('/admin/room-types/{roomType}/amenities', [RoomTypeAmenityController::class, 'store'])
        ->name('admin.room-types.amenities.store');
    Route::delete('/admin/room-types/{roomType}/amenities/{amenity}', [RoomTypeAmenityController::class, 'destroy'])
        ->name('admin.room-types.amenities.destroy');
    Route::patch('/admin/room-types/{roomType}/amenities/{amenity}/highlight', [RoomTypeAmenityController::class, 'toggleHighlight'])
        ->name('admin.room-types.amenities.highlight');
    Route::patch('/admin/room-types/{roomType}/amenities/highlight-all', [RoomTypeAmenityController::class, 'highlightAll'])
        ->name('admin.room-types.amenities.highlight-all');
    // Cách liiiiiiiiiiiii





    // Amenities Management Kiệt //////////////////////////////////
    Route::get('/admin/services/amenities', [ServiceAmenityController::class, 'index'])->name('admin.services.amenities');
    Route::get('/admin/services/amenities/create', [ServiceAmenityController::class, 'create'])->name('admin.services.amenities.create');
    Route::post('/admin/services/amenities/store', [ServiceAmenityController::class, 'store'])->name('admin.services.amenities.store');
    Route::get('/admin/services/amenities/edit/{amenityId}', [ServiceAmenityController::class, 'edit'])->name('admin.services.amenities.edit');
    Route::put('/admin/services/amenities/update/{amenityId}', [ServiceAmenityController::class, 'update'])->name('admin.services.amenities.update');
    Route::post('/admin/services/amenities/destroy/{amenityId}', [ServiceAmenityController::class, 'destroy'])->name('admin.services.amenities.destroy');
    Route::patch('/admin/services/amenities/toggle-status/{amenity}', [ServiceAmenityController::class, 'toggleStatus'])->name('admin.services.amenities.toggle-status');



    //  Meals Management Công //////////////////////////////////
    Route::get('/admin/services/meals', [ServiceMealController::class, 'index'])->name('admin.services.meals');
    Route::get('/admin/services/meals/create', [ServiceMealController::class, 'create'])->name('admin.services.meals.create');
    Route::post('/admin/services/meals/store', [ServiceMealController::class, 'store'])->name('admin.services.meals.store');
    Route::get('/admin/services/meals/edit/{id}', [ServiceMealController::class, 'edit'])->name('admin.services.meals.edit');
    Route::put('/admin/services/meals/update/{id}', [ServiceMealController::class, 'update'])->name('admin.services.meals.update');
    Route::post('/admin/services/meals/destroy/{id}', [ServiceMealController::class, 'destroy'])->name('admin.services.meals.destroy');
    Route::patch('/admin/services/meals/toggle-status/{mealType}', [ServiceMealController::class, 'toggleStatus'])->name('admin.services.meals.toggle-status');



    //  Meals Management Tuyên //////////////////////////////////
    Route::get('/admin/services/beds', [ServiceBedController::class, 'index'])->name('admin.services.beds');
    Route::get('/admin/services/beds/create', [ServiceBedController::class, 'create'])->name('admin.services.beds.create');
    Route::post('/admin/services/beds/store', [ServiceBedController::class, 'store'])->name('admin.services.beds.store');
    Route::get('/admin/services/beds/edit/{id}', [ServiceBedController::class, 'edit'])->name('admin.services.beds.edit');
    Route::put('/admin/services/beds/update/{id}', [ServiceBedController::class, 'update'])->name('admin.services.beds.update');
    Route::post('/admin/services/beds/destroy/{id}', [ServiceBedController::class, 'destroy'])->name('admin.services.beds.destroy');
    Route::patch('/admin/services/beds/toggle-status/{bedType}', [ServiceBedController::class, 'toggleStatus'])->name('admin.services.beds.toggle-status');


    // Languages
    Route::get('/admin/multinational/languages', [LanguageController::class, 'index'])->name('admin.multinational.languages');
    Route::get('/admin/multinational/languages/create', [LanguageController::class, 'create'])->name('admin.multinational.languages.create');
    Route::post('/admin/multinational/languages/store', [LanguageController::class, 'store'])->name('admin.multinational.languages.store');
    Route::get('/admin/multinational/languages/edit/{language_code}', [LanguageController::class, 'edit'])->name('admin.multinational.languages.edit');
    Route::put('/admin/multinational/languages/update/{language_code}', [LanguageController::class, 'update'])->name('admin.multinational.languages.update');
    Route::post('/admin/multinational/languages/destroy/{language_code}', [LanguageController::class, 'destroy'])->name('admin.multinational.languages.destroy');

    // Currencies
    Route::get('/admin/multinational/currencies', [CurrencyController::class, 'index'])->name('admin.multinational.currencies');
    Route::get('/admin/multinational/currencies/create', [CurrencyController::class, 'create'])->name('admin.multinational.currencies.create');
    Route::post('/admin/multinational/currencies/store', [CurrencyController::class, 'store'])->name('admin.multinational.currencies.store');
    Route::get('/admin/multinational/currencies/edit/{currency_code}', [CurrencyController::class, 'edit'])->name('admin.multinational.currencies.edit');
    Route::put('/admin/multinational/currencies/update/{currency_code}', [CurrencyController::class, 'update'])->name('admin.multinational.currencies.update');
    Route::post('/admin/multinational/currencies/destroy/{currency_code}', [CurrencyController::class, 'destroy'])->name('admin.multinational.currencies.destroy');

    // Translation
    Route::prefix('admin/translation')->name('admin.translation.')->group(function () {
        Route::get('/', [TranslationController::class, 'index'])->name('index');
        Route::get('/create', [TranslationController::class, 'create'])->name('create');
        Route::post('/store', [TranslationController::class, 'store'])->name('store');
        Route::get('/edit/{translationId}', [TranslationController::class, 'edit'])->name('edit');
        Route::put('/update/{translationId}', [TranslationController::class, 'update'])->name('update');
        Route::post('/destroy/{translationId}', [TranslationController::class, 'destroy'])->name('destroy');
    });



    // Bookings/////////////////////////////////////////////////////////////////
    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.bookings');









    // Room modification
    Route::get('/admin/room-modifications', [RoomModificationController::class, 'index'])->name('admin.room_modifications');





    // Chính sách hủy phòng///////////////////////////////////////////////////
    Route::get('/admin/cancellation-policies', [CancellationPolicyController::class, 'index'])->name('admin.cancellation-policies');
    Route::get('/admin/cancellation-policies/show/{id}', [CancellationPolicyController::class, 'index'])->name('admin.cancellation-policies.show');

    Route::get('/admin/cancellation-policies/create', [CancellationPolicyController::class, 'create'])->name('admin.cancellation-policies.create');
    Route::post('/admin/cancellation-policies/store', [CancellationPolicyController::class, 'store'])->name('admin.cancellation-policies.store');
    Route::get('/admin/cancellation-policies/edit/{id}', [CancellationPolicyController::class, 'edit'])->name('admin.cancellation-policies.edit');
    Route::put('/admin/cancellation-policies/update/{id}', [CancellationPolicyController::class, 'update'])->name('admin.cancellation-policies.update');
    Route::post('/admin/cancellation-policies/destroy/{id}', [CancellationPolicyController::class, 'destroy'])->name('admin.cancellation-policies.destroy');
    Route::patch('/admin/cancellation-policies/toggle-status/{id}', [CancellationPolicyController::class, 'toggleStatus'])->name('admin.cancellation-policies.toggle-status');



    // Chính sách đặt cọc///////////////////////////////////////////////////
    Route::get('/admin/deposit-policies', [DepositPolicyController::class, 'index'])->name('admin.deposit-policies');




    // Chính sách checkin ///////////////////////////////////////////////////
    Route::get('/admin/checkout-policies', [CheckinPolicyController::class, 'index'])->name('admin.checkin-policies');


    // Chính sách checkout ///////////////////////////////////////////////////
    Route::get('/admin/checkout-policies', [CheckoutPolicyController::class, 'index'])->name('admin.checkout-policies');



    
















    //FAQs
    Route::get('/admin/faqs', [FAQController::class, 'index'])->name('admin.faqs');
    Route::get('/admin/faqs/create', [FAQController::class, 'create'])->name('admin.faqs.create');
    Route::post('/admin/faqs/store', [FAQController::class, 'store'])->name('admin.faqs.store');
    Route::get('/admin/faqs/edit/{faqId}', [FAQController::class, 'edit'])->name('admin.faqs.edit');    
    Route::put('/admin/faqs/updat/{faqId}', [FAQController::class, 'update'])->name('admin.faqs.update');
    Route::post('/admin/faqs/destroy/{faqId}', [FAQController::class, 'destroy'])->name('admin.faqs.destroy');
    Route::patch('/admin/faqs/toggle-status/{faqId}', [FAQController::class, 'toggleStatus'])->name('faqs.toggle-status');


});
