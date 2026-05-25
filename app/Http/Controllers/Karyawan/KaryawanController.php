<?php

namespace App\Http\Controllers\Karyawan;

use App\Events\LowStockAlert;
use App\Events\ProductStockUpdated;
use App\Events\TransactionCreatedRealtime;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Mail\TransactionCreated;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    /**
     * Dashboard karyawan — statistik ringkas.
     */
    public function dashboard()
    {
        $totalProducts = Product::where('is_aktif', true)->count();
        $totalServices = Service::where('is_aktif', true)->count();
        $todayTransactions = Transaction::whereDate('tanggal', today())->count();
        $todayRevenue = Transaction::whereDate('tanggal', today())
            ->where('status', 'lunas')->sum('total');

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
        $products = Product::latest()->pathPaginate(15, url('karyawan/products/page'));

        return view('karyawan.products.index', compact('products'));
    }

    /**
     * Daftar layanan — READ ONLY.
     */
    public function services()
    {
        $services = Service::with('dokter')
            ->where('is_aktif', true)->latest()->pathPaginate(15, url('karyawan/services/page'));

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
            ->latest('tanggal')
            ->pathPaginate(15, url('karyawan/transactions/page'));

        return view('karyawan.transactions.index', compact('transactions'));
    }

    /**
     * Form buat transaksi baru.
     */
    public function createTransaction()
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('is_aktif', true)->where('stok', '>', 0)->get();
        $services = Service::where('is_aktif', true)->get();

        return view('karyawan.transactions.create', compact('customers', 'products', 'services'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:users,id',
            'metode_bayar' => 'required|in:cash,transfer,ewallet',
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
            $hasP = ! empty($request->product_ids);
            $hasS = ! empty($request->service_ids);

            $jenis = 'campuran';
            if ($hasP && ! $hasS) {
                $jenis = 'barang';
            } elseif ($hasS && ! $hasP) {
                $jenis = 'layanan';
            }

            $diskon = $request->diskon ?? 0;

            $trx = Transaction::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_kasir' => Auth::id(),
                'kode_transaksi' => 'TRX-'.date('Ymd').'-'.str_pad(Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis' => $jenis,
                'subtotal' => 0,
                'diskon' => $diskon,
                'total' => 0,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => 0,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'lunas',
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
                        'subtotal' => $sub,
                    ]);
                    $p->decrement('stok', $qty);
                    $p->refresh();
                    try {
                        event(new ProductStockUpdated($p));
                        if ($p->stok <= 10) {
                            event(new LowStockAlert($p));
                        }
                    } catch (\Exception $e) {
                        Log::warning('Broadcast failed: '.$e->getMessage());
                    }
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
                        'subtotal' => $sub,
                    ]);
                }
            }

            $total = $subtotal - $diskon;
            $kembalian = max(0, $request->jumlah_bayar - $total);
            $trx->update(['subtotal' => $subtotal, 'total' => $total, 'kembalian' => $kembalian]);

            DB::commit();

            try {
                event(new TransactionCreatedRealtime($trx->fresh(['pelanggan', 'kasir'])));
                
                // Notify Admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SystemNotification(
                    'Transaksi Baru',
                    "Karyawan {$trx->kasir->nama} mencatat transaksi {$trx->kode_transaksi} sebesar Rp " . number_format($trx->total, 0, ',', '.'),
                    'success',
                    route('admin.transactions.show', $trx->id)
                ));
            } catch (\Exception $e) {
                Log::warning('Broadcast failed: '.$e->getMessage());
            }

            // Send Email Safely (Queued)
            try {
                $trx->load('pelanggan');
                $customer = $trx->pelanggan;
                if ($customer && $customer->email) {
                    Mail::to($customer->email)->queue(new TransactionCreated($trx));
                    Log::info('Transaction email queued for: '.$customer->email);
                }
            } catch (\Exception $mailEx) {
                Log::error('Mail queueing failed: '.$mailEx->getMessage());
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil!',
                    'transaction' => $trx->load(['pelanggan', 'kasir', 'barang.barang', 'layanan.layanan']),
                    'kembalian' => $kembalian,
                ]);
            }

            return redirect()->route('karyawan.transactions')->with('success', 'Transaksi berhasil! Kembalian: Rp '.number_format($kembalian, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal: '.$e->getMessage()], 422);
            }

            return back()->withInput()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function showTransaction(Transaction $transaction)
    {
        $transaction->load(['pelanggan', 'kasir', 'barang.barang', 'layanan.layanan']);

        return view('karyawan.transactions.show', compact('transaction'));
    }

    // ========================================
    // REPORTS — VIEW + EXPORT PDF + EXPORT EXCEL
    // ========================================

    /**
     * Laporan ringkasan.
     */
    public function reports(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::query();

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $totalRevenue = (clone $query)->where('status', 'lunas')->sum('total');
        $monthlyRevenue = Transaction::where('status', 'lunas')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->sum('total');
        $totalTransactions = (clone $query)->count();
        $paidTransactions = (clone $query)->where('status', 'lunas')->count();

        return view('karyawan.reports.index', compact(
            'totalRevenue', 'monthlyRevenue', 'totalTransactions', 'paidTransactions',
            'startDate', 'endDate'
        ));
    }

    /**
     * Export laporan transaksi ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::with(['pelanggan', 'kasir'])->latest('tanggal');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $transactions = $query->get();
        $totalTransactions = $transactions->count();
        $paidTransactions = $transactions->where('status', 'lunas')->count();
        $totalRevenue = $transactions->where('status', 'lunas')->sum('total');
        $totalSubtotal = $transactions->where('status', 'lunas')->sum('subtotal');
        $totalDiskon = $transactions->where('status', 'lunas')->sum('diskon');

        $pdf = Pdf::loadView('karyawan.reports.pdf', compact(
            'transactions', 'totalTransactions', 'paidTransactions',
            'totalRevenue', 'totalSubtotal', 'totalDiskon',
            'startDate', 'endDate'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-transaksi-'.now()->format('Y-m-d-His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan transaksi ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $filename = 'laporan-transaksi-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new TransactionExport($startDate, $endDate), $filename);
    }
}
