<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    /**
     * Dashboard karyawan — statistik ringkas.
     */
    public function dashboard()
    {
        $totalProducts      = Product::where('is_aktif', true)->count();
        $totalServices      = Service::where('is_aktif', true)->count();
        $todayTransactions  = Transaction::whereDate('tanggal', today())->count();
        $todayRevenue       = Transaction::whereDate('tanggal', today())
                                ->where('status', 'paid')->sum('total');

        $recentTransactions = Transaction::with(['pelanggan', 'kasir'])
            ->latest('tanggal')->take(5)->get();

        return view('karyawan.dashboard.index', compact(
            'totalProducts', 'totalServices', 'todayTransactions',
            'todayRevenue', 'recentTransactions'
        ));
    }

    /**
     * Daftar produk — READ ONLY.
     */
    public function products()
    {
        $products = Product::latest()->paginate(15);
        return view('karyawan.products.index', compact('products'));
    }

    /**
     * Daftar layanan — READ ONLY.
     */
    public function services()
    {
        $services = Service::with('dokter')
            ->where('is_aktif', true)->latest()->paginate(15);
        return view('karyawan.services.index', compact('services'));
    }

    // ========================================
    // TRANSACTIONS — READ + CREATE
    // ========================================

    /**
     * Daftar transaksi.
     */
    public function transactions()
    {
        $transactions = Transaction::with(['pelanggan', 'kasir'])
            ->latest('tanggal')->paginate(15);
        return view('karyawan.transactions.index', compact('transactions'));
    }

    /**
     * Form buat transaksi baru.
     */
    public function createTransaction()
    {
        $customers = User::where('role', 'admin')->get();
        $products  = Product::where('is_aktif', true)->where('stok', '>', 0)->get();
        $services  = Service::where('is_aktif', true)->get();

        return view('karyawan.transactions.create', compact('customers', 'products', 'services'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'  => 'required|exists:users,id',
            'jenis'         => 'required|in:produk,layanan,campuran',
            'metode_bayar'  => 'required|in:cash,transfer,qris',
            'catatan'       => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.type'  => 'required|in:product,service',
            'items.*.id'    => 'required|integer',
            'items.*.qty'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;

            // Hitung subtotal
            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $subtotal += $product->harga * $item['qty'];
                } else {
                    $service = Service::findOrFail($item['id']);
                    $subtotal += $service->harga * $item['qty'];
                }
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'id_pelanggan'   => $validated['id_pelanggan'],
                'id_kasir'       => Auth::id(),
                'kode_transaksi' => 'TRX-' . now()->format('Ymd') . '-' . str_pad(Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis'          => $validated['jenis'],
                'subtotal'       => $subtotal,
                'diskon'         => 0,
                'total'          => $subtotal,
                'jumlah_bayar'   => $subtotal,
                'kembalian'      => 0,
                'metode_bayar'   => $validated['metode_bayar'],
                'status'         => 'paid',
                'catatan'        => $validated['catatan'] ?? null,
                'tanggal'        => now(),
            ]);

            // Simpan detail items & update stok produk
            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $transaction->barang()->create([
                        'id_barang'  => $product->id,
                        'jumlah'     => $item['qty'],
                        'harga'      => $product->harga,
                        'subtotal'   => $product->harga * $item['qty'],
                    ]);
                    $product->decrement('stok', $item['qty']);
                } else {
                    $service = Service::findOrFail($item['id']);
                    $transaction->layanan()->create([
                        'id_layanan' => $service->id,
                        'jumlah'     => $item['qty'],
                        'harga'      => $service->harga,
                        'subtotal'   => $service->harga * $item['qty'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('karyawan.transactions')
                ->with('success', 'Transaksi ' . $transaction->kode_transaksi . ' berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Laporan ringkasan.
     */
    public function reports()
    {
        $totalRevenue      = Transaction::where('status', 'paid')->sum('total');
        $monthlyRevenue    = Transaction::where('status', 'paid')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->sum('total');
        $totalTransactions = Transaction::count();
        $paidTransactions  = Transaction::where('status', 'paid')->count();

        return view('karyawan.reports.index', compact(
            'totalRevenue', 'monthlyRevenue', 'totalTransactions', 'paidTransactions'
        ));
    }
}
