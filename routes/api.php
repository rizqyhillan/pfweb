<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\MobileAuthController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API jalan bro'
    ]);
});

Route::post('/login', [MobileAuthController::class, 'login']);
Route::post('/register', [MobileAuthController::class, 'register']);
Route::post('/send-otp', [MobileAuthController::class, 'sendOtp']);
Route::post('/verify-otp', [MobileAuthController::class, 'verifyOtpAndRegister']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [MobileAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Medical Records
    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
    Route::get('/pets/{id}/medical-records', [MedicalRecordController::class, 'byPet']);
    Route::post('/medical-records', [MedicalRecordController::class, 'store']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/transactions/status/{status}', [TransactionController::class, 'byStatus']);

});