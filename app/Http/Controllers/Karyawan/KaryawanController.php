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
        $request->validate([
            'id_pelanggan' => 'required|exists:users,id',
            'metode_bayar' => 'required|in:cash,transfer,ewallet,qris',
            'diskon' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:barang,id',
            'product_qtys' => 'nullable|array',
            'product_qtys.*' => 'integer|min:1',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:layanan,id',
            'service_qtys' => 'nullable|array',
            'service_qtys.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $hasP = !empty($request->product_ids);
            $hasS = !empty($request->service_ids);
            
            $jenis = 'campuran';
            if ($hasP && !$hasS) {
                $jenis = 'barang';
            } elseif ($hasS && !$hasP) {
                $jenis = 'layanan';
            }

            $diskon = $request->diskon ?? 0;

            $trx = Transaction::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_kasir' => Auth::id(),
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis' => $jenis,
                'subtotal' => 0,
                'diskon' => $diskon,
                'total' => 0,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => 0,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'paid',
                'catatan' => $request->catatan,
                'tanggal' => now(),
            ]);

            if ($hasP) {
                foreach ($request->product_ids as $i => $pid) {
                    $p = Product::find($pid);
                    $qty = $request->product_qtys[$i] ?? 1;
                    $sub = $p->harga * $qty;
                    $subtotal += $sub;
                    $trx->barang()->create([
                        'id_barang' => $pid, 
                        'jumlah' => $qty, 
                        'harga_satuan' => $p->harga, 
                        'subtotal' => $sub
                    ]);
                    $p->decrement('stok', $qty);
                }
            }
            if ($hasS) {
                foreach ($request->service_ids as $i => $sid) {
                    $s = Service::find($sid);
                    $qty = $request->service_qtys[$i] ?? 1;
                    $sub = $s->harga * $qty;
                    $subtotal += $sub;
                    $trx->layanan()->create([
                        'id_layanan' => $sid, 
                        'jumlah' => $qty, 
                        'harga_satuan' => $s->harga, 
                        'subtotal' => $sub
                    ]);
                }
            }

            $total = $subtotal - $diskon;
            $kembalian = max(0, $request->jumlah_bayar - $total);
            $trx->update(['subtotal' => $subtotal, 'total' => $total, 'kembalian' => $kembalian]);

            DB::commit();
            return redirect()->route('karyawan.transactions')->with('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
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
