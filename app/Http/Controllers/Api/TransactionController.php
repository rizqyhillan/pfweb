<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\PackageType;
use App\Models\Grooming;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with([
                'pelanggan',
                'kasir',
                'barang.barang',
                'layanan.layanan',
            ])
            ->where('id_pelanggan', $request->user()->id)
            ->latest('tanggal')
            ->get()
            ->map(fn ($transaction) => $this->formatTransaction($transaction));

        return response()->json([
            'data' => $transactions,
        ]);
    }

    public function show(Request $request, $id)
    {
        $transaction = Transaction::with([
                'pelanggan',
                'kasir',
                'barang.barang',
                'layanan.layanan',
            ])
            ->where('id_pelanggan', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatTransaction($transaction),
        ]);
    }

    public function byStatus(Request $request, $status)
    {
        $allowed = ['lunas', 'pending', 'batal'];

        if (!in_array($status, $allowed)) {
            return response()->json([
                'message' => 'Status tidak valid. Gunakan: lunas, pending, atau batal.',
            ], 422);
        }

        $transactions = Transaction::with([
                'pelanggan',
                'kasir',
                'barang.barang',
                'layanan.layanan',
            ])
            ->where('id_pelanggan', $request->user()->id)
            ->where('status', $status)
            ->latest('tanggal')
            ->get()
            ->map(fn ($transaction) => $this->formatTransaction($transaction));

        return response()->json([
            'data' => $transactions,
        ]);
    }

    /**
     * GET /api/grooming/packages
     * Ambil semua paket grooming
     */
    public function getGroomingPackages()
    {
        $packages = PackageType::all();
        return response()->json(['data' => $packages]);
    }

    /**
     * GET /api/grooming/availability
     * Ambil hari dan jam tersedia untuk grooming
     */
    public function getGroomingAvailability()
    {
        $days = [];
        $now = Carbon::now();
        // Berikan opsi 5 hari ke depan mulai besok
        for ($i = 1; $i <= 5; $i++) {
            $date = $now->copy()->addDays($i);
            $days[] = [
                'day' => $date->locale('id')->isoFormat('ddd'),
                'date' => $date->format('d'),
                'full_date' => $date->format('Y-m-d'),
                'month_year' => $date->locale('id')->isoFormat('MMMM YYYY')
            ];
        }

        $pagi = ['09:00', '10:00', '11:00'];
        $siang = ['13:00', '14:00', '15:00', '16:00'];

        return response()->json([
            'data' => [
                'days' => $days,
                'times' => [
                    'pagi' => $pagi,
                    'siang' => $siang
                ]
            ]
        ]);
    }

    /**
     * POST /api/grooming/book
     * Buat booking grooming baru
     */
    public function storeGrooming(Request $request)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_paket' => 'required|exists:package_types,id',
            'tanggal_grooming' => 'required|date',
            'waktu_grooming' => 'required|date_format:H:i',
            'catatan_grooming' => 'nullable|string',
        ]);

        // Verifikasi kepemilikan hewan
        $pet = \App\Models\Pet::where('id', $v['id_hewan'])
            ->where('id_pemilik', $request->user()->id)
            ->first();

        if (!$pet) {
            return response()->json(['message' => 'Hewan tidak ditemukan atau bukan milik Anda'], 403);
        }

        $paket = PackageType::find($v['id_paket']);
        $v['total_biaya'] = $paket->harga_per_malam ?? 0;
        $v['status'] = 'pending';

        $grooming = Grooming::create($v);

        return response()->json([
            'message' => 'Booking grooming berhasil',
            'data' => $grooming
        ], 201);
    }

    private function formatTransaction(Transaction $transaction): array
    {
        $barangItems = $transaction->barang->map(function ($item) {
            $product = $item->barang;
            $image = $product->image ?? null;
    
            return [
                'id' => $item->id,
                'tipe' => 'barang',
                'id_barang' => $item->id_barang,
                'nama' => $product->nama_barang ?? '-',
                'kategori' => $product->kategori ?? null,
                'image' => $image,
                'image_url' => $image ? asset('storage/' . $image) : null,
                'jumlah' => (int) $item->jumlah,
                'harga_satuan' => (float) $item->harga_satuan,
                'subtotal' => (float) $item->subtotal,
            ];
        });
    
        $layananItems = $transaction->layanan->map(function ($item) {
            $service = $item->layanan;
    
            return [
                'id' => $item->id,
                'tipe' => 'layanan',
                'id_layanan' => $item->id_layanan,
                'nama' => $service->nama_layanan ?? '-',
                'kategori' => $service->kategori ?? null,
                'jumlah' => (int) $item->jumlah,
                'harga_satuan' => (float) $item->harga_satuan,
                'subtotal' => (float) $item->subtotal,
            ];
        });
    
        $items = $barangItems->concat($layananItems)->values();
    
        return [
            'id' => $transaction->id,
            'kode_transaksi' => $transaction->kode_transaksi,
            'jenis' => $transaction->jenis,
    
            'id_pelanggan' => $transaction->id_pelanggan,
            'nama_pelanggan' => $transaction->pelanggan->nama ?? '-',
    
            'id_kasir' => $transaction->id_kasir,
            'nama_kasir' => $transaction->kasir->nama ?? null,
    
            'subtotal' => (float) $transaction->subtotal,
            'diskon' => (float) $transaction->diskon,
            'total' => (float) $transaction->total,
            'jumlah_bayar' => (float) $transaction->jumlah_bayar,
            'kembalian' => (float) $transaction->kembalian,
    
            'metode_bayar' => $transaction->metode_bayar,
            'status' => $transaction->status,
            'catatan' => $transaction->catatan,
            'tanggal' => optional($transaction->tanggal)->format('Y-m-d H:i:s'),
    
            'items' => $items,
            'barang' => $barangItems,
            'layanan' => $layananItems,
        ];
    }
}