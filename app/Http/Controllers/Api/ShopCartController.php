<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ShopCartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getActiveCart($request->user()->id);

        $cart->load(['items.barang', 'items.variasi']);

        return response()->json([
            'data' => $this->formatCart($cart),
        ]);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => ['required', 'exists:barang,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'id_variasi' => ['nullable', 'exists:product_variations,id'],
        ]);

        $product = Product::where('id', $validated['id_barang'])
            ->where('is_aktif', true)
            ->firstOrFail();

        $variation = null;
        if (!empty($validated['id_variasi'])) {
            $variation = $product->variations()->findOrFail($validated['id_variasi']);
            $price = $variation->harga;
            $stock = $variation->stok;
        } else {
            $price = $product->harga;
            $stock = $product->stok;
        }

        if ($stock < $validated['jumlah']) {
            return response()->json([
                'message' => 'Stok produk tidak mencukupi.',
            ], 422);
        }

        $cart = $this->getActiveCart($request->user()->id);

        $itemQuery = CartItem::where('id_keranjang', $cart->id)
            ->where('id_barang', $product->id);

        if ($variation) {
            $itemQuery->where('id_variasi', $variation->id);
        } else {
            $itemQuery->whereNull('id_variasi');
        }

        $item = $itemQuery->first();

        if ($item) {
            $newQty = $item->jumlah + $validated['jumlah'];

            if ($stock < $newQty) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi untuk jumlah tersebut.',
                ], 422);
            }

            $item->update([
                'jumlah' => $newQty,
                'harga_satuan' => $price,
                'subtotal' => $price * $newQty,
            ]);
        } else {
            $item = CartItem::create([
                'id_keranjang' => $cart->id,
                'id_barang' => $product->id,
                'id_variasi' => $variation ? $variation->id : null,
                'jumlah' => $validated['jumlah'],
                'harga_satuan' => $price,
                'subtotal' => $price * $validated['jumlah'],
            ]);
        }

        $cart->load(['items.barang', 'items.variasi']);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'data' => $this->formatCart($cart),
        ]);
    }

    public function updateItem(Request $request, $item)
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getActiveCart($request->user()->id);

        $cartItem = CartItem::with(['barang', 'variasi'])
            ->where('id', $item)
            ->where('id_keranjang', $cart->id)
            ->firstOrFail();

        $product = $cartItem->barang;
        $variation = $cartItem->variasi;

        if (!$product || !$product->is_aktif) {
            return response()->json([
                'message' => 'Produk tidak tersedia.',
            ], 422);
        }

        $stock = $variation ? $variation->stok : $product->stok;
        $price = $variation ? $variation->harga : $product->harga;

        if ($stock < $validated['jumlah']) {
            return response()->json([
                'message' => 'Stok produk tidak mencukupi.',
            ], 422);
        }

        $cartItem->update([
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $price,
            'subtotal' => $price * $validated['jumlah'],
        ]);

        $cart->load(['items.barang', 'items.variasi']);

        return response()->json([
            'message' => 'Jumlah produk berhasil diperbarui.',
            'data' => $this->formatCart($cart),
        ]);
    }

    public function removeItem(Request $request, $item)
    {
        $cart = $this->getActiveCart($request->user()->id);

        $cartItem = CartItem::where('id', $item)
            ->where('id_keranjang', $cart->id)
            ->firstOrFail();

        $cartItem->delete();

        $cart->load(['items.barang', 'items.variasi']);

        return response()->json([
            'message' => 'Produk berhasil dihapus dari keranjang.',
            'data' => $this->formatCart($cart),
        ]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getActiveCart($request->user()->id);

        $cart->items()->delete();

        $cart->load(['items.barang', 'items.variasi']);

        return response()->json([
            'message' => 'Keranjang berhasil dikosongkan.',
            'data' => $this->formatCart($cart),
        ]);
    }

    public function cancelTransaction(Request $request, $id)
    {
        $user = $request->user();
    
        return DB::transaction(function () use ($user, $id) {
            $transaction = Transaction::with(['barang.barang', 'barang.variasi'])
                ->where('id', $id)
                ->where('id_pelanggan', $user->id)
                ->where('jenis', 'shopping')
                ->lockForUpdate()
                ->firstOrFail();
    
            if ($transaction->status !== 'pending') {
                return response()->json([
                    'message' => 'Transaksi hanya bisa dibatalkan jika status masih pending.',
                ], 422);
            }
    
            foreach ($transaction->barang as $item) {
                if ($item->id_variasi) {
                    $variation = \App\Models\ProductVariation::find($item->id_variasi);
                    if ($variation) {
                        $variation->increment('stok', $item->jumlah);
                    }
                } else if ($item->barang) {
                    $item->barang->increment('stok', $item->jumlah);
                }
            }
    
            $transaction->update([
                'status' => 'batal',
                'catatan' => trim(($transaction->catatan ? $transaction->catatan . "\n" : '') . 'Dibatalkan oleh customer.'),
            ]);
    
            $transaction->load([
                'pelanggan',
                'barang.barang',
                'barang.variasi',
                'layanan.layanan',
            ]);
    
            return response()->json([
                'message' => 'Transaksi berhasil dibatalkan dan stok dikembalikan.',
                'data' => [
                    'id' => $transaction->id,
                    'kode_transaksi' => $transaction->kode_transaksi,
                    'jenis' => $transaction->jenis,
                    'status' => $transaction->status,
                    'subtotal' => (float) $transaction->subtotal,
                    'total' => (float) $transaction->total,
                    'metode_bayar' => $transaction->metode_bayar,
                    'catatan' => $transaction->catatan,
                    'tanggal' => optional($transaction->tanggal)->format('Y-m-d H:i:s'),
                    'items' => $transaction->barang->map(function ($item) {
                        $product = $item->barang;
                        $variation = $item->variasi;
                        $image = $product->image ?? null;
                        
                        $nama = $product->nama_barang ?? '-';
                        if ($variation) {
                            $nama .= " (Variasi: " . $variation->nama_variasi . ")";
                        }
    
                        return [
                            'id' => $item->id,
                            'id_barang' => $item->id_barang,
                            'id_variasi' => $item->id_variasi,
                            'nama_barang' => $nama,
                            'kategori' => $product->kategori ?? null,
                            'image' => $image,
                            'image_url' => $image ? asset('storage/' . $image) : null,
                            'jumlah' => (int) $item->jumlah,
                            'harga_satuan' => (float) $item->harga_satuan,
                            'subtotal' => (float) $item->subtotal,
                        ];
                    }),
                ],
            ]);
        });
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'catatan' => ['nullable', 'string'],
            'metode_bayar' => ['nullable', 'in:cash,transfer,ewallet'],
        ]);

        $user = $request->user();

        $cart = $this->getActiveCart($user->id);
        $cart->load(['items.barang', 'items.variasi']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang masih kosong.',
            ], 422);
        }

        return DB::transaction(function () use ($cart, $user, $validated) {
            $subtotal = 0;
            $itemDetails = [];

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->id_barang)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$product->is_aktif) {
                    return response()->json([
                        'message' => "Produk {$product->nama_barang} sudah tidak aktif.",
                    ], 422);
                }

                $variation = null;
                if ($item->id_variasi) {
                    $variation = $product->variations()->where('id', $item->id_variasi)->lockForUpdate()->first();
                    if (!$variation) {
                        return response()->json([
                            'message' => "Variasi produk {$product->nama_barang} tidak ditemukan.",
                        ], 422);
                    }
                    $stock = $variation->stok;
                    $price = $variation->harga;
                } else {
                    $stock = $product->stok;
                    $price = $product->harga;
                }

                if ($stock < $item->jumlah) {
                    return response()->json([
                        'message' => "Stok produk {$product->nama_barang} tidak mencukupi.",
                    ], 422);
                }

                $lineTotal = $price * $item->jumlah;
                $subtotal += $lineTotal;

                $itemName = $product->nama_barang;
                if ($variation) {
                    $itemName .= " - " . $variation->nama_variasi;
                }

                $itemDetails[] = [
                    'id'       => (string) $product->id . ($variation ? '-' . $variation->id : ''),
                    'price'    => (int) $price,
                    'quantity' => (int) $item->jumlah,
                    'name'     => mb_substr($itemName, 0, 50),
                ];
            }

            $kodeTransaksi = 'SHOP-' . now()->format('YmdHis') . '-' . $user->id;
            $metodeBayar   = $validated['metode_bayar'] ?? 'ewallet';

            $transaction = Transaction::create([
                'id_pelanggan'   => $user->id,
                'id_kasir'       => null,
                'kode_transaksi' => $kodeTransaksi,
                'jenis'          => 'shopping',
                'subtotal'       => $subtotal,
                'diskon'         => 0,
                'total'          => $subtotal,
                'jumlah_bayar'   => 0,
                'kembalian'      => 0,
                'metode_bayar'   => $metodeBayar,
                'status'         => 'pending',
                'catatan'        => $validated['catatan'] ?? null,
                'tanggal'        => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->id_barang)
                    ->lockForUpdate()
                    ->firstOrFail();

                $variation = null;
                if ($item->id_variasi) {
                    $variation = $product->variations()->where('id', $item->id_variasi)->lockForUpdate()->firstOrFail();
                    $price = $variation->harga;
                } else {
                    $price = $product->harga;
                }

                $transaction->barang()->create([
                    'id_barang'    => $product->id,
                    'id_variasi'   => $item->id_variasi,
                    'jumlah'       => $item->jumlah,
                    'harga_satuan' => $price,
                    'subtotal'     => $price * $item->jumlah,
                ]);

                if ($variation) {
                    $variation->decrement('stok', $item->jumlah);
                } else {
                    $product->decrement('stok', $item->jumlah);
                }
            }

            $cart->update([
                'status' => 'checkout',
            ]);

            $snapToken   = null;
            $redirectUrl = null;

            if (in_array($metodeBayar, ['transfer', 'ewallet'])) {
                try {
                    $midtrans = app(\App\Services\MidtransService::class);

                    $snap = $midtrans->createSnapToken(
                        orderId: $kodeTransaksi,
                        grossAmount: (int) $subtotal,
                        customerDetails: [
                            'first_name' => $user->nama,
                            'email'      => $user->email,
                            'phone'      => $user->no_hp ?? '',
                        ],
                        itemDetails: $itemDetails,
                    );

                    $snapToken   = $snap['token'];
                    $redirectUrl = $snap['redirect_url'];

                    $duration = config('services.midtrans.expiry_duration', 12);
                    $unit = config('services.midtrans.expiry_unit', 'hour');
                    $expiredAt = now();
                    if ($unit === 'minute') {
                        $expiredAt = $expiredAt->addMinutes($duration);
                    } elseif ($unit === 'day') {
                        $expiredAt = $expiredAt->addDays($duration);
                    } else {
                        $expiredAt = $expiredAt->addHours($duration);
                    }

                    $transaction->update([
                        'payment_provider'     => 'midtrans',
                        'payment_token'        => $snapToken,
                        'payment_redirect_url' => $redirectUrl,
                        'payment_status'       => 'pending',
                        'payment_expired_at'   => $expiredAt,
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Midtrans Snap failed: ' . $e->getMessage());
                }
            }

            $transaction->load([
                'pelanggan',
                'barang.barang',
                'barang.variasi',
            ]);

            return response()->json([
                'message' => 'Checkout berhasil dibuat.',
                'data' => [
                    'id'              => $transaction->id,
                    'kode_transaksi'  => $transaction->kode_transaksi,
                    'jenis'           => $transaction->jenis,
                    'status'          => $transaction->status,
                    'metode_bayar'    => $transaction->metode_bayar,
                    'subtotal'        => (float) $transaction->subtotal,
                    'diskon'          => (float) $transaction->diskon,
                    'total'           => (float) $transaction->total,
                    'catatan'         => $transaction->catatan,
                    'tanggal'         => optional($transaction->tanggal)->format('Y-m-d H:i:s'),
                    'snap_token'      => $snapToken,
                    'redirect_url'    => $redirectUrl,
                    'payment_status'  => $transaction->payment_status,
                    'items' => $transaction->barang->map(function ($item) {
                        $product = $item->barang;
                        $variation = $item->variasi;
                        $image = $product->image ?? null;

                        $nama = $product->nama_barang ?? '-';
                        if ($variation) {
                            $nama .= " (Variasi: " . $variation->nama_variasi . ")";
                        }

                        return [
                            'id'           => $item->id,
                            'id_barang'    => $item->id_barang,
                            'id_variasi'   => $item->id_variasi,
                            'nama_barang'  => $nama,
                            'kategori'     => $product->kategori ?? null,
                            'image'        => $image,
                            'image_url'    => $image ? asset('storage/' . $image) : null,
                            'jumlah'       => (int) $item->jumlah,
                            'harga_satuan' => (float) $item->harga_satuan,
                            'subtotal'     => (float) $item->subtotal,
                        ];
                    }),
                ],
            ], 201);
        });
    }

    private function getActiveCart(int $userId): Cart
    {
        return Cart::firstOrCreate(
            [
                'id_user' => $userId,
                'status' => 'aktif',
            ],
            [
                'id_user' => $userId,
                'status' => 'aktif',
            ]
        );
    }

    private function formatCart(Cart $cart): array
    {
        $items = $cart->items->map(function ($item) {
            $product = $item->barang;
            $variation = $item->variasi;
            $image = $product->image ?? null;

            $harga = $variation ? (float) $variation->harga : (float) ($product->harga ?? 0);
            $stok = $variation ? (int) $variation->stok : (int) ($product->stok ?? 0);
            $nama = $product->nama_barang ?? '-';
            if ($variation) {
                $nama .= " (Variasi: " . $variation->nama_variasi . ")";
            }

            return [
                'id' => $item->id,
                'id_barang' => $item->id_barang,
                'id_variasi' => $item->id_variasi,
                'nama_barang' => $nama,
                'nama_variasi' => $variation ? $variation->nama_variasi : null,
                'kategori' => $product->kategori ?? null,
                'image' => $image,
                'image_url' => $image ? asset('storage/' . $image) : null,
                'jumlah' => (int) $item->jumlah,
                'harga_satuan' => $harga,
                'subtotal' => $harga * $item->jumlah,
                'stok' => $stok,
                'tersedia' => $product ? ((bool) $product->is_aktif && $stok > 0) : false,
            ];
        });

        return [
            'id' => $cart->id,
            'status' => $cart->status,
            'items' => $items,
            'total_item' => $items->sum('jumlah'),
            'total_harga' => $items->sum('subtotal'),
        ];
    }
}