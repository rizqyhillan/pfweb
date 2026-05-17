<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorBooking;
use App\Models\DoctorSchedule;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function doctors()
    {
        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->select([
                'id',
                'nama',
                'email',
                'no_hp',
                'alamat',
            ])
            ->orderBy('nama')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'nama' => $doctor->nama,
                    'email' => $doctor->email,
                    'no_hp' => $doctor->no_hp,
                    'alamat' => $doctor->alamat,
                    'spesialis' => 'Dokter Hewan',
                    'pengalaman' => 'Berpengalaman',
                    'rating' => 4.9,
                    'tersedia' => true,
                ];
            });

        return response()->json([
            'data' => $doctors,
        ]);
    }

    public function services(Request $request)
    {
        $query = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan');

        if ($request->filled('doctor_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('id_dokter')
                    ->orWhere('id_dokter', $request->doctor_id);
            });
        }

        $services = $query->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'nama_layanan' => $service->nama_layanan,
                'kategori' => $service->kategori,
                'deskripsi' => $service->deskripsi,
                'harga' => (float) $service->harga,
                'estimasi_biaya' => (float) $service->harga,
                'id_dokter' => $service->id_dokter,
                'is_aktif' => (bool) $service->is_aktif,
            ];
        });

        return response()->json([
            'data' => $services,
        ]);
    }

    public function schedules(Request $request)
    {
        $query = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('jam_mulai');

        if ($request->filled('doctor_id')) {
            $query->where('id_dokter', $request->doctor_id);
        }

        $schedules = $query->get()->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'id_dokter' => $schedule->id_dokter,
                'nama_dokter' => $schedule->dokter->nama ?? '-',
                'hari' => $schedule->hari,
                'jam_mulai' => $schedule->jam_mulai,
                'jam_selesai' => $schedule->jam_selesai,
                'is_aktif' => (bool) $schedule->is_aktif,
            ];
        });

        return response()->json([
            'data' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => ['required', 'exists:hewan,id'],
            'id_dokter' => ['required', 'exists:users,id'],
            'id_layanan' => ['required', 'exists:layanan,id'],
            'id_jadwal' => ['nullable', 'exists:jadwal_dokter,id'],
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],
            'jam_booking' => ['required', 'date_format:H:i'],
            'keluhan' => ['nullable', 'string'],
        ]);

        $pet = Pet::where('id', $validated['id_hewan'])
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();

        $doctor = User::where('id', $validated['id_dokter'])
            ->where('role', 'dokter')
            ->where('is_aktif', true)
            ->firstOrFail();

        $service = Service::where('id', $validated['id_layanan'])
            ->where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->where(function ($query) use ($doctor) {
                $query->whereNull('id_dokter')
                    ->orWhere('id_dokter', $doctor->id);
            })
            ->firstOrFail();

        if (!empty($validated['id_jadwal'])) {
            DoctorSchedule::where('id', $validated['id_jadwal'])
                ->where('id_dokter', $doctor->id)
                ->where('is_aktif', true)
                ->firstOrFail();
        }

        $booking = DoctorBooking::create([
            'id_hewan' => $pet->id,
            'id_dokter' => $doctor->id,
            'id_layanan' => $service->id,
            'id_jadwal' => $validated['id_jadwal'] ?? null,
            'tanggal_booking' => $validated['tanggal_booking'],
            'jam_booking' => $validated['jam_booking'],
            'keluhan' => $validated['keluhan'] ?? null,
            'catatan_dokter' => null,
            'status' => 'pending',
            'total_biaya' => $service->harga,
        ]);

        $booking->load([
            'hewan',
            'dokter',
            'layanan',
            'jadwal',
        ]);

        return response()->json([
            'message' => 'Booking dokter berhasil dibuat. Pembayaran dilakukan di lokasi.',
            'data' => $this->formatBooking($booking),
        ], 201);
    }

    public function myBookings(Request $request)
    {
        $bookings = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest()
            ->get()
            ->map(fn ($booking) => $this->formatBooking($booking));

        return response()->json([
            'data' => $bookings,
        ]);
    }

    public function show(Request $request, $id)
    {
        $booking = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatBooking($booking),
        ]);
    }


    public function cancel(Request $request, $id)
    {
        $booking = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($booking->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking dokter hanya bisa dibatalkan jika status masih pending.',
            ], 422);
        }

        $booking->update([
            'status' => 'batal',
        ]);

        return response()->json([
            'message' => 'Booking dokter berhasil dibatalkan.',
            'data' => $this->formatBooking($booking->fresh(['hewan', 'dokter', 'layanan', 'jadwal'])),
        ]);
    }

    private function formatBooking(DoctorBooking $booking): array
    {
        return [
            'id' => $booking->id,
            'id_hewan' => $booking->id_hewan,
            'nama_hewan' => $booking->hewan->nama_hewan ?? '-',
            'jenis_hewan' => $booking->hewan->jenis ?? '-',

            'id_dokter' => $booking->id_dokter,
            'nama_dokter' => $booking->dokter->nama ?? '-',

            'id_layanan' => $booking->id_layanan,
            'nama_layanan' => $booking->layanan->nama_layanan ?? '-',
            'deskripsi_layanan' => $booking->layanan->deskripsi ?? null,

            'id_jadwal' => $booking->id_jadwal,
            'hari_jadwal' => $booking->jadwal->hari ?? null,
            'jam_mulai_jadwal' => $booking->jadwal->jam_mulai ?? null,
            'jam_selesai_jadwal' => $booking->jadwal->jam_selesai ?? null,

            'tanggal_booking' => optional($booking->tanggal_booking)->format('Y-m-d'),
            'jam_booking' => $booking->jam_booking ? Carbon::parse($booking->jam_booking)->format('H:i') : null,
            'keluhan' => $booking->keluhan,
            'catatan_dokter' => $booking->catatan_dokter,
            'status' => $booking->status,

            'estimasi_biaya' => (float) $booking->total_biaya,
            'total_biaya' => (float) $booking->total_biaya,
            'metode_pembayaran' => 'Bayar di lokasi',
            'payment_note' => 'Pembayaran dilakukan di lokasi setelah layanan selesai.',
        ];
    }
}