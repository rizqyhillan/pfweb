@extends('layouts.admin')
@section('title', 'Detail Transaksi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Detail Transaksi: {{ $transaction->kode_transaksi }}</h4>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Pelanggan:</strong> {{ $transaction->pelanggan->nama ?? 'Umum' }}</p>
                <p><strong>Kasir:</strong> {{ $transaction->kasir->nama ?? '-' }}</p>
                <p><strong>Tanggal:</strong> {{ $transaction->tanggal ? $transaction->tanggal->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <div class="col-md-6">
                <p>
                    <strong>Status:</strong>
                    @if($transaction->status === 'lunas')
                        <span class="badge bg-label-success">Lunas</span>
                    @elseif($transaction->status === 'pending')
                        <span class="badge bg-label-warning">Pending</span>
                    @else
                        <span class="badge bg-label-danger">{{ ucfirst($transaction->status ?? 'Batal') }}</span>
                    @endif
                </p>
                <p><strong>Tipe:</strong> <span class="badge bg-label-info">{{ ucfirst($transaction->jenis) }}</span></p>
                <p><strong>Metode Bayar:</strong> {{ ucfirst($transaction->metode_bayar ?? '-') }}</p>
                @if(in_array(strtolower($transaction->metode_bayar), ['transfer', 'ewallet']))
                    <p><strong>Midtrans ID:</strong> {{ $transaction->payment_reference ?? '-' }}</p>
                    <p><strong>Midtrans Token:</strong> {{ $transaction->payment_token ?? '-' }}</p>
                    <p><strong>Status Midtrans:</strong> <span class="badge bg-label-secondary">{{ ucfirst($transaction->payment_status ?? '-') }}</span></p>
                    @if($transaction->paid_at)
                        <p><strong>Tanggal Bayar Midtrans:</strong> {{ $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d/m/Y H:i') : '-' }}</p>
                    @endif
                @endif
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Tipe</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->barang as $tb)
                <tr>
                    <td>{{ $tb->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                    <td><span class="badge bg-label-info">Barang</span></td>
                    <td>Rp {{ number_format($tb->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $tb->jumlah }}</td>
                    <td>Rp {{ number_format($tb->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @foreach($transaction->layanan as $tl)
                <tr>
                    <td>{{ $tl->layanan->nama_layanan ?? 'Layanan Terhapus' }}</td>
                    <td><span class="badge bg-label-warning">Layanan</span></td>
                    <td>Rp {{ number_format($tl->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $tl->jumlah }}</td>
                    <td>Rp {{ number_format($tl->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Subtotal:</th>
                    <th>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Diskon:</th>
                    <th>Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Total:</th>
                    <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Jumlah Bayar:</th>
                    <th>Rp {{ number_format($transaction->jumlah_bayar, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Kembalian:</th>
                    <th>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection