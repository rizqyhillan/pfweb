<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with(['hewan.owner', 'dokter'])
            ->latest('tanggal')
            ->get();

        return response()->json($records);
    }

    public function byPet($id)
    {
        $records = MedicalRecord::with(['dokter'])
            ->where('id_hewan', $id)
            ->latest('tanggal')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_dokter' => 'required|exists:users,id',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $record = MedicalRecord::create($data);

        return response()->json([
            'message' => 'Rekam medis berhasil ditambahkan',
            'data' => $record
        ]);
    }
}