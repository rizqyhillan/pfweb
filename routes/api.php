<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DoctorBookingController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\ShopCartController;
use App\Http\Controllers\Api\BoardingController;
use App\Http\Controllers\Api\MidtransCallbackController;

// Midtrans Webhook — public endpoint (no auth)
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'API jalan bro'
    ]);
});

Route::post('/login', [MobileAuthController::class, 'login']);
Route::post('/register', [MobileAuthController::class, 'register']);
Route::post('/send-otp', [MobileAuthController::class, 'sendOtp']);
Route::post('/verify-otp', [MobileAuthController::class, 'verifyOtpAndRegister']);

Route::post('/forgot-password/send-otp', [MobileAuthController::class, 'sendForgotPasswordOtp']);
Route::post('/forgot-password/verify-otp', [MobileAuthController::class, 'verifyForgotPasswordOtp']);
Route::post('/forgot-password/reset', [MobileAuthController::class, 'resetForgotPassword']);

// Grooming (publik - bisa dilihat tanpa login)
Route::get('/grooming/packages', [TransactionController::class, 'getGroomingPackages']);
Route::get('/grooming/availability', [TransactionController::class, 'getGroomingAvailability']);

Route::get('/doctors', [DoctorBookingController::class, 'doctors']);
Route::get('/doctor-services', [DoctorBookingController::class, 'services']);
Route::get('/doctor-schedules', [DoctorBookingController::class, 'schedules']);
Route::get('/doctor-availability', [DoctorBookingController::class, 'availability']);

// Shopping public 
Route::get('/shop/products', [ShopController::class, 'products']);
Route::get('/shop/products/best-sellers', [ShopController::class, 'bestSellers']);
Route::get('/shop/categories', [ShopController::class, 'categories']);
Route::get('/shop/products/{id}', [ShopController::class, 'productDetail']);

Route::get('/boarding/rooms', [BoardingController::class, 'rooms']);
Route::get('/boarding/rooms/{id}', [BoardingController::class, 'roomDetail']);
Route::post('/boarding/estimate', [BoardingController::class, 'estimate']);
Route::post('/boarding/book', [BoardingController::class, 'store']);
Route::get('/my-boardings', [BoardingController::class, 'myBoardings']);
Route::get('/boardings/{id}', [BoardingController::class, 'show']);
Route::post('/boardings/{id}/cancel', [BoardingController::class, 'cancel']);
Route::post('/boardings/{id}/reschedule', [BoardingController::class, 'reschedule']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [MobileAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::put('/change-password', [ProfileController::class, 'changePassword']);

    // Medical Records
    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
    Route::get('/pets/{id}/medical-records', [MedicalRecordController::class, 'byPet']);
    Route::get('/medical-records/pet/{id}', [MedicalRecordController::class, 'byPet']);
    Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show']);
    Route::post('/medical-records', [MedicalRecordController::class, 'store']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/status/{status}', [TransactionController::class, 'byStatus']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    
    // Grooming
    Route::post('/grooming/book', [TransactionController::class, 'storeGrooming']);
    Route::get('/my-grooming-bookings', [TransactionController::class, 'myGroomingBookings']);
    Route::get('/grooming-bookings/{id}', [TransactionController::class, 'showGrooming']);
    Route::post('/grooming-bookings/{id}/cancel', [TransactionController::class, 'cancelGrooming']);
    Route::post('/grooming-bookings/{id}/reschedule', [TransactionController::class, 'rescheduleGrooming']);

    // Shop
    Route::post('/checkout', [TransactionController::class, 'checkout']);

    // Shopping Cart
    Route::get('/shop/cart', [ShopCartController::class, 'index']);
    Route::post('/shop/cart/items', [ShopCartController::class, 'addItem']);
    Route::patch('/shop/cart/items/{item}', [ShopCartController::class, 'updateItem']);
    Route::delete('/shop/cart/items/{item}', [ShopCartController::class, 'removeItem']);
    Route::delete('/shop/cart', [ShopCartController::class, 'clear']);
    Route::post('/shop/checkout', [ShopCartController::class, 'checkout']);
    Route::post('/shop/transactions/{id}/cancel', [ShopCartController::class, 'cancelTransaction']);

    // Pets
    Route::get('/my-pets', [PetController::class, 'index']);
    Route::post('/my-pets', [PetController::class, 'store']);
    Route::put('/my-pets/{id}', [PetController::class, 'update']);
    Route::delete('/my-pets/{id}', [PetController::class, 'destroy']);

    // Doctor Booking
    // Route::get('/doctors', [DoctorBookingController::class, 'doctors']);
    // Route::get('/doctor-services', [DoctorBookingController::class, 'services']);
    // Route::get('/doctor-schedules', [DoctorBookingController::class, 'schedules']);
    // Route::get('/doctor-availability', [DoctorBookingController::class, 'availability']);
    Route::post('/doctor-bookings', [DoctorBookingController::class, 'store']);
    Route::get('/my-doctor-bookings', [DoctorBookingController::class, 'myBookings']);
    Route::get('/doctor-bookings/{id}', [DoctorBookingController::class, 'show']);
    Route::post('/doctor-bookings/{id}/cancel', [DoctorBookingController::class, 'cancel']);
    Route::post('/doctor-bookings/{id}/reschedule', [DoctorBookingController::class, 'reschedule']);

    // Boarding / Penitipan
    // Route::get('/boarding/rooms', [BoardingController::class, 'rooms']);
    // Route::get('/boarding/rooms/{id}', [BoardingController::class, 'roomDetail']);
    // Route::post('/boarding/estimate', [BoardingController::class, 'estimate']);
    // Route::post('/boarding/book', [BoardingController::class, 'store']);
    // Route::get('/my-boardings', [BoardingController::class, 'myBoardings']);
    // Route::get('/boardings/{id}', [BoardingController::class, 'show']);
    // Route::post('/boardings/{id}/cancel', [BoardingController::class, 'cancel']);
    // Route::post('/boardings/{id}/reschedule', [BoardingController::class, 'reschedule']);

});