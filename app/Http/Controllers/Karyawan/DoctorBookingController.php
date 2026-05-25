<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DoctorBooking;
use App\Models\Pet;
use App\Models\User;
use App\Models\Service;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function index()
    {
        $bookings = DoctorBooking::with([
                'hewan.owner',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->latest()
            ->get();

        return view('karyawan.doctor-bookings.index', compact('bookings'));
    }

    public function show(DoctorBooking $doctorBooking)
    {
        $doctorBooking->load([
            'hewan.owner',
            'dokter',
            'layanan',
            'jadwal',
        ]);
    
        return view('karyawan.doctor-bookings.show', compact('doctorBooking'));
    }

    public function payment(DoctorBooking $doctorBooking)
    {
        if ($doctorBooking->id_transaksi || $doctorBooking->status === 'selesai' || $doctorBooking->status === 'batal') {
            return redirect()->route('karyawan.doctor-bookings.index')
                ->with('error', 'Booking dokter ini sudah dibayar, selesai, atau dibatalkan.');
        }

        $doctorBooking->load(['hewan.owner', 'dokter', 'layanan', 'jadwal']);

        return view('karyawan.doctor-bookings.payment', compact('doctorBooking'));
    }

    public function pay(Request $request, DoctorBooking $doctorBooking)
    {
        if ($doctorBooking->id_transaksi || $doctorBooking->status === 'selesai' || $doctorBooking->status === 'batal') {
            return redirect()->route('karyawan.doctor-bookings.index')
                ->with('error', 'Booking dokter ini sudah dibayar, selesai, atau dibatalkan.');
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
            $customer = $doctorBooking->hewan->owner;
            
            // Get or create Service record if booking has no layout/service associated
            $serviceId = $doctorBooking->id_layanan;
            if (!$serviceId) {
                $service = \App\Models\Service::firstOrCreate(
                    ['nama_layanan' => 'Konsultasi Dokter', 'kategori' => 'dokter'],
                    ['harga' => $doctorBooking->total_biaya, 'is_aktif' => true]
                );
                $serviceId = $service->id;
            } else {
                $service = \App\Models\Service::find($serviceId);
            }

            // Create Transaction
            $transaction = \App\Models\Transaction::create([
                'id_pelanggan' => $customer->id ?? null,
                'id_kasir' => auth()->id(),
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . str_pad(\App\Models\Transaction::whereDate('tanggal', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'jenis' => 'dokter',
                'subtotal' => $request->total_biaya,
                'diskon' => $diskon,
                'total' => $total,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $kembalian,
                'metode_bayar' => $request->metode_bayar,
                'status' => 'lunas',
                'catatan' => $request->catatan ?? ('Pembayaran Booking Dokter: ' . ($service->nama_layanan ?? 'Konsultasi')),
                'tanggal' => now(),
            ]);

            // Create Transaction Service Detail
            \App\Models\TransactionService::create([
                'id_transaksi' => $transaction->id,
                'id_layanan' => $serviceId,
                'jumlah' => 1,
                'harga_satuan' => $request->total_biaya,
                'subtotal' => $request->total_biaya,
            ]);

            // Update Doctor Booking
            $doctorBooking->update([
                'status' => 'selesai',
                'id_transaksi' => $transaction->id,
                'total_biaya' => $request->total_biaya,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('karyawan.transactions.show', $transaction->id)
                ->with('success', 'Pembayaran Booking Dokter berhasil diproses!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, DoctorBooking $doctorBooking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,dikonfirmasi,selesai,batal'],
        ]);

        $doctorBooking->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('karyawan.doctor-bookings.index')
            ->with('success', 'Status booking dokter berhasil diperbarui.');
    }
}
