<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
}