@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Data Transaksi</h4>
    <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Transaksi
      Baru</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Kasir</th>
            <th>Tipe</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($transactions as $trx)
            <tr>
              <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
              <td><strong>{{ $trx->kode_transaksi }}</strong></td>
              <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
              <td>{{ $trx->kasir->nama ?? '-' }}</td>
              <td><span class="badge bg-label-info">{{ ucfirst($trx->jenis) }}</span></td>
              <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
              <td>{{ ucfirst($trx->metode_bayar) }}</td>
              <td>
                @if($trx->status === 'lunas')
                  <span class="badge bg-label-success">Lunas</span>
                @elseif($trx->status === 'pending')
                  <span class="badge bg-label-warning">Pending</span>
                @else
                  <span class="badge bg-label-danger">Batal</span>
                @endif
              </td>
              <td>{{ $trx->tanggal ? $trx->tanggal->format('d/m/Y H:i') : '-' }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.transactions.show', $trx) }}"><i
                        class="icon-base bx bx-show me-1"></i> Detail</a>
                    <form action="{{ route('admin.transactions.destroy', $trx) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus transaksi ini?')">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada data transaksi</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($transactions->hasPages())
      <div class="card-footer d-flex justify-content-center">
        {{ $transactions->links() }}
      </div>
    @endif
  </div>
@endsection