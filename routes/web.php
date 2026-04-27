<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BoardingController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;

// Dynamic Pages (using Controller)
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/doctors', 'doctors')->name('doctors');
    Route::get('/services', 'services')->name('services');
    Route::get('/departments', 'departments')->name('departments');
});

// Static Pages (using Route::view)
Route::view('/about', 'pages.about')->name('about');
Route::view('/appointment', 'pages.appointment')->name('appointment');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/department-details', 'pages.department-details')->name('department-details');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/service-details', 'pages.service-details')->name('service-details');
Route::view('/starter-page', 'pages.starter-page')->name('starter-page');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/testimonials', 'pages.testimonials')->name('testimonials');

// Breeze Routes - Redirect to admin dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pets', PetController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('boardings', BoardingController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';

// Fallback Route (404)
Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});
