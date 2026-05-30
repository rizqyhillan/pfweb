<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.doctor-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $pets = Pet::with('owner')
            ->orderBy('nama_hewan')
            ->get();

        $activeDoctorIds = Service::where('kategori', 'dokter')
            ->whereNotNull('id_dokter')
            ->where('is_aktif', true)
            ->pluck('id_dokter');

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->whereIn('id', $activeDoctorIds)
            ->orderBy('nama')
            ->get();

        $services = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan')
            ->get();

        $schedules = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderBy('id_dokter')
            ->orderByRaw("CASE hari WHEN 'senin' THEN 1 WHEN 'selasa' THEN 2 WHEN 'rabu' THEN 3 WHEN 'kamis' THEN 4 WHEN 'jumat' THEN 5 WHEN 'sabtu' THEN 6 WHEN 'minggu' THEN 7 END")
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.doctor-bookings.create', compact(
            'pets',
            'doctors',
            'services',
            'schedules'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => ['required', 'exists:hewan,id'],
            'id_dokter' => ['required', 'exists:users,id'],
            'id_layanan' => ['nullable', 'exists:layanan,id'],
            'id_jadwal' => ['nullable', 'exists:jadwal_dokter,id'],
            'tanggal_booking' => ['required', 'date'],
            'jam_booking' => ['required', 'date_format:H:i'],
            'keluhan' => ['nullable', 'string'],
            'catatan_dokter' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,dikonfirmasi,selesai,batal'],
            'total_biaya' => ['required', 'numeric', 'min:0'],
        ]);

        $scheduleError = $this->validateDoctorSchedule($request);
        if ($scheduleError) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $scheduleError);
        }

        // Check if booking slot is available
        $existingBooking = DoctorBooking::where('id_dokter', $validated['id_dokter'])
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->where('jam_booking', $validated['jam_booking'])
            ->whereNotIn('status', ['batal'])
            ->exists();

        if ($existingBooking) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Jam booking tidak tersedia. Dokter sudah memiliki booking pada tanggal dan jam tersebut.');
        }

        if (!empty($validated['id_layanan'])) {
            $service = Service::where('kategori', 'dokter')
                ->where('is_aktif', true)
                ->findOrFail($validated['id_layanan']);

            if ((float) $validated['total_biaya'] <= 0) {
                $validated['total_biaya'] = $service->harga;
            }
        }

        DoctorBooking::create($validated);

        return redirect()
            ->route('admin.doctor-bookings.index')
            ->with('success', 'Booking dokter berhasil ditambahkan.');
    }

    public function show(DoctorBooking $doctorBooking)
    {
        $doctorBooking->load([
            'hewan.owner',
            'dokter',
            'layanan',
            'jadwal',
        ]);
    
        return view('admin.doctor-bookings.show', compact('doctorBooking'));
    }
    public function edit(DoctorBooking $doctorBooking)
    {
        $doctorBooking->load([
            'hewan.owner',
            'dokter',
            'layanan',
            'jadwal',
        ]);

        $pets = Pet::with('owner')
            ->orderBy('nama_hewan')
            ->get();

        $activeDoctorIds = Service::where('kategori', 'dokter')
            ->whereNotNull('id_dokter')
            ->where('is_aktif', true)
            ->pluck('id_dokter');

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->whereIn('id', $activeDoctorIds)
            ->orderBy('nama')
            ->get();

        $services = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan')
            ->get();

        $schedules = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderBy('id_dokter')
            ->orderByRaw("CASE hari WHEN 'senin' THEN 1 WHEN 'selasa' THEN 2 WHEN 'rabu' THEN 3 WHEN 'kamis' THEN 4 WHEN 'jumat' THEN 5 WHEN 'sabtu' THEN 6 WHEN 'minggu' THEN 7 END")
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.doctor-bookings.edit', compact(
            'doctorBooking',
            'pets',
            'doctors',
            'services',
            'schedules'
        ));
    }

    public function update(Request $request, DoctorBooking $doctorBooking)
    {
        $validated = $request->validate([
            'id_hewan' => ['required', 'exists:hewan,id'],
            'id_dokter' => ['required', 'exists:users,id'],
            'id_layanan' => ['nullable', 'exists:layanan,id'],
            'id_jadwal' => ['nullable', 'exists:jadwal_dokter,id'],
            'tanggal_booking' => ['required', 'date'],
            'jam_booking' => ['required', 'date_format:H:i'],
            'keluhan' => ['nullable', 'string'],
            'catatan_dokter' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,dikonfirmasi,selesai,batal'],
            'total_biaya' => ['required', 'numeric', 'min:0'],
        ]);

        $scheduleError = $this->validateDoctorSchedule($request);
        if ($scheduleError) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $scheduleError);
        }

        // Check if booking slot is available (exclude current booking)
        $existingBooking = DoctorBooking::where('id_dokter', $validated['id_dokter'])
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->where('jam_booking', $validated['jam_booking'])
            ->where('id', '!=', $doctorBooking->id)
            ->whereNotIn('status', ['batal'])
            ->exists();

        if ($existingBooking) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Jam booking tidak tersedia. Dokter sudah memiliki booking pada tanggal dan jam tersebut.');
        }

        if (!empty($validated['id_layanan'])) {
            $service = Service::where('kategori', 'dokter')
                ->where('is_aktif', true)
                ->findOrFail($validated['id_layanan']);

            if ((float) $validated['total_biaya'] <= 0) {
                $validated['total_biaya'] = $service->harga;
            }
        }

        $doctorBooking->update($validated);

        return redirect()
            ->route('admin.doctor-bookings.index')
            ->with('success', 'Booking dokter berhasil diperbarui.');
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
            ->route('admin.doctor-bookings.index')
            ->with('success', 'Status booking dokter berhasil diperbarui.');
    }

    public function payment(DoctorBooking $doctorBooking)
    {
        if ($doctorBooking->id_transaksi || $doctorBooking->status === 'selesai' || $doctorBooking->status === 'batal') {
            return redirect()->route('admin.doctor-bookings.index')
                ->with('error', 'Booking dokter ini sudah dibayar, selesai, atau dibatalkan.');
        }

        $doctorBooking->load(['hewan.owner', 'dokter', 'layanan', 'jadwal']);

        return view('admin.doctor-bookings.payment', compact('doctorBooking'));
    }

    public function pay(Request $request, DoctorBooking $doctorBooking)
    {
        if ($doctorBooking->id_transaksi || $doctorBooking->status === 'selesai' || $doctorBooking->status === 'batal') {
            return redirect()->route('admin.doctor-bookings.index')
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

            return redirect()->route('admin.transactions.show', $transaction->id)
                ->with('success', 'Pembayaran Booking Dokter berhasil diproses!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(DoctorBooking $doctorBooking)
    {
        $doctorBooking->delete();

        return redirect()
            ->route('admin.doctor-bookings.index')
            ->with('success', 'Booking dokter berhasil dihapus.');
    }

    private function validateDoctorSchedule(Request $request)
    {
        if ($request->filled('id_jadwal')) {
            $schedule = DoctorSchedule::find($request->id_jadwal);
            if ($schedule) {
                // Check if schedule belongs to the selected doctor
                if ($schedule->id_dokter != $request->id_dokter) {
                    return 'Jadwal yang dipilih tidak cocok dengan dokter yang dipilih.';
                }

                // Check day of week
                $daysMap = [
                    0 => 'minggu',
                    1 => 'senin',
                    2 => 'selasa',
                    3 => 'rabu',
                    4 => 'kamis',
                    5 => 'jumat',
                    6 => 'sabtu',
                ];
                $dayOfWeekNumber = \Carbon\Carbon::parse($request->tanggal_booking)->dayOfWeek;
                $bookingDay = $daysMap[$dayOfWeekNumber];

                if ($bookingDay !== strtolower($schedule->hari)) {
                    return 'Tanggal booking tidak cocok dengan hari jadwal dokter yang dipilih (' . ucfirst($schedule->hari) . ').';
                }

                // Check time range
                $bookingTime = \Carbon\Carbon::parse($request->jam_booking)->format('H:i');
                $startTime = \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i');
                $endTime = \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i');

                if ($bookingTime < $startTime || $bookingTime > $endTime) {
                    return 'Jam booking harus berada di antara ' . $startTime . ' dan ' . $endTime . ' sesuai jadwal dokter yang dipilih.';
                }
            }
        }
        return null;
    }
}