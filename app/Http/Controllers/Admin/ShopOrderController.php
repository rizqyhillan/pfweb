<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopOrderController extends Controller
{
    public function index(Request $request)
    {
        // Proactively sync recently created pending Midtrans transactions (limit 5 to avoid slow page loads)
        $pendingMidtrans = Transaction::where('status', 'pending')
            ->where('jenis', 'shopping')
            ->where('payment_provider', 'midtrans')
            ->where('tanggal', '>=', now()->subHours(24))
            ->limit(5)
            ->get();

        if ($pendingMidtrans->isNotEmpty()) {
            try {
                $midtrans = app(\App\Services\MidtransService::class);
                foreach ($pendingMidtrans as $trx) {
                    try {
                        $statusData = $midtrans->getTransactionStatus($trx->kode_transaksi);
                        $trx->updateStatusFromMidtrans($statusData);
                    } catch (\Exception $e) {
                        Log::warning('Auto sync in shop-order list failed for ' . $trx->kode_transaksi . ': ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Midtrans service not available during list sync: ' . $e->getMessage());
            }
        }

        $query = Transaction::with(['pelanggan', 'barang.barang'])
            ->where('jenis', 'shopping')
            ->latest('tanggal');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($customerQuery) use ($search) {
                        $customerQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.shop-orders.index', compact('orders'));
    }

    public function show(Transaction $shopOrder)
    {
        abort_if($shopOrder->jenis !== 'shopping', 404);

        // Proactively sync payment status from Midtrans if still pending
        if ($shopOrder->status === 'pending' && $shopOrder->payment_provider === 'midtrans') {
            try {
                $midtrans = app(\App\Services\MidtransService::class);
                $statusData = $midtrans->getTransactionStatus($shopOrder->kode_transaksi);
                $shopOrder->updateStatusFromMidtrans($statusData);
                $shopOrder->refresh();
            } catch (\Exception $e) {
                Log::error('Admin shop-order sync Midtrans failed: ' . $e->getMessage());
            }
        }

        $shopOrder->load([
            'pelanggan',
            'barang.barang',
        ]);

        return view('admin.shop-orders.show', compact('shopOrder'));
    }

    /**
     * Manually sync payment status from Midtrans API.
     */
    public function syncMidtrans(Transaction $shopOrder)
    {
        abort_if($shopOrder->jenis !== 'shopping', 404);

        if ($shopOrder->payment_provider !== 'midtrans') {
            return redirect()
                ->route('admin.shop-orders.show', $shopOrder)
                ->with('error', 'Transaksi ini tidak menggunakan Midtrans.');
        }

        try {
            $midtrans = app(\App\Services\MidtransService::class);
            $statusData = $midtrans->getTransactionStatus($shopOrder->kode_transaksi);
            $shopOrder->updateStatusFromMidtrans($statusData);
            $shopOrder->refresh();

            $statusLabel = ucfirst($shopOrder->payment_status ?? $shopOrder->status);

            return redirect()
                ->route('admin.shop-orders.show', $shopOrder)
                ->with('success', "Status berhasil disinkronkan dari Midtrans. Status saat ini: {$statusLabel}");
        } catch (\Exception $e) {
            Log::error('Manual Midtrans sync failed: ' . $e->getMessage());
            return redirect()
                ->route('admin.shop-orders.show', $shopOrder)
                ->with('error', 'Gagal menyinkronkan status dari Midtrans: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Transaction $shopOrder)
    {
        abort_if($shopOrder->jenis !== 'shopping', 404);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,lunas,batal'],
            'catatan' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($shopOrder, $validated) {
            $shopOrder = Transaction::with('barang.barang')
                ->where('id', $shopOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($shopOrder->status === 'batal' && $validated['status'] !== 'batal') {
                return redirect()
                    ->route('admin.shop-orders.show', $shopOrder)
                    ->with('error', 'Transaksi yang sudah batal tidak bisa diaktifkan kembali.');
            }

            if ($shopOrder->status === 'lunas' && $validated['status'] === 'batal') {
                return redirect()
                    ->route('admin.shop-orders.show', $shopOrder)
                    ->with('error', 'Transaksi yang sudah lunas tidak bisa langsung dibatalkan dari menu ini.');
            }

            if ($shopOrder->status === 'pending' && $validated['status'] === 'batal') {
                foreach ($shopOrder->barang as $item) {
                    if ($item->barang) {
                        $item->barang->increment('stok', $item->jumlah);
                    }
                }
            }

            $updateData = [
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? $shopOrder->catatan,
                'jumlah_bayar' => $validated['status'] === 'lunas' ? $shopOrder->total : $shopOrder->jumlah_bayar,
                'kembalian' => 0,
            ];

            // If manually marking as lunas, also update payment fields
            if ($validated['status'] === 'lunas' && $shopOrder->status !== 'lunas') {
                $updateData['payment_status'] = 'settlement';
                $updateData['paid_at'] = now();
            }

            $shopOrder->update($updateData);

            return redirect()
                ->route('admin.shop-orders.show', $shopOrder)
                ->with('success', 'Status pesanan berhasil diperbarui.');
        });
    }
}