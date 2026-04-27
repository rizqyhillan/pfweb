@extends('layouts.admin')
@section('title', 'Detail Mutasi Stok')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Mutasi Stok</h4>
    <a href="{{ route('admin.stock-cards.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 30%">Tanggal</th>
                        <td>: {{ \Carbon\Carbon::parse($stock_card->tanggal)->format('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Barang</th>
                        <td>: <strong>{{ $stock_card->barang->nama_barang ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Batch / Supplier</th>
                        <td>: 
                            {{ $stock_card->batch->no_batch ?? '-' }} 
                            @if($stock_card->batch && $stock_card->batch->supplier)
                                ({{ $stock_card->batch->supplier->nama_supplier }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Mutasi</th>
                        <td>: 
                            @if($stock_card->jenis_mutasi === 'masuk') <span class="badge bg-label-success">Barang Masuk</span>
                            @elseif($stock_card->jenis_mutasi === 'keluar') <span class="badge bg-label-danger">Barang Keluar</span>
                            @elseif($stock_card->jenis_mutasi === 'adjustment') <span class="badge bg-label-warning">Adjustment (Penyesuaian)</span>
                            @elseif($stock_card->jenis_mutasi === 'retur') <span class="badge bg-label-info">Retur Barang</span>
                            @elseif($stock_card->jenis_mutasi === 'expired') <span class="badge bg-label-secondary">Barang Kadaluarsa</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 30%">Jumlah</th>
                        <td>: 
                            @if(in_array($stock_card->jenis_mutasi, ['masuk', 'retur']))
                                <span class="text-success fw-bold">+{{ $stock_card->jumlah }}</span>
                            @else
                                <span class="text-danger fw-bold">-{{ $stock_card->jumlah }}</span>
                            @endif
                            <span class="text-muted ms-2">(Saldo akhir: {{ $stock_card->saldo }})</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Harga Satuan</th>
                        <td>: Rp {{ number_format($stock_card->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Referensi</th>
                        <td>: {{ $stock_card->referensi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>: {{ $stock_card->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
