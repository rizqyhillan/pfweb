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
    /**
     * GET /api/transactions
     * Ambil semua transaksi milik pelanggan yang sedang login
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['pelanggan', 'kasir'])
            ->latest('tanggal')
            ->get();

        return response()->json($transactions);
    }

    /**
     * GET /api/transactions/{id}
     * Detail satu transaksi
     */
    public function show($id)
    {
        $transaction = Transaction::with(['pelanggan', 'kasir'])
            ->findOrFail($id);

        return response()->json($transaction);
    }

    /**
     * GET /api/transactions/status/{status}
     * Filter berdasarkan status: lunas | pending | batal
     */
    public function byStatus($status)
    {
        $allowed = ['lunas', 'pending', 'batal'];

        if (!in_array($status, $allowed)) {
            return response()->json([
                'message' => 'Status tidak valid. Gunakan: lunas, pending, atau batal'
            ], 422);
        }

        $transactions = Transaction::with(['pelanggan', 'kasir'])
            ->where('status', $status)
            ->latest('tanggal')
            ->get();

        return response()->json($transactions);
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
}