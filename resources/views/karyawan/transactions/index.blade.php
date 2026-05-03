@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Transaksi</h5>
        <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-primary">
          <i class="bx bx-store me-1"></i> Buka POS Kasir
        </a>
      </div>
      <div class="card-body">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($transactions as $trx)
                <tr>
                  <td>{{ $loop->iteration + $transactions->firstItem() - 1 }}</td>
                  <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                  <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                  <td>{{ $trx->kasir->nama ?? '-' }}</td>
                  <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                  <td>{{ ucfirst($trx->metode_bayar ?? '-') }}</td>
                  <td>
                    @if($trx->status === 'lunas')
                      <span class="badge bg-label-success">Lunas</span>
                    @elseif($trx->status === 'pending')
                      <span class="badge bg-label-warning">Pending</span>
                    @else
                      <span class="badge bg-label-danger">{{ ucfirst($trx->status ?? 'Batal') }}</span>
                    @endif
                  </td>
                  <td>{{ $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '-' }}</td>
                  <td>
                    <a href="{{ route('karyawan.transactions.show', $trx) }}" class="btn btn-sm btn-icon btn-info" title="Detail">
                      <i class="bx bx-show"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted">Belum ada data transaksi.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($transactions->hasPages())
          <div class="mt-4">
            {{ $transactions->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
