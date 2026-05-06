<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockCard;
use Illuminate\Http\Request;

class StockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = StockCard::with(['barang', 'batch']);
        if ($request->filled('id_barang')) {
            $query->where('id_barang', $request->id_barang);
        }
        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }
        $stockCards = $query->latest('tanggal')->pathPaginate(20, url('admin/stock-cards/page'));
        $products = Product::where('is_aktif', true)->get();

        return view('admin.stock-cards.index', compact('stockCards', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_aktif', true)->get();
        $batches = ProductBatch::with('barang')->where('sisa_stok', '>', 0)->get();

        return view('admin.stock-cards.create', compact('products', 'batches'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'id_batch' => 'nullable|exists:barang_batch,id',
            'jenis_mutasi' => 'required|in:masuk,keluar,adjustment,retur,expired',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'referensi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $product = Product::find($v['id_barang']);
        if (in_array($v['jenis_mutasi'], ['masuk', 'retur'])) {
            $product->increment('stok', $v['jumlah']);
        } else {
            $product->decrement('stok', $v['jumlah']);
        }
        if (! empty($v['id_batch'])) {
            $batch = ProductBatch::find($v['id_batch']);
            if (in_array($v['jenis_mutasi'], ['masuk', 'retur'])) {
                $batch->increment('sisa_stok', $v['jumlah']);
            } else {
                $batch->decrement('sisa_stok', $v['jumlah']);
            }
        }
        $product->refresh();
        StockCard::create([
            'id_barang' => $v['id_barang'], 'id_batch' => $v['id_batch'] ?? null,
            'tanggal' => now(), 'jenis_mutasi' => $v['jenis_mutasi'],
            'jumlah' => $v['jumlah'], 'saldo' => $product->stok,
            'harga_satuan' => $v['harga_satuan'] ?? 0,
            'referensi' => $v['referensi'], 'keterangan' => $v['keterangan'],
        ]);
        $labels = ['masuk' => 'Masuk', 'keluar' => 'Keluar', 'adjustment' => 'Penyesuaian', 'retur' => 'Retur', 'expired' => 'Kadaluarsa'];

        return redirect()->route('admin.stock-cards.index')->with('success', 'Mutasi stok ('.$labels[$v['jenis_mutasi']].') berhasil dicatat.');
    }

    public function show(StockCard $stock_card)
    {
        $stock_card->load(['barang', 'batch']);

        return view('admin.stock-cards.show', compact('stock_card'));
    }
}
