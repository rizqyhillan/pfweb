<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function products(Request $request)
    {
        $query = Product::query()
            ->where('is_aktif', true)
            ->where('stok', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $products = $query
            ->orderBy('nama_barang')
            ->get()
            ->map(fn ($product) => $this->formatProduct($product));

        return response()->json([
            'data' => $products,
        ]);
    }

    public function productDetail($id)
    {
        $product = Product::where('is_aktif', true)
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatProduct($product),
        ]);
    }

    public function categories()
    {
        $categories = Product::where('is_aktif', true)
            ->whereNotNull('kategori')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->values();

        return response()->json([
            'data' => $categories,
        ]);
    }

    private function formatProduct(Product $product): array
    {
        $image = $product->image ?? null;

        return [
            'id' => $product->id,
            'nama_barang' => $product->nama_barang,
            'kategori' => $product->kategori,
            'harga' => (float) $product->harga,
            'stok' => (int) $product->stok,
            'is_aktif' => (bool) $product->is_aktif,

            'image' => $image,
            'image_url' => $image ? asset('storage/' . $image) : null,

            'tersedia' => $product->stok > 0 && $product->is_aktif,
        ];
    }
}