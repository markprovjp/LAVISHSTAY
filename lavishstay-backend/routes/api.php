<?php

use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\RoomTypeController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

=======
use App\Http\Controllers\RoomOptionController;
use App\Http\Controllers\BookingController;
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
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
<<<<<<< HEAD
// Route::apiResource('room-types', RoomTypeController::class);
=======
Route::apiResource('room-types', RoomTypeController::class);
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc



//FAQs API
<<<<<<< HEAD
Route::apiResource('faqs', FAQController::class);
=======
Route::apiResource('faqs', FAQController::class);



Route::apiResource('room-options', RoomOptionController::class);
Route::post('bookings', [BookingController::class, 'store']);
Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
