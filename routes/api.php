<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DoctorBookingController;

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

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [MobileAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::put('/change-password', [ProfileController::class, 'changePassword']);

    // Medical Records
    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
    Route::get('/pets/{id}/medical-records', [MedicalRecordController::class, 'byPet']);
    Route::post('/medical-records', [MedicalRecordController::class, 'store']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/transactions/status/{status}', [TransactionController::class, 'byStatus']);

    // Pets
    Route::get('/my-pets', [PetController::class, 'index']);
    Route::post('/my-pets', [PetController::class, 'store']);
    Route::put('/my-pets/{id}', [PetController::class, 'update']);
    Route::delete('/my-pets/{id}', [PetController::class, 'destroy']);

    // Doctor Booking
    Route::get('/doctors', [DoctorBookingController::class, 'doctors']);
    Route::get('/doctor-services', [DoctorBookingController::class, 'services']);
    Route::get('/doctor-schedules', [DoctorBookingController::class, 'schedules']);
    Route::post('/doctor-bookings', [DoctorBookingController::class, 'store']);
    Route::get('/my-doctor-bookings', [DoctorBookingController::class, 'myBookings']);
    Route::get('/doctor-bookings/{id}', [DoctorBookingController::class, 'show']);

});