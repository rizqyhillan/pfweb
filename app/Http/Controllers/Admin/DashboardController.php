<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Transaction;
use App\Models\Boarding;
use App\Models\MedicalRecord;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPets = Pet::count();
        $totalProducts = Product::count();
        $totalServices = Service::where('is_aktif', true)->count();
        $totalUsers = User::count();
        $totalSuppliers = Supplier::count();
        $activeBoarding = Boarding::where('status', 'aktif')->count();
        $todayTransactions = Transaction::whereDate('tanggal', Carbon::today())->count();
        $monthlyRevenue = Transaction::where('status', 'lunas')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('total');
        $recentTransactions = Transaction::with(['pelanggan', 'kasir'])->latest('tanggal')->take(5)->get();
        $recentMedicalRecords = MedicalRecord::with(['hewan', 'dokter'])->latest('tanggal')->take(5)->get();
        $lowStockProducts = Product::where('is_aktif', true)->where('stok', '<', 10)->orderBy('stok')->take(5)->get();
        $nearExpiredBatches = ProductBatch::with('barang')->where('sisa_stok', '>', 0)
            ->whereNotNull('tanggal_expired')
            ->where('tanggal_expired', '<=', Carbon::now()->addDays(30))
            ->orderBy('tanggal_expired')->take(5)->get();

        $todayRevenue = Transaction::whereDate('tanggal', Carbon::today())
            ->where('status', 'lunas')
            ->sum('total');

        return view('admin.dashboard', compact(
            'totalPets', 'totalProducts', 'totalServices', 'totalUsers', 'totalSuppliers',
            'activeBoarding', 'todayTransactions', 'monthlyRevenue', 'todayRevenue',
            'recentTransactions', 'recentMedicalRecords', 'lowStockProducts', 'nearExpiredBatches'
        ));
    }
}
