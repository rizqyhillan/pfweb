<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Transaction;
use App\Models\Boarding;
use App\Models\MedicalRecord;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPets = Pet::count();
        $totalProducts = Product::count();
        $totalServices = Service::where('is_active', true)->count();
        $totalUsers = User::count();

        $activeBoarding = Boarding::where('status', 'active')->count();

        $todayTransactions = Transaction::whereDate('date', Carbon::today())->count();

        $monthlyRevenue = Transaction::where('status', 'paid')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('total');

        $recentTransactions = Transaction::with(['customer', 'cashier'])
            ->latest('date')
            ->take(5)
            ->get();

        $recentMedicalRecords = MedicalRecord::with(['pet', 'doctor'])
            ->latest('date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPets',
            'totalProducts',
            'totalServices',
            'totalUsers',
            'activeBoarding',
            'todayTransactions',
            'monthlyRevenue',
            'recentTransactions',
            'recentMedicalRecords'
        ));
    }
}
