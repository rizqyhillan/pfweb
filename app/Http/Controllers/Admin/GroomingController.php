<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grooming;
use App\Models\PackageType;
use App\Models\Pet;
use Illuminate\Http\Request;

class GroomingController extends Controller
{
    public function index(Request $request)
    {
        $query = Grooming::with(['hewan.owner', 'paket']);

        if ($request->filled('paket')) {
            $query->where('id_paket', $request->paket);
        }

        $groomings = $query->latest()->pathPaginate(15, url('admin/groomings/page'));
        $packageOptions = PackageType::orderBy('label')->pluck('label', 'id')->toArray();
        $selectedPaket = $request->paket ?? '';

        return view('admin.groomings.index', compact('groomings', 'packageOptions', 'selectedPaket'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $packages = PackageType::where('name', '!=', 'basic')->orWhere('name', '!=', 'regular')->orWhere('name', '!=', 'premium')->get();
        // For grooming, use all package types
        $packages = PackageType::all();

        return view('admin.groomings.create', compact('pets', 'packages'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_paket' => 'required|exists:package_types,id',
            'tanggal_grooming' => 'required|date',
            'waktu_grooming' => 'required|date_format:H:i',
            'catatan_grooming' => 'nullable|string',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        $paket = PackageType::find($v['id_paket']);
        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $v['total_biaya'] = $paket->harga_per_malam ?? 0;
        }
        $v['status'] = 'pending';
        $grooming = Grooming::create($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil dibuat. Biaya: Rp '.number_format($v['total_biaya'], 0, ',', '.'));
    }

    public function edit(Grooming $grooming)
    {
        $pets = Pet::with('owner')->get();
        $packages = PackageType::all();

        return view('admin.groomings.edit', compact('grooming', 'pets', 'packages'));
    }

    public function update(Request $request, Grooming $grooming)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_paket' => 'required|exists:package_types,id',
            'tanggal_grooming' => 'required|date',
            'waktu_grooming' => 'required|date_format:H:i',
            'catatan_grooming' => 'nullable|string',
            'status' => 'required|in:pending,aktif,selesai,batal',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $paket = PackageType::find($v['id_paket']);
            $v['total_biaya'] = $paket->harga_per_malam ?? 0;
        }
        $grooming->update($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil diperbarui.');
    }

    public function payment(Grooming $grooming)
    {
        if ($grooming->id_transaksi || $grooming->status === 'selesai' || $grooming->status === 'batal') {
            return redirect()->route('admin.groomings.index')
                ->with('error', 'Grooming ini sudah dibayar, selesai, atau dibatalkan.');
        }

        $grooming->load(['hewan.owner', 'paket']);

        return view('admin.groomings.payment', compact('grooming'));
    }

    public function pay(Request $request, Grooming $grooming)
    {
        if ($grooming->id_transaksi || $grooming->status === 'selesai' || $grooming->status === 'batal') {
            return redirect()->route('admin.groomings.index')
                ->with('error', 'Grooming ini sudah dibayar, selesai, atau dibatalkan.');
        }

        $request->validate([
            'metode_bayar' => 'required|in:cash,transfer,ewallet',
            'diskon' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        $diskon = $request->diskon ?? 0;
        $total = $request->total_biaya - $diskon;
        $kembalian = max(0, $request->jumlah_bayar - $total);

        if ($request->jumlah_bayar < $total) {
            return back()->withInput()->with('error', 'Jumlah bayar kurang dari total akhir!');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $customer = $grooming->hewan->owner;
            
            // Get or create Service record for this grooming package
            $service = \App\Models\Service::firstOrCreate(
                ['nama_layanan' => 'Grooming ' . $grooming->paket->label, 'kategori' => 'grooming'],
                ['harga' => $grooming->total_biaya, 'is_aktif' => true]
            );

            // Create Transaction
            $transaction = \App\Models\Transaction::create([
                'id_pelanggan' => $customer->id ?? null,
                'id_kasir' => auth()->id(),
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . str_pad(\App\Models\Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis' => 'grooming',
                'subtotal' => $request->total_biaya,
                'diskon' => $diskon,
                'total' => $total,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $kembalian,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'lunas',
                'catatan' => $request->catatan ?? ('Pembayaran Grooming: ' . $grooming->paket->label),
                'tanggal' => now(),
            ]);

            // Create Transaction Service Detail
            \App\Models\TransactionService::create([
                'id_transaksi' => $transaction->id,
                'id_layanan' => $service->id,
                'jumlah' => 1,
                'harga_satuan' => $request->total_biaya,
                'subtotal' => $request->total_biaya,
            ]);

            // Update Grooming Booking
            $grooming->update([
                'status' => 'selesai',
                'id_transaksi' => $transaction->id,
                'total_biaya' => $request->total_biaya,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.transactions.show', $transaction->id)
                ->with('success', 'Pembayaran Grooming berhasil diproses!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }


    public function updateStatus(Request $request, Grooming $grooming)
    {
        $v = $request->validate([
            'status' => 'required|in:pending,aktif,selesai,batal',
        ]);

        $grooming->update($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Status grooming berhasil diperbarui.');
    }

    public function destroy(Grooming $grooming)
    {
        $grooming->delete();

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil dihapus.');
    }
}
