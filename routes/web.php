<?php

use App\Http\Controllers\Admin\BoardingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroomingController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\PackageTypeController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\ProductBatchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StockCardController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Karyawan\KaryawanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DoctorBookingController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\DoctorServiceController;
use App\Http\Controllers\Admin\ShopOrderController;
use App\Http\Controllers\Admin\HomeBannerController;

// use Illuminate\Support\Facades\Mail;

// Dynamic Pages (using Controller)
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
});

// Static Pages removed for single-page architecture

// Breeze default /dashboard — redirect berdasarkan role
Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'dokter' => redirect()->route('doctor.dashboard'),
        'karyawan' => redirect()->route('karyawan.dashboard'),
        default => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Admin Routes — hanya role 'admin'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pets', PetController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('package-types', PackageTypeController::class);
    Route::put('boardings/{boarding}/status', [BoardingController::class, 'updateStatus'])->name('boardings.update-status');
    Route::resource('boardings', BoardingController::class);
    Route::resource('home-banners', HomeBannerController::class)->except(['show']);

    Route::get('groomings/{grooming}/payment', [GroomingController::class, 'payment'])->name('groomings.payment');
    Route::post('groomings/{grooming}/pay', [GroomingController::class, 'pay'])->name('groomings.pay');
    Route::put('groomings/{grooming}/status', [GroomingController::class, 'updateStatus'])->name('groomings.update-status');
    Route::resource('groomings', GroomingController::class);
    
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('users', UserController::class);
    Route::resource('product-batches', ProductBatchController::class);
    Route::resource('stock-cards', StockCardController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('doctor-bookings/{doctorBooking}/payment', [DoctorBookingController::class, 'payment'])->name('doctor-bookings.payment');
    Route::post('doctor-bookings/{doctorBooking}/pay', [DoctorBookingController::class, 'pay'])->name('doctor-bookings.pay');
    Route::put('doctor-bookings/{doctorBooking}/status', [DoctorBookingController::class, 'updateStatus'])->name('doctor-bookings.update-status');
    Route::resource('doctor-bookings', DoctorBookingController::class);
    Route::resource('doctor-schedules', DoctorScheduleController::class);
    Route::resource('doctor-services', DoctorServiceController::class);
    Route::get('shop-orders', [ShopOrderController::class, 'index'])->name('shop-orders.index');
    Route::get('shop-orders/{shopOrder}', [ShopOrderController::class, 'show'])->name('shop-orders.show');
    Route::put('shop-orders/{shopOrder}/status', [ShopOrderController::class, 'updateStatus'])->name('shop-orders.update-status');
    Route::post('shop-orders/{shopOrder}/sync-midtrans', [ShopOrderController::class, 'syncMidtrans'])->name('shop-orders.sync-midtrans');
    

    // Path-based pagination routes
    Route::get('pets/page/{page}', [PetController::class, 'index'])->name('pets.page');
    Route::get('products/page/{page}', [ProductController::class, 'index'])->name('products.page');
    Route::get('services/page/{page}', [ServiceController::class, 'index'])->name('services.page');
    Route::get('transactions/page/{page}', [TransactionController::class, 'index'])->name('transactions.page');
    Route::get('rooms/page/{page}', [RoomController::class, 'index'])->name('rooms.page');
    Route::get('package-types/page/{page}', [PackageTypeController::class, 'index'])->name('package-types.page');
    Route::get('boardings/page/{page}', [BoardingController::class, 'index'])->name('boardings.page');
    Route::get('home-banners/page/{page}', [HomeBannerController::class, 'index'])->name('home-banners.page');
    Route::get('groomings/page/{page}', [GroomingController::class, 'index'])->name('groomings.page');
    Route::get('medical-records/page/{page}', [MedicalRecordController::class, 'index'])->name('medical-records.page');
    Route::get('suppliers/page/{page}', [SupplierController::class, 'index'])->name('suppliers.page');
    Route::get('users/page/{page}', [UserController::class, 'index'])->name('users.page');
    Route::get('users/role/{role}', [UserController::class, 'index'])->name('users.role');
    Route::get('users/role/{role}/page/{page}', [UserController::class, 'index'])->name('users.role.page');
    Route::get('product-batches/page/{page}', [ProductBatchController::class, 'index'])->name('product-batches.page');
    Route::get('stock-cards/page/{page}', [StockCardController::class, 'index'])->name('stock-cards.page');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
});

