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
use App\Http\Controllers\Admin\ProductBatchController;
use App\Http\Controllers\Admin\StockCardController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Karyawan\KaryawanController;
use Illuminate\Support\Facades\Auth;

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

// Breeze default /dashboard — redirect berdasarkan role
Route::get('/dashboard', function () {
    return match(Auth::user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'dokter'   => redirect()->route('doctor.dashboard'),
        'karyawan' => redirect()->route('karyawan.dashboard'),
        default    => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes — hanya role 'admin'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::resource('product-batches', ProductBatchController::class);
    Route::resource('stock-cards', StockCardController::class)->only(['index', 'create', 'store', 'show']);
});

// Doctor Routes — hanya role 'doctor'
Route::middleware(['auth', 'role:dokter'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/', function () { return redirect()->route('doctor.dashboard'); });
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [DoctorController::class, 'patients'])->name('patients');
    Route::get('/patients/create', [DoctorController::class, 'createPatient'])->name('patients.create');
    Route::post('/patients', [DoctorController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients/{pet}/edit', [DoctorController::class, 'editPatient'])->name('patients.edit');
    Route::put('/patients/{pet}', [DoctorController::class, 'updatePatient'])->name('patients.update');
    Route::delete('/patients/{pet}', [DoctorController::class, 'deletePatient'])->name('patients.destroy');
    Route::get('/schedule', [DoctorController::class, 'schedule'])->name('schedule');
    Route::get('/schedule/create', [DoctorController::class, 'createSchedule'])->name('schedule.create');
    Route::post('/schedule', [DoctorController::class, 'storeSchedule'])->name('schedule.store');
    Route::get('/schedule/{schedule}/edit', [DoctorController::class, 'editSchedule'])->name('schedule.edit');
    Route::put('/schedule/{schedule}', [DoctorController::class, 'updateSchedule'])->name('schedule.update');
    Route::delete('/schedule/{schedule}', [DoctorController::class, 'deleteSchedule'])->name('schedule.destroy');

    // Medical Records — CRUD
    Route::get('/medical-records', [DoctorController::class, 'medicalRecords'])->name('medical-records');
    Route::get('/medical-records/create', [DoctorController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records', [DoctorController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{medical_record}/edit', [DoctorController::class, 'editMedicalRecord'])->name('medical-records.edit');
    Route::put('/medical-records/{medical_record}', [DoctorController::class, 'updateMedicalRecord'])->name('medical-records.update');
    Route::delete('/medical-records/{medical_record}', [DoctorController::class, 'deleteMedicalRecord'])->name('medical-records.destroy');
});

// Karyawan Routes — hanya role 'karyawan'
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/', function () { return redirect()->route('karyawan.dashboard'); });
    Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [KaryawanController::class, 'products'])->name('products');
    Route::get('/services', [KaryawanController::class, 'services'])->name('services');
    Route::get('/reports', [KaryawanController::class, 'reports'])->name('reports');

    // Transactions — READ + CREATE
    Route::get('/transactions', [KaryawanController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/create', [KaryawanController::class, 'createTransaction'])->name('transactions.create');
    Route::post('/transactions', [KaryawanController::class, 'storeTransaction'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [KaryawanController::class, 'showTransaction'])->name('transactions.show');

    // Boardings - CRUD for Karyawan
    Route::resource('boardings', \App\Http\Controllers\Karyawan\BoardingController::class);
});

// Export Medical Records to Excel and pdf
Route::get('/admin/medical-records/export/excel', 
    [MedicalRecordController::class, 'exportExcel']
)->name('admin.medical-records.export.excel');

Route::get('/admin/medical-records/{hewan}/export-pdf', 
    [MedicalRecordController::class, 'exportPdf']
)->name('admin.medical-records.export.pdf');

require __DIR__.'/auth.php';

// Fallback Route (404)
Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});
