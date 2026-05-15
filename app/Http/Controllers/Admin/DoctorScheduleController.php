<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::with('dokter')
            ->orderBy('id_dokter')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.doctor-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.doctor-schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dokter' => ['required', 'exists:users,id'],
            'hari' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        DoctorSchedule::create($validated);

        return redirect()
            ->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function show(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->load('dokter');

        return view('admin.doctor-schedules.show', compact('doctorSchedule'));
    }

    public function edit(DoctorSchedule $doctorSchedule)
    {
        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.doctor-schedules.edit', compact('doctorSchedule', 'doctors'));
    }

    public function update(Request $request, DoctorSchedule $doctorSchedule)
    {
        $validated = $request->validate([
            'id_dokter' => ['required', 'exists:users,id'],
            'hari' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        $doctorSchedule->update($validated);

        return redirect()
            ->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->delete();

        return redirect()
            ->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}