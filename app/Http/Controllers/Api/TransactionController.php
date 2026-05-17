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
            'tanggal_grooming' => 'required|date|after_or_equal:today',
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

        $grooming->load(['hewan.owner', 'paket']);

        return response()->json([
            'message' => 'Booking grooming berhasil dibuat. Pembayaran dilakukan di lokasi.',
            'data' => $this->formatGrooming($grooming),
        ], 201);
    }

    public function myGroomingBookings(Request $request)
    {
        $groomings = Grooming::with(['hewan.owner', 'paket'])
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest('tanggal_grooming')
            ->get()
            ->map(fn ($grooming) => $this->formatGrooming($grooming));

        return response()->json([
            'data' => $groomings,
        ]);
    }

    public function showGrooming(Request $request, $id)
    {
        $grooming = Grooming::with(['hewan.owner', 'paket'])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatGrooming($grooming),
        ]);
    }


    public function rescheduleGrooming(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_grooming' => ['required', 'date', 'after_or_equal:today'],
            'waktu_grooming' => ['required', 'date_format:H:i'],
        ]);

        $grooming = Grooming::with(['hewan.owner', 'paket'])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($grooming->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking grooming hanya bisa diubah jadwal jika status masih pending.',
            ], 422);
        }

        $grooming->update([
            'tanggal_grooming' => $validated['tanggal_grooming'],
            'waktu_grooming' => $validated['waktu_grooming'],
        ]);

        return response()->json([
            'message' => 'Jadwal booking grooming berhasil diubah.',
            'data' => $this->formatGrooming($grooming->fresh(['hewan.owner', 'paket'])),
        ]);
    }

    public function cancelGrooming(Request $request, $id)
    {
        $grooming = Grooming::with(['hewan.owner', 'paket'])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($grooming->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking grooming hanya bisa dibatalkan jika status masih pending.',
            ], 422);
        }

        $grooming->update([
            'status' => 'batal',
        ]);

        return response()->json([
            'message' => 'Booking grooming berhasil dibatalkan.',
            'data' => $this->formatGrooming($grooming->fresh(['hewan.owner', 'paket'])),
        ]);
    }

    private function formatGrooming(Grooming $grooming): array
    {
        return [
            'id' => $grooming->id,
            'id_hewan' => $grooming->id_hewan,
            'nama_hewan' => $grooming->hewan->nama_hewan ?? '-',
            'jenis_hewan' => $grooming->hewan->jenis ?? null,
            'ras_hewan' => $grooming->hewan->ras ?? null,
            'id_paket' => $grooming->id_paket,
            'nama_paket' => $grooming->paket->label ?? $grooming->paket->name ?? 'Grooming',
            'deskripsi_paket' => $grooming->paket->description ?? null,
            'fasilitas_paket' => $grooming->paket->fasilitas ?? null,
            'tanggal_grooming' => optional($grooming->tanggal_grooming)->format('Y-m-d'),
            'waktu_grooming' => $grooming->waktu_grooming ? Carbon::parse($grooming->waktu_grooming)->format('H:i') : null,
            'status' => $grooming->status,
            'catatan_grooming' => $grooming->catatan_grooming,
            'estimasi_biaya' => (float) $grooming->total_biaya,
            'total_biaya' => (float) $grooming->total_biaya,
            'metode_pembayaran' => 'Bayar di lokasi',
            'payment_note' => 'Pembayaran dilakukan di lokasi setelah layanan selesai.',
            'created_at' => optional($grooming->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($grooming->updated_at)->format('Y-m-d H:i:s'),
        ];
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