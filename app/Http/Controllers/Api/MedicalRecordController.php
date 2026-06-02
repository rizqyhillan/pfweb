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
        if (! in_array($request->user()->role, ['admin', 'dokter'])) {
            return response()->json([
                'message' => 'Hanya admin atau dokter yang dapat menambahkan rekam medis.',
            ], 403);
        }

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
        $record->load(['hewan.owner', 'dokter', 'photos']);

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
            'nama_hewan' => $hewan->nama_hewan ?? '-',
            'jenis_hewan' => $hewan->jenis ?? null,
            'ras_hewan' => $hewan->ras ?? null,
            'umur_hewan' => $hewan->umur ?? null,
            'berat_hewan' => $hewan->berat ? (float) $hewan->berat : null,
            'nama_pemilik' => $owner->nama ?? '-',

            'id_dokter' => $record->id_dokter,
            'nama_dokter' => $dokter->nama ?? '-',
            'spesialisasi_dokter' => $dokter->spesialisasi ?? 'Dokter Hewan',
            'foto_dokter' => $dokter->foto ?? $dokter->avatar ?? null,

            'diagnosa' => $record->diagnosa,
            'tindakan' => $record->tindakan,
            'resep' => $record->resep,
            'catatan' => $record->catatan,
            'berat_saat_itu' => $record->berat_saat_itu ? (float) $record->berat_saat_itu : null,
            'tanggal' => optional($record->tanggal)->format('Y-m-d H:i:s'),

            'photos' => $record->relationLoaded('photos')
                ? $record->photos->map(fn ($photo) => [
                    'id' => $photo->id,
                    'foto' => $photo->foto,
                    'url' => $photo->foto ? asset('storage/' . $photo->foto) : null,
                ])->values()
                : [],

            // Nested format tetap dikirim agar mobile lama/halaman lain yang baca relasi tidak rusak.
            'hewan' => $hewan ? [
                'id' => $hewan->id,
                'nama_hewan' => $hewan->nama_hewan,
                'jenis' => $hewan->jenis,
                'ras' => $hewan->ras,
                'umur' => $hewan->umur,
                'berat' => $hewan->berat,
                'owner' => $owner ? [
                    'id' => $owner->id,
                    'nama' => $owner->nama,
                    'email' => $owner->email,
                ] : null,
            ] : null,
            'dokter' => $dokter ? [
                'id' => $dokter->id,
                'nama' => $dokter->nama,
                'spesialisasi' => $dokter->spesialisasi ?? 'Dokter Hewan',
                'foto' => $dokter->foto ?? null,
                'avatar' => $dokter->avatar ?? null,
            ] : null,

            'created_at' => optional($record->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($record->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
