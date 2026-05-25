@extends('layouts.admin')

@section('title', 'Detail Pesanan Shopping')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Pesanan Shopping</h1>
            <p class="text-muted mb-0">{{ $shopOrder->kode_transaksi }}</p>
        </div>

        <a href="{{ route('admin.shop-orders.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>



    @php
        $badgeClass = match($shopOrder->status) {
            'pending' => 'warning',
            'lunas' => 'success',
            'batal' => 'danger',
            default => 'secondary',
        };
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($shopOrder->barang as $item)
                                    <tr>
                                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                        <td>{{ $item->barang->kategori ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal</th>
                                    <th>Rp {{ number_format($shopOrder->subtotal, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Diskon</th>
                                    <th>Rp {{ number_format($shopOrder->diskon, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th>Rp {{ number_format($shopOrder->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Status Pesanan</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.shop-orders.update-status', $shopOrder) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pesanan</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $shopOrder->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="lunas" {{ $shopOrder->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="batal" {{ $shopOrder->status === 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <small class="text-muted">
                                Jika status pending diubah ke batal, stok barang akan dikembalikan otomatis.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control">{{ old('catatan', $shopOrder->catatan) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>

                <div class="card-body">
                    <p class="mb-2"><strong>Kode:</strong><br>{{ $shopOrder->kode_transaksi }}</p>
                    <p class="mb-2"><strong>Status:</strong><br>
                        <span class="badge bg-{{ $badgeClass }}">
                            {{ ucfirst($shopOrder->status) }}
                        </span>
                    </p>
                    <p class="mb-2"><strong>Metode Bayar:</strong><br>{{ $shopOrder->metode_bayar ?? '-' }}</p>
                    @if(in_array(strtolower($shopOrder->metode_bayar), ['transfer', 'ewallet']))
                        <p class="mb-2"><strong>Midtrans ID:</strong><br>{{ $shopOrder->payment_reference ?? '-' }}</p>
                        <p class="mb-2"><strong>Midtrans Token:</strong><br>{{ $shopOrder->payment_token ?? '-' }}</p>
                        <p class="mb-2"><strong>Status Midtrans:</strong><br>{{ ucfirst($shopOrder->payment_status ?? '-') }}</p>
                        @if($shopOrder->paid_at)
                            <p class="mb-2"><strong>Tanggal Bayar:</strong><br>{{ $shopOrder->paid_at ? \Carbon\Carbon::parse($shopOrder->paid_at)->format('d M Y H:i') : '-' }}</p>
                        @endif

                        @if($shopOrder->payment_provider === 'midtrans')
                            <hr>
                            <form action="{{ route('admin.shop-orders.sync-midtrans', $shopOrder) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-arrow-repeat"></i> Sync dari Midtrans
                                </button>
                            </form>
                        @endif
                    @endif
                    <p class="mb-2"><strong>Tanggal:</strong><br>{{ optional($shopOrder->tanggal)->format('d M Y H:i') }}</p>
                    <p class="mb-0"><strong>Total:</strong><br>Rp {{ number_format($shopOrder->total, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer</h5>
                </div>

                <div class="card-body">
                    <p class="mb-2"><strong>Nama:</strong><br>{{ $shopOrder->pelanggan->nama ?? '-' }}</p>
                    <p class="mb-2"><strong>Email:</strong><br>{{ $shopOrder->pelanggan->email ?? '-' }}</p>
                    <p class="mb-0"><strong>No HP:</strong><br>{{ $shopOrder->pelanggan->no_hp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection