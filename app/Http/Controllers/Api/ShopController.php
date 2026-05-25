<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function bestSellers()
    {
        $products = Product::query()
            ->select('barang.*')
            ->selectRaw('COALESCE(SUM(transaksi_barang.jumlah), 0) as total_sold')
            ->leftJoin('transaksi_barang', 'barang.id', '=', 'transaksi_barang.id_barang')
            ->leftJoin('transaksi', function ($join) {
                $join->on('transaksi_barang.id_transaksi', '=', 'transaksi.id')
                    ->where('transaksi.jenis', '=', 'shop')
                    ->where('transaksi.status', '=', 'lunas');
            })
            ->where('barang.is_aktif', true)
            ->where('barang.stok', '>', 0)
            ->groupBy(
                'barang.id',
                'barang.nama_barang',
                'barang.kategori',
                'barang.harga',
                'barang.stok',
                'barang.satuan',
                'barang.deskripsi',
                'barang.is_aktif',
                'barang.image',
                'barang.created_at',
                'barang.updated_at'
            )
            ->orderByDesc('total_sold')
            ->latest('barang.created_at')
            ->take(4)
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
            ->where('stok', '>', 0)
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

            'total_sold' => (int) ($product->total_sold ?? 0),
            'is_featured' => ((int) ($product->total_sold ?? 0)) > 0,

            'tersedia' => $product->stok > 0 && $product->is_aktif,
        ];
    }
}