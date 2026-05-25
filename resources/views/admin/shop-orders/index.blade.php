@extends('layouts.admin')

@section('title', 'Pesanan Shopping')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Pesanan Shopping</h1>
            <p class="text-muted mb-0">Kelola pesanan produk dari customer mobile.</p>
        </div>
    </div>



    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.shop-orders.index') }}" class="row g-2">
                <div class="col-md-5">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        class="form-control" 
                        placeholder="Cari kode transaksi / nama / email customer"
                    >
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('admin.shop-orders.index') }}" class="btn btn-light">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Pesanan</h5>
        </div>

        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Customer</th>
                                <th>Tanggal</th>
                                <th>Item</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->kode_transaksi }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $order->pelanggan->nama ?? '-' }}</div>
                                        <small class="text-muted">{{ $order->pelanggan->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ optional($order->tanggal)->format('d M Y H:i') }}</td>
                                    <td>{{ $order->barang->sum('jumlah') }} item</td>
                                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($order->status) {
                                                'pending' => 'warning',
                                                'lunas' => 'success',
                                                'batal' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.shop-orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">Belum ada pesanan shopping</h5>
                    <p class="text-muted mb-0">Pesanan dari mobile akan muncul di sini setelah customer checkout.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection