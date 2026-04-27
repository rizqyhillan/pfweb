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
        $records = MedicalRecord::with(['pet.owner', 'doctor'])->latest('date')->paginate(15);
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
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'doctor_id' => 'nullable|exists:users,id',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'current_weight' => 'nullable|numeric|min:0',
            'date' => 'required|date',
        ]);

        MedicalRecord::create($validated);

        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load(['pet.owner', 'doctor']);
        return view('admin.medical-records.show', compact('medical_record'));
    }

    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->delete();
        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil dihapus.');
    }
}
