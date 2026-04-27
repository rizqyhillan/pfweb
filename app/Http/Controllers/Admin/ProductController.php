<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);
        $v['is_aktif'] = 1;
        $v['satuan'] = $v['satuan'] ?? 'pcs';
        Product::create($v);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $v = $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);
        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 0;
        $product->update($v);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
