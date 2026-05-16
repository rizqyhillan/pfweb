<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::with([
                'hewan.owner',
                'dokter',
                'photos',
            ])
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest('tanggal')
            ->get()
            ->map(fn ($record) => $this->formatMedicalRecord($record));

        return response()->json([
            'data' => $records,
        ]);
    }

    public function byPet(Request $request, $id)
    {
        $records = MedicalRecord::with([
                'hewan.owner',
                'dokter',
            ])
            ->where('id_hewan', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest('tanggal')
            ->get()
            ->map(fn ($record) => $this->formatMedicalRecord($record));

        return response()->json([
            'data' => $records,
        ]);
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

    public function show(Request $request, $id)
    {
        $record = MedicalRecord::with([
                'hewan.owner',
                'dokter',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatMedicalRecord($record),
        ]);
    }

    private function formatMedicalRecord(MedicalRecord $record): array
    {
        return [
            'id' => $record->id,

            'id_hewan' => $record->id_hewan,
            'nama_hewan' => $record->hewan->nama_hewan ?? '-',
            'jenis_hewan' => $record->hewan->jenis ?? null,
            'ras_hewan' => $record->hewan->ras ?? null,

            'id_dokter' => $record->id_dokter,
            'nama_dokter' => $record->dokter->nama ?? '-',

            'diagnosa' => $record->diagnosa,
            'tindakan' => $record->tindakan,
            'resep' => $record->resep,
            'berat_saat_itu' => $record->berat_saat_itu ? (float) $record->berat_saat_itu : null,
            'tanggal' => optional($record->tanggal)->format('Y-m-d H:i:s'),

            'created_at' => optional($record->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($record->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}