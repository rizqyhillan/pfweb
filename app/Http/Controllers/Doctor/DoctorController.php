<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Dashboard utama dokter.
     */
    public function dashboard()
    {
        $doctor = Auth::user();

        $totalPatients    = Pet::count();
        $myRecords        = MedicalRecord::where('id_dokter', $doctor->id)->count();
        $todaySchedules   = DoctorSchedule::where('id_dokter', $doctor->id)
            ->where('hari', now()->locale('id')->isoFormat('dddd'))
            ->where('is_aktif', true)
            ->get();

        $recentRecords = MedicalRecord::with(['hewan.owner'])
            ->where('id_dokter', $doctor->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('doctor.dashboard.index', compact(
            'doctor', 'totalPatients', 'myRecords', 'recentRecords', 'todaySchedules'
        ));
    }

    /**
     * Daftar semua pasien (hewan) — READ ONLY.
     */
    public function patients()
    {
        $pets = Pet::with('owner')->latest()->paginate(15);
        return view('doctor.patients.index', compact('pets'));
    }

    // ========================================
    // MEDICAL RECORDS — FULL CRUD
    // ========================================

    /**
     * Daftar rekam medis milik dokter ini.
     */
    public function medicalRecords()
    {
        $records = MedicalRecord::with(['hewan.owner'])
            ->where('id_dokter', Auth::id())
            ->latest('tanggal')
            ->paginate(15);

        return view('doctor.medical-records.index', compact('records'));
    }

    /**
     * Form tambah rekam medis.
     */
    public function createMedicalRecord()
    {
        $pets = Pet::with('owner')->get();
        return view('doctor.medical-records.create', compact('pets'));
    }

    /**
     * Simpan rekam medis baru.
     */
    public function storeMedicalRecord(Request $request)
    {
        $validated = $request->validate([
            'id_hewan'      => 'required|exists:hewan,id',
            'diagnosa'      => 'required|string',
            'tindakan'      => 'nullable|string',
            'resep'         => 'nullable|string',
            'berat_saat_itu'=> 'nullable|numeric|min:0',
            'catatan'       => 'nullable|string',
            'tanggal'       => 'required|date',
        ]);

        $validated['id_dokter'] = Auth::id();

        MedicalRecord::create($validated);

        return redirect()->route('doctor.medical-records')
            ->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    /**
     * Form edit rekam medis (hanya milik dokter ini).
     */
    public function editMedicalRecord(MedicalRecord $medical_record)
    {
        // Pastikan hanya dokter pemilik yang bisa edit
        if ($medical_record->id_dokter !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }

        $pets = Pet::with('owner')->get();
        return view('doctor.medical-records.edit', compact('medical_record', 'pets'));
    }

    /**
     * Update rekam medis.
     */
    public function updateMedicalRecord(Request $request, MedicalRecord $medical_record)
    {
        if ($medical_record->id_dokter !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'id_hewan'      => 'required|exists:hewan,id',
            'diagnosa'      => 'required|string',
            'tindakan'      => 'nullable|string',
            'resep'         => 'nullable|string',
            'berat_saat_itu'=> 'nullable|numeric|min:0',
            'catatan'       => 'nullable|string',
            'tanggal'       => 'required|date',
        ]);

        $medical_record->update($validated);

        return redirect()->route('doctor.medical-records')
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Hapus rekam medis (hanya milik dokter ini).
     */
    public function deleteMedicalRecord(MedicalRecord $medical_record)
    {
        if ($medical_record->id_dokter !== Auth::id()) {
            abort(403);
        }

        $medical_record->delete();

        return redirect()->route('doctor.medical-records')
            ->with('success', 'Rekam medis berhasil dihapus.');
    }

    /**
     * Jadwal praktek dokter — READ ONLY.
     */
    public function schedule()
    {
        $schedules = DoctorSchedule::where('id_dokter', Auth::id())
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->get();

        return view('doctor.schedule.index', compact('schedules'));
    }
}
