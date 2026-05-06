<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\TransactionController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API jalan bro'
    ]);
});

Route::get('/medical-records', [MedicalRecordController::class, 'index']);
Route::get('/pets/{id}/medical-records', [MedicalRecordController::class, 'byPet']);
Route::post('/medical-records', [MedicalRecordController::class, 'store']);

Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::get('/transactions/status/{status}', [TransactionController::class, 'byStatus']);