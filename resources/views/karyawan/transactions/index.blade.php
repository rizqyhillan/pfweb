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
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="icon-base bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('karyawan.transactions.show', $trx) }}">
                            <i class="icon-base bx bx-show me-1"></i> Detail
                          </a>
                        </div>
                      </div>
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

  @section('page-js')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Echo !== 'undefined') {
          console.log('✅ Echo connected. Listening on transactions...');

          window.Echo.channel('transactions')
            .listen('.new-transaction', (e) => {
              let trx = e.transaction;
              let tbody = document.querySelector('tbody.table-border-bottom-0');
              let noDataTr = tbody.querySelector('td[colspan="9"]');
              if (noDataTr) noDataTr.parentElement.remove();

              let totalFormat = new Intl.NumberFormat('id-ID').format(trx.total);
              let dateStr = trx.tanggal ? new Date(trx.tanggal).toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-';
              let statusBadge = trx.status === 'lunas' ? '<span class="badge bg-label-success">Lunas</span>' : '<span class="badge bg-label-warning">' + trx.status + '</span>';

              let html = `
                      <tr style="animation: slideIn .3s ease;">
                          <td><span class="badge bg-success">Baru</span></td>
                          <td><strong>${trx.kode_transaksi}</strong></td>
                          <td>${trx.pelanggan ? trx.pelanggan.nama : '-'}</td>
                          <td>${trx.kasir ? trx.kasir.nama : '-'}</td>
                          <td>Rp ${totalFormat}</td>
                          <td>${trx.metode_bayar ? trx.metode_bayar.charAt(0).toUpperCase() + trx.metode_bayar.slice(1) : '-'}</td>
                          <td>${statusBadge}</td>
                          <td>${dateStr}</td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="/karyawan/transactions/${trx.id}">
                                  <i class="icon-base bx bx-show me-1"></i> Detail
                                </a>
                              </div>
                            </div>
                          </td>
                      </tr>`;

              tbody.insertAdjacentHTML('afterbegin', html);
            });
        }
      });
    </script>
  @endsection
@endsection