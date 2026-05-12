<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MedicalRecordsExport;
use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with(['hewan.owner', 'dokter'])->latest('tanggal')->pathPaginate(15, url('admin/medical-records/page'));

        return view('admin.medical-records.index', compact('records'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $doctors = User::where('role', 'dokter')->get();

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
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $mr = MedicalRecord::create($v);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('medical-records', 'public');
                $mr->photos()->create(['foto' => $path]);
            }
        }

        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load(['hewan.owner', 'dokter', 'photos']);

        return view('admin.medical-records.show', compact('medical_record'));
    }

    public function edit(MedicalRecord $medical_record)
    {
        $pets = Pet::with('owner')->get();
        $doctors = User::where('role', 'dokter')->get();
        $medical_record->load('photos');

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
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'delete_fotos' => 'nullable|array',
            'delete_fotos.*' => 'exists:medical_record_photos,id',
        ]);
        $medical_record->update($v);

        if ($request->has('delete_fotos')) {
            $photosToDelete = $medical_record->photos()->whereIn('id', $request->delete_fotos)->get();
            foreach ($photosToDelete as $photo) {
                if (Storage::disk('public')->exists($photo->foto)) {
                    Storage::disk('public')->delete($photo->foto);
                }
                $photo->delete();
            }
        }

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('medical-records', 'public');
                $medical_record->photos()->create(['foto' => $path]);
            }
        }

        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->load('photos');
        foreach ($medical_record->photos as $photo) {
            if (Storage::disk('public')->exists($photo->foto)) {
                Storage::disk('public')->delete($photo->foto);
            }
        }
        $medical_record->delete();

        return redirect()->route('admin.medical-records.index')->with('success', 'Rekam medis berhasil dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new MedicalRecordsExport, 'rekam-medis.xlsx');
    }

    public function exportPdf($hewanId)
    {
        $records = MedicalRecord::with(['hewan.owner', 'dokter'])
            ->where('id_hewan', $hewanId)
            ->get();

        if ($records->isEmpty()) {
            abort(404, 'Data tidak ditemukan');
        }

        $hewan = $records->first()->hewan;

        $pdf = Pdf::loadView('admin.medical-records.pdf', [
            'records' => $records,
            'hewan' => $hewan,
        ]);

        return $pdf->download('rekam-medis-'.$hewan->nama_hewan.'.pdf');
    }
}