// Doctor Routes — hanya role 'doctor'
Route::middleware(['auth', 'role:dokter'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('doctor.dashboard');
    });
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [DoctorController::class, 'patients'])->name('patients');
    Route::get('/patients/page/{page}', [DoctorController::class, 'patients'])->name('patients.page');
    Route::get('/patients/create', [DoctorController::class, 'createPatient'])->name('patients.create');
    Route::post('/patients', [DoctorController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients/{pet}', [DoctorController::class, 'showPatient'])->name('patients.show');
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
    Route::get('/medical-records/page/{page}', [DoctorController::class, 'medicalRecords'])->name('medical-records.page');
    Route::get('/medical-records/create', [DoctorController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records', [DoctorController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{medical_record}', [DoctorController::class, 'showMedicalRecord'])->name('medical-records.show');
    Route::get('/medical-records/{medical_record}/edit', [DoctorController::class, 'editMedicalRecord'])->name('medical-records.edit');
    Route::put('/medical-records/{medical_record}', [DoctorController::class, 'updateMedicalRecord'])->name('medical-records.update');
    Route::delete('/medical-records/{medical_record}', [DoctorController::class, 'deleteMedicalRecord'])->name('medical-records.destroy');
});

// Karyawan Routes — hanya role 'karyawan'
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('karyawan.dashboard');
    });
    Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [KaryawanController::class, 'products'])->name('products');
    Route::get('/products/page/{page}', [KaryawanController::class, 'products'])->name('products.page');
    Route::get('/services', [KaryawanController::class, 'services'])->name('services');
    Route::get('/services/page/{page}', [KaryawanController::class, 'services'])->name('services.page');

    // Transactions — READ + CREATE
    Route::get('/transactions', [KaryawanController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/page/{page}', [KaryawanController::class, 'transactions'])->name('transactions.page');
    Route::get('/transactions/create', [KaryawanController::class, 'createTransaction'])->name('transactions.create');
    Route::post('/transactions', [KaryawanController::class, 'storeTransaction'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [KaryawanController::class, 'showTransaction'])->name('transactions.show');

    // Boardings - CRUD for Karyawan
    Route::resource('boardings', App\Http\Controllers\Karyawan\BoardingController::class);
    Route::get('boardings/page/{page}', [App\Http\Controllers\Karyawan\BoardingController::class, 'index'])->name('boardings.page');

    // Groomings for Karyawan
    Route::get('groomings/{grooming}/payment', [App\Http\Controllers\Karyawan\GroomingController::class, 'payment'])->name('groomings.payment');
    Route::post('groomings/{grooming}/pay', [App\Http\Controllers\Karyawan\GroomingController::class, 'pay'])->name('groomings.pay');
    Route::put('groomings/{grooming}/status', [App\Http\Controllers\Karyawan\GroomingController::class, 'updateStatus'])->name('groomings.update-status');
    Route::resource('groomings', App\Http\Controllers\Karyawan\GroomingController::class)->only(['index']);
    Route::get('groomings/page/{page}', [App\Http\Controllers\Karyawan\GroomingController::class, 'index'])->name('groomings.page');

    // Doctor Bookings for Karyawan
    Route::get('doctor-bookings/{doctorBooking}/payment', [App\Http\Controllers\Karyawan\DoctorBookingController::class, 'payment'])->name('doctor-bookings.payment');
    Route::post('doctor-bookings/{doctorBooking}/pay', [App\Http\Controllers\Karyawan\DoctorBookingController::class, 'pay'])->name('doctor-bookings.pay');
    Route::put('doctor-bookings/{doctorBooking}/status', [App\Http\Controllers\Karyawan\DoctorBookingController::class, 'updateStatus'])->name('doctor-bookings.update-status');
    Route::resource('doctor-bookings', App\Http\Controllers\Karyawan\DoctorBookingController::class)->only(['index', 'show']);
});

// Export Medical Records to Excel dan PDF
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

// Route::get('/test-email', function () {

//     Mail::raw('EMAILNYA BISA NJIR KLASJFKLJSDALFKJ', function ($message) {
//         $message->to('aslanalfarizy97@gmail.com')
//                 ->subject('Test Email PawPet');
//     });

//     return 'Email berhasil dikirim';
// });
