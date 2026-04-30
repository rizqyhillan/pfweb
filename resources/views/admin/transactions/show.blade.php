@extends('layouts.admin')
@section('title', 'Detail Transaksi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Detail Transaksi: {{ $transaction->kode_transaksi }}</h4>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <p><strong>Pelanggan:</strong> {{ $transaction->pelanggan->nama ?? 'Umum' }}</p>
        <p><strong>Kasir:</strong> {{ $transaction->kasir->nama ?? '-' }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->tanggal }}</p>
        <p><strong>Status:</strong> {{ $transaction->status }}</p>
        
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->barang as $tb)
                <tr>
                    <td>{{ $tb->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                    <td>Rp {{ number_format($tb->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $tb->jumlah }}</td>
                    <td>Rp {{ number_format($tb->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @foreach($transaction->layanan as $tl)
                <tr>
                    <td>{{ $tl->layanan->nama_layanan ?? 'Layanan Terhapus' }}</td>
                    <td>Rp {{ number_format($tl->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $tl->jumlah }}</td>
                    <td>Rp {{ number_format($tl->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Subtotal:</th>
                    <th>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Diskon:</th>
                    <th>Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Jumlah Bayar:</th>
                    <th>Rp {{ number_format($transaction->jumlah_bayar, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Kembalian:</th>
                    <th>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
