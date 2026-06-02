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
                'photos',
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
            'id_dokter' => 'nullable|exists:users,id',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'resep' => 'nullable|string',
            'berat_saat_itu' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $record = MedicalRecord::with([
                'hewan.owner',
                'dokter',
                'photos',
            ])
            ->findOrFail(MedicalRecord::create($data)->id);

        return response()->json([
            'message' => 'Rekam medis berhasil ditambahkan',
            'data' => $this->formatMedicalRecord($record),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $record = MedicalRecord::with([
                'hewan.owner',
                'dokter',
                'photos',
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
        $hewan = $record->hewan;
        $owner = $hewan?->owner;
        $dokter = $record->dokter;

        return [
            'id' => $record->id,
            'id_hewan' => $record->id_hewan,
            'id_dokter' => $record->id_dokter,

            // Format flat untuk mobile versi sekarang/baru.
            'nama_hewan' => $hewan?->nama_hewan ?? '-',
            'jenis_hewan' => $hewan?->jenis ?? '-',
            'ras_hewan' => $hewan?->ras ?? '-',
            'umur_hewan' => $hewan?->umur ? (string) $hewan->umur : '-',
            'berat_hewan' => $hewan?->berat !== null ? (float) $hewan->berat : null,
            'nama_pemilik' => $owner?->nama ?? '-',
            'nama_dokter' => $dokter?->nama ?? '-',
            'spesialisasi_dokter' => $dokter?->spesialisasi ?? 'Dokter Hewan',
            'foto_dokter_url' => $dokter?->foto_url,

            'diagnosa' => $record->diagnosa,
            'tindakan' => $record->tindakan,
            'resep' => $record->resep,
            'catatan' => $record->catatan,
            'berat_saat_itu' => $record->berat_saat_itu !== null ? (float) $record->berat_saat_itu : null,
            'tanggal' => optional($record->tanggal)->format('Y-m-d H:i:s'),
            'created_at' => optional($record->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($record->updated_at)->format('Y-m-d H:i:s'),

            'photos' => $record->photos->map(fn ($photo) => [
                'id' => $photo->id,
                'foto' => $photo->foto,
                'foto_url' => asset('storage/' . $photo->foto),
            ])->values(),

            // Format nested supaya tetap kompatibel dengan model Flutter lama.
            'hewan' => $hewan ? [
                'id' => $hewan->id,
                'nama_hewan' => $hewan->nama_hewan,
                'jenis' => $hewan->jenis,
                'ras' => $hewan->ras,
                'umur' => $hewan->umur,
                'berat' => $hewan->berat !== null ? (float) $hewan->berat : null,
                'owner' => $owner ? [
                    'id' => $owner->id,
                    'nama' => $owner->nama,
                    'email' => $owner->email,
                ] : null,
            ] : null,
            'dokter' => $dokter ? [
                'id' => $dokter->id,
                'nama' => $dokter->nama,
                'email' => $dokter->email,
                'foto' => $dokter->foto,
                'foto_url' => $dokter->foto_url,
                'spesialisasi' => $dokter->spesialisasi ?? 'Dokter Hewan',
            ] : null,
        ];
    }
}
