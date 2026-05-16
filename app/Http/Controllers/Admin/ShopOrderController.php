<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopOrderController extends Controller
{
    public function index(Request $request)
    {
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

        $shopOrder->load([
            'pelanggan',
            'barang.barang',
        ]);

        return view('admin.shop-orders.show', compact('shopOrder'));
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

            $shopOrder->update([
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? $shopOrder->catatan,
                'jumlah_bayar' => $validated['status'] === 'lunas' ? $shopOrder->total : $shopOrder->jumlah_bayar,
                'kembalian' => 0,
            ]);

            return redirect()
                ->route('admin.shop-orders.show', $shopOrder)
                ->with('success', 'Status pesanan berhasil diperbarui.');
        });
    }
}