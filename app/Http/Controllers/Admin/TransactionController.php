<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'cashier'])->latest('date')->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'type' => 'required|in:service,product,mixed',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,ewallet',
            'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['cashier_id'] = auth()->id();
        $validated['transaction_code'] = 'TRX-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('date', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['date'] = now();

        Transaction::create($validated);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'cashier']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
