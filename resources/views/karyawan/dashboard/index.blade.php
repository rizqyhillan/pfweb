@extends('layouts.admin')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="row">
  <!-- Welcome Card -->
  <div class="col-lg-8 mb-6 order-0">
    <div class="card h-100">
      <div class="d-flex align-items-start row h-100">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Selamat Datang, {{ Auth::user()->nama }}! 👋</h5>
            <p class="mb-6">
              Dashboard portal karyawan Anda.<br />Kelola transaksi, lihat produk, dan pantau layanan dari sini.
            </p>
            <a href="{{ route('karyawan.transactions') }}" class="btn btn-sm btn-outline-primary">Lihat Transaksi</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-6">
            <img src="{{ asset('admin-assets/img/illustrations/man-with-laptop.png') }}" height="175" alt="View Badge User" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="col-lg-4 mb-6 order-1">
    <div class="row h-100">
      <div class="col-6 mb-6 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-package icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1 text-truncate">Produk Aktif</p>
            <h4 class="card-title mb-3">{{ number_format($totalProducts) }}</h4>
          </div>
        </div>
      </div>
      <div class="col-6 mb-6 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-first-aid icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1 text-truncate">Layanan Aktif</p>
            <h4 class="card-title mb-3">{{ number_format($totalServices) }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- More Stats -->
  <div class="col-lg-4 mb-6 order-2">
    <div class="row h-100">
      <div class="col-6 mb-6 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-receipt icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1 text-truncate">Transaksi Hari Ini</p>
            <h4 class="card-title mb-3">{{ number_format($todayTransactions) }}</h4>
          </div>
        </div>
      </div>
      <div class="col-6 mb-6 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-money icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1 text-truncate">Pendapatan Hari Ini</p>
            <h4 class="card-title mb-3">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Transactions -->
  <div class="col-lg-8 mb-6 order-3">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Transaksi Terakhir</h5>
        <a href="{{ route('karyawan.transactions') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body px-0">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($recentTransactions as $trx)
                <tr>
                  <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                  <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                  <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                  <td>
                    @if($trx->status === 'lunas')
                      <span class="badge bg-label-success">Lunas</span>
                    @elseif($trx->status === 'pending')
                      <span class="badge bg-label-warning">Pending</span>
                    @else
                      <span class="badge bg-label-danger">{{ ucfirst($trx->status) }}</span>
                    @endif
                  </td>
                  <td>{{ $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

