<?php

namespace App\Http\Controllers;

use App\Models\PackageType;
use App\Models\Product;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function home()
    {
        // Featured products — active products, newest first, limit 6
        // Table: barang | Columns used: is_aktif
        $featuredProducts = $this->safeQuery(
            'barang',
            fn () => Product::where('is_aktif', true)->latest()->take(6)->get()
        );

        // Featured services — active services, limit 6
        // Table: layanan | Columns used: is_aktif
        $featuredServices = $this->safeQuery(
            'layanan',
            fn () => Service::where('is_aktif', true)->take(6)->get()
        );

        // Doctors — active users with role 'dokter', limit 6
        // Table: users (always exists)
        $doctors = $this->safeQuery(
            'users',
            fn () => User::where('role', 'dokter')
                ->where('is_aktif', true)
                ->latest()
                ->take(6)
                ->get()
        );

        // Grooming packages from package_types table
        // Table: package_types (may not have been migrated yet)
        $groomingPackages = $this->safeQuery(
            'package_types',
            fn () => PackageType::orderBy('harga_per_malam')->get()
        );

        // Boarding rooms — available rooms
        // Table: kamar | Column 'paket' may not exist in older schemas
        $boardingRooms = $this->safeQuery(
            'kamar',
            fn () => Room::where('status', 'tersedia')
                ->when(
                    Schema::hasColumn('kamar', 'paket'),
                    fn ($q) => $q->orderBy('paket')
                )
                ->take(6)
                ->get()
        );

        // Dynamic stats — each individually guarded
        $stats = [
            'totalDoctors' => $this->safeQuery('users', fn () => User::where('role', 'dokter')->where('is_aktif', true)->count(), 0),
            'totalProducts' => $this->safeQuery('barang', fn () => Product::where('is_aktif', true)->count(), 0),
            'totalServices' => $this->safeQuery('layanan', fn () => Service::where('is_aktif', true)->count(), 0),
            'totalRooms' => $this->safeQuery('kamar', fn () => Room::where('status', 'tersedia')->count(), 0),
        ];

        return view('pages.home', compact(
            'featuredProducts',
            'featuredServices',
            'doctors',
            'groomingPackages',
            'boardingRooms',
            'stats',
        ));
    }

    /**
     * Execute a query only if the table exists, returning a fallback on failure.
     *
     * @param  string  $table  The table name to check
     * @param  \Closure  $query  The query closure to execute
     * @param  mixed  $fallback  Value to return if table is missing or query fails
     */
    private function safeQuery(string $table, \Closure $query, mixed $fallback = null): mixed
    {
        if (! Schema::hasTable($table)) {
            return $fallback ?? collect();
        }

        try {
            return $query();
        } catch (\Throwable) {
            return $fallback ?? collect();
        }
    }

    public function doctors()
    {
        // TODO: Fetch doctors from database
        return view('pages.doctors');
    }

    public function services()
    {
        // TODO: Fetch services from database
        return view('pages.services');
    }

    public function departments()
    {
        // TODO: Fetch departments from database
        return view('pages.departments');
    }
}
