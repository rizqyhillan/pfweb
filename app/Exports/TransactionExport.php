<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Transaction::with(['pelanggan', 'kasir'])->latest('tanggal');

        if ($this->startDate) {
            $query->whereDate('tanggal', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggal', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Pelanggan',
            'Kasir',
            'Jenis',
            'Subtotal',
            'Diskon',
            'Total',
            'Jumlah Bayar',
            'Kembalian',
            'Metode Bayar',
            'Status',
            'Tanggal',
        ];
    }

    public function map($trx): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $trx->kode_transaksi,
            $trx->pelanggan->nama ?? '-',
            $trx->kasir->nama ?? '-',
            ucfirst($trx->jenis),
            $trx->subtotal,
            $trx->diskon,
            $trx->total,
            $trx->jumlah_bayar,
            $trx->kembalian,
            ucfirst($trx->metode_bayar),
            ucfirst($trx->status),
            $trx->tanggal ? $trx->tanggal->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
