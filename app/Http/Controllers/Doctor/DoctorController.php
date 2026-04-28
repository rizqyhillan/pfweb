<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Dashboard utama dokter.
     */
    public function dashboard()
    {
        $doctor = Auth::user();

        // Statistik ringkas
        $totalPatients   = Pet::count();
        $myRecords       = MedicalRecord::where('id_dokter', $doctor->id)->count();
        $mySchedulesToday = DoctorSchedule::where('id_dokter', $doctor->id)
            ->where('hari', now()->locale('id')->isoFormat('dddd'))
            ->where('is_aktif', true)
            ->count();

        // Rekam medis terbaru yang ditangani dokter ini
        $recentRecords = MedicalRecord::with(['hewan.owner'])
            ->where('id_dokter', $doctor->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Jadwal hari ini
        $todaySchedules = DoctorSchedule::where('id_dokter', $doctor->id)
            ->where('is_aktif', true)
            ->orderBy('jam_mulai')
            ->get();

        return view('doctor.dashboard', compact(
            'doctor',
            'totalPatients',
            'myRecords',
            'mySchedulesToday',
            'recentRecords',
            'todaySchedules'
        ));
    }

    /**
     * Daftar semua pasien (hewan).
     */
    public function patients()
    {
        $pets = Pet::with('owner')
            ->latest()
            ->paginate(15);

        return view('doctor.patients', compact('pets'));
    }

    /**
     * Daftar rekam medis yang ditangani dokter ini.
     */
    public function medicalRecords()
    {
        $doctor  = Auth::user();
        $records = MedicalRecord::with(['hewan.owner'])
            ->where('id_dokter', $doctor->id)
            ->latest('tanggal')
            ->paginate(15);

        return view('doctor.medical-records', compact('records'));
    }

    /**
     * Jadwal praktek dokter.
     */
    public function schedule()
    {
        $doctor    = Auth::user();
        $schedules = DoctorSchedule::where('id_dokter', $doctor->id)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->get();

        return view('doctor.schedule', compact('schedules'));
    }
}
