<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->pathPaginate(15, url('admin/products/page'));

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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'variations' => 'nullable|array',
            'variations.*.nama_variasi' => 'nullable|string|max:100',
            'variations.*.harga' => 'nullable|numeric|min:0',
            'variations.*.stok' => 'nullable|integer|min:0',
        ]);

        $v['is_aktif'] = 1;
        $v['satuan'] = $v['satuan'] ?? 'pcs';

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
        }

        $v['image'] = $imagePaths[0] ?? null;

        $variationsData = $v['variations'] ?? [];
        unset($v['images']);
        unset($v['variations']);

        $product = Product::create($v);

        foreach ($imagePaths as $path) {
            $product->images()->create(['path' => $path]);
        }

        if (! empty($variationsData)) {
            foreach ($variationsData as $variation) {
                if (empty($variation['nama_variasi']) && empty($variation['harga']) && empty($variation['stok'])) {
                    continue;
                }

                $product->variations()->create([
                    'nama_variasi' => $variation['nama_variasi'],
                    'harga' => $variation['harga'] ?? $v['harga'],
                    'stok' => $variation['stok'] ?? 0,
                ]);
            }
        }

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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'deleted_images' => 'nullable|string',
            'variations' => 'nullable|array',
            'variations.*.nama_variasi' => 'nullable|string|max:100',
            'variations.*.harga' => 'nullable|numeric|min:0',
            'variations.*.stok' => 'nullable|integer|min:0',
        ]);

        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        if (!empty($v['deleted_images'])) {
            $deletedIds = explode(',', $v['deleted_images']);
            foreach ($deletedIds as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        unset($v['deleted_images']);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
        }

        if (count($imagePaths)) {
            foreach ($imagePaths as $path) {
                $product->images()->create(['path' => $path]);
            }

            $v['image'] = $product->image ?: $imagePaths[0];
        } else {
            $v['image'] = $product->image;
        }

        unset($v['images']);

        $variationsData = $v['variations'] ?? [];
        unset($v['variations']);

        $product->update($v);

        if ($request->has('variations')) {
            $product->variations()->delete();

            foreach ($variationsData as $variation) {
                if (empty($variation['nama_variasi']) && empty($variation['harga']) && empty($variation['stok'])) {
                    continue;
                }

                $product->variations()->create([
                    'nama_variasi' => $variation['nama_variasi'],
                    'harga' => $variation['harga'] ?? $v['harga'],
                    'stok' => $variation['stok'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
