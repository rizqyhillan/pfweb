<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with(['hewan.owner', 'dokter'])->latest('tanggal')->paginate(15);
        return view('admin.medical-records.index', compact('records'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $doctors = User::where('role', 'doctor')->get();
        return view('admin.medical-records.create', compact('pets', 'doctors'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_dokter' => 'required|exists:users,id',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);
        MedicalRecord::create($v);
        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load(['hewan.owner', 'dokter']);
        return view('admin.medical-records.show', compact('medical_record'));
    }

    public function edit(MedicalRecord $medical_record)
    {
        $pets = Pet::with('owner')->get();
        $doctors = User::where('role', 'doctor')->get();
        return view('admin.medical-records.edit', compact('medical_record', 'pets', 'doctors'));
    }

    public function update(Request $request, MedicalRecord $medical_record)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_dokter' => 'required|exists:users,id',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);
        $medical_record->update($v);
        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->delete();
        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil dihapus.');
    }
}
