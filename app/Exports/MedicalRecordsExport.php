<?php

namespace App\Exports;

use App\Models\MedicalRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MedicalRecordsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MedicalRecord::with(['hewan.owner', 'dokter'])->get()->map(function ($r) {
            return [
                'Hewan'   => $r->hewan->nama_hewan ?? '-',
                'Pemilik' => $r->hewan->owner->nama ?? '-',
                'Dokter'  => $r->dokter->nama ?? '-',
                'Diagnosa'=> $r->diagnosa ?? '-',
                'Tindakan'=> $r->tindakan ?? '-',
                'Resep'   => $r->resep ?? '-',
                'Berat'   => $r->berat_saat_itu ? $r->berat_saat_itu . ' kg' : '-',
                'Tanggal' => $r->tanggal ? $r->tanggal->format('d/m/Y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Hewan',
            'Pemilik',
            'Dokter',
            'Diagnosa',
            'Tindakan',
            'Resep',
            'Berat',
            'Tanggal',
        ];
    }
}