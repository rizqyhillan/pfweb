<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TransactionService;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['pelanggan', 'kasir'])->latest('tanggal')->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('is_aktif', true)->where('stok', '>', 0)->get();
        $services = Service::where('is_aktif', true)->get();
        return view('admin.transactions.create', compact('customers', 'products', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:users,id',
            'metode_bayar' => 'required|in:cash,transfer,ewallet',
            'diskon' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:barang,id',
            'product_qtys' => 'nullable|array',
            'product_qtys.*' => 'integer|min:1',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:layanan,id',
            'service_qtys' => 'nullable|array',
            'service_qtys.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $hasP = !empty($request->product_ids);
            $hasS = !empty($request->service_ids);
            $jenis = 'campuran';
            if ($hasP && !$hasS)
                $jenis = 'barang';
            if ($hasS && !$hasP)
                $jenis = 'layanan';

            $diskon = $request->diskon ?? 0;

            $trx = Transaction::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_kasir' => auth()->id(),
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis' => $jenis,
                'subtotal' => 0,
                'diskon' => $diskon,
                'total' => 0,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => 0,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'lunas',
                'catatan' => $request->catatan,
                'tanggal' => now(),
            ]);

            if ($hasP) {
                foreach ($request->product_ids as $i => $pid) {
                    $p = Product::find($pid);
                    $qty = $request->product_qtys[$i] ?? 1;
                    $sub = $p->harga * $qty;
                    $subtotal += $sub;
                    TransactionProduct::create(['id_transaksi' => $trx->id, 'id_barang' => $pid, 'jumlah' => $qty, 'harga_satuan' => $p->harga, 'subtotal' => $sub]);
                    $p->decrement('stok', $qty);
                }
            }
            if ($hasS) {
                foreach ($request->service_ids as $i => $sid) {
                    $s = Service::find($sid);
                    $qty = $request->service_qtys[$i] ?? 1;
                    $sub = $s->harga * $qty;
                    $subtotal += $sub;
                    TransactionService::create(['id_transaksi' => $trx->id, 'id_layanan' => $sid, 'jumlah' => $qty, 'harga_satuan' => $s->harga, 'subtotal' => $sub]);
                }
            }

            $total = $subtotal - $diskon;
            $kembalian = max(0, $request->jumlah_bayar - $total);
            $trx->update(['subtotal' => $subtotal, 'total' => $total, 'kembalian' => $kembalian]);

            DB::commit();
            return redirect()->route('admin.transactions.show', $trx)->with('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['pelanggan', 'kasir', 'barang.barang', 'layanan.layanan']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        foreach ($transaction->barang as $item) {
            Product::where('id', $item->id_barang)->increment('stok', $item->jumlah);
        }
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
