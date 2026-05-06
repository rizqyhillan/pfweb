<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
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

        $totalPatients = Pet::count();
        $myRecords = MedicalRecord::where('id_dokter', $doctor->id)->count();

        // Map Carbon dayOfWeekIso (1=Mon..7=Sun) to DB enum values
        $hariMap = [1 => 'senin', 2 => 'selasa', 3 => 'rabu', 4 => 'kamis', 5 => 'jumat', 6 => 'sabtu', 7 => 'minggu'];
        $hariIni = $hariMap[now()->dayOfWeekIso] ?? '';

        $todaySchedules = DoctorSchedule::where('id_dokter', $doctor->id)
            ->where('hari', $hariIni)
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
     * Daftar semua pasien (hewan)
     */
    public function patients()
    {
        $pets = Pet::with('owner')->latest()->pathPaginate(15, url('doctor/patients/page'));

        return view('doctor.patients.index', compact('pets'));
    }

    public function createPatient()
    {
        $owners = User::where('role', 'customer')->get();

        return view('doctor.patients.create', compact('owners'));
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'id_pemilik' => 'required|exists:users,id',
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|in:kucing,anjing,burung,reptil,lainnya',
            'ras' => 'nullable|string|max:50',
            'umur' => 'nullable|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);
        Pet::create($validated);

        return redirect()->route('doctor.patients')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function editPatient(Pet $pet)
    {
        $owners = User::where('role', 'customer')->get();

        return view('doctor.patients.edit', compact('pet', 'owners'));
    }

    public function updatePatient(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'id_pemilik' => 'required|exists:users,id',
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|in:kucing,anjing,burung,reptil,lainnya',
            'ras' => 'nullable|string|max:50',
            'umur' => 'nullable|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);
        $pet->update($validated);

        return redirect()->route('doctor.patients')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function deletePatient(Pet $pet)
    {
        $pet->delete();

        return redirect()->route('doctor.patients')->with('success', 'Data pasien berhasil dihapus.');
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
            ->pathPaginate(15, url('doctor/medical-records/page'));

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
            'id_hewan' => 'required|exists:hewan,id',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
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
            'id_hewan' => 'required|exists:hewan,id',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
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
     * Jadwal praktek dokter
     */
    public function schedule()
    {
        $schedules = DoctorSchedule::where('id_dokter', Auth::id())
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get();

        return view('doctor.schedule.index', compact('schedules'));
    }

    public function createSchedule()
    {
        return view('doctor.schedule.create');
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'required|integer|min:1',
        ]);

        $validated['id_dokter'] = Auth::id();
        $validated['is_aktif'] = $request->boolean('is_aktif');

        DoctorSchedule::create($validated);

        return redirect()->route('doctor.schedule')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function editSchedule(DoctorSchedule $schedule)
    {
        if ($schedule->id_dokter !== Auth::id()) {
            abort(403);
        }

        return view('doctor.schedule.edit', compact('schedule'));
    }

    public function updateSchedule(Request $request, DoctorSchedule $schedule)
    {
        if ($schedule->id_dokter !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'required|integer|min:1',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        $schedule->update($validated);

        return redirect()->route('doctor.schedule')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function deleteSchedule(DoctorSchedule $schedule)
    {
        if ($schedule->id_dokter !== Auth::id()) {
            abort(403);
        }
        $schedule->delete();

        return redirect()->route('doctor.schedule')->with('success', 'Jadwal berhasil dihapus.');
    }
}
