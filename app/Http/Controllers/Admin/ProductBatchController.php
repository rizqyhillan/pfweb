<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProductBatch;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockCard;
use Illuminate\Http\Request;

class ProductBatchController extends Controller
{
    public function index()
    {
        $batches = ProductBatch::with(['barang', 'supplier'])->latest('tanggal_masuk')->paginate(15);
        return view('admin.product-batches.index', compact('batches'));
    }

    public function create()
    {
        $products = Product::where('is_aktif', true)->get();
        $suppliers = Supplier::all();
        return view('admin.product-batches.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'id_supplier' => 'nullable|exists:supplier,id',
            'no_batch' => 'nullable|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'nullable|date|after:tanggal_masuk',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $v['sisa_stok'] = $v['jumlah_masuk'];
        $batch = ProductBatch::create($v);
        Product::where('id', $v['id_barang'])->increment('stok', $v['jumlah_masuk']);
        $product = Product::find($v['id_barang']);
        StockCard::create([
            'id_barang' => $v['id_barang'], 'id_batch' => $batch->id,
            'tanggal' => $v['tanggal_masuk'], 'jenis_mutasi' => 'masuk',
            'jumlah' => $v['jumlah_masuk'], 'saldo' => $product->stok,
            'harga_satuan' => $v['harga_beli'],
            'referensi' => 'Batch #' . ($v['no_batch'] ?? $batch->id),
            'keterangan' => 'Penerimaan barang',
        ]);
        return redirect()->route('admin.product-batches.index')->with('success', 'Batch produk berhasil ditambahkan.');
    }

    public function edit(ProductBatch $product_batch)
    {
        $products = Product::where('is_aktif', true)->get();
        $suppliers = Supplier::all();
        return view('admin.product-batches.edit', compact('product_batch', 'products', 'suppliers'));
    }

    public function update(Request $request, ProductBatch $product_batch)
    {
        $v = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'id_supplier' => 'nullable|exists:supplier,id',
            'no_batch' => 'nullable|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'jumlah_masuk' => 'required|integer|min:1',
            'sisa_stok' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $product_batch->update($v);
        return redirect()->route('admin.product-batches.index')->with('success', 'Batch produk berhasil diperbarui.');
    }

    public function destroy(ProductBatch $product_batch)
    {
        Product::where('id', $product_batch->id_barang)->decrement('stok', $product_batch->sisa_stok);
        $product_batch->delete();
        return redirect()->route('admin.product-batches.index')->with('success', 'Batch produk berhasil dihapus.');
    }
}
