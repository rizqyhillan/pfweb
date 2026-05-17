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

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        $services = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan')
            ->get();

        $schedules = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderBy('id_dokter')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
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

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        $services = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan')
            ->get();

        $schedules = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderBy('id_dokter')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
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

    public function destroy(DoctorBooking $doctorBooking)
    {
        $doctorBooking->delete();

        return redirect()
            ->route('admin.doctor-bookings.index')
            ->with('success', 'Booking dokter berhasil dihapus.');
    }
}