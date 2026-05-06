@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('content')
  <div class="row">
    <!-- Welcome Card -->
    <div class="col-xxl-8 mb-6 order-0">
      <div class="card">
        <div class="d-flex align-items-start row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary mb-3">Selamat Datang, {{ Auth::user()->nama }}! 🐾</h5>
              <p class="mb-6">
                Dashboard admin klinik hewan Anda.<br />Kelola pasien, layanan, dan transaksi dari sini.
              </p>
              <a href="{{ route('admin.pets.index') }}" class="btn btn-sm btn-outline-primary">Lihat Data Hewan</a>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
              <img src="{{ asset('admin-assets/img/illustrations/man-with-laptop.png') }}" height="175"
                alt="View Badge User" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img src="{{ asset('admin-assets/img/icons/unicons/chart-success.png') }}" alt="chart success"
                    class="rounded" />
                </div>
              </div>
              <p class="mb-1">Total Hewan</p>
              <h4 class="card-title mb-3">{{ number_format($totalPets) }}</h4>
              <small class="text-success fw-medium"><i class="icon-base bx bx-check-circle"></i> Terdaftar</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img src="{{ asset('admin-assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info"
                    class="rounded" />
                </div>
              </div>
              <p class="mb-1">Transaksi Hari Ini</p>
              <h4 class="card-title mb-3">{{ number_format($todayTransactions) }}</h4>
              <small class="text-info fw-medium"><i class="icon-base bx bx-receipt"></i> Hari ini</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- More Stats -->
    <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
      <div class="row">
        <div class="col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-primary"><i
                      class="icon-base bx bx-money icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1">Pendapatan Bulan Ini</p>
              <h4 class="card-title mb-3">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
            </div>
          </div>
        </div>
        <div class="col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-warning"><i
                      class="icon-base bx bx-hotel icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1">Boarding Aktif</p>
              <h4 class="card-title mb-3">{{ number_format($activeBoarding) }}</h4>
            </div>
          </div>
        </div>
        <div class="col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-success"><i
                      class="icon-base bx bx-package icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1">Total Produk</p>
              <h4 class="card-title mb-3">{{ number_format($totalProducts) }}</h4>
            </div>
          </div>
        </div>
        <div class="col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger"><i
                      class="icon-base bx bx-first-aid icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1">Layanan Aktif</p>
              <h4 class="card-title mb-3">{{ number_format($totalServices) }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Recent Transactions -->
    <div class="col-md-6 col-lg-8 order-2 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Transaksi Terakhir</h5>
          <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body px-0">
          <div class="table-responsive text-nowrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Customer</th>
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
                        <span class="badge bg-label-danger">Batal</span>
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

    <!-- Recent Medical Records -->
    <div class="col-md-6 col-lg-4 order-3 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Rekam Medis Terakhir</h5>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($recentMedicalRecords as $record)
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bxs-dog"></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-0">{{ $record->hewan->nama_hewan ?? '-' }}</h6>
                    <small
                      class="text-body-secondary">{{ Str::limit($record->diagnosa ?? 'Belum ada diagnosis', 30) }}</small>
                  </div>
                  <div class="user-progress">
                    <small class="text-body-secondary">{{ $record->tanggal ? $record->tanggal->format('d/m/Y') : '-' }}</small>
                  </div>
                </div>
              </li>
            @empty
              <li class="text-center text-muted">Belum ada rekam medis</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Info Row -->
  <div class="row">
    <div class="col-12 mb-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
              <span class="avatar-initial rounded bg-label-info p-2"><i class="icon-base bx bx-user icon-md"></i></span>
              <div>
                <h6 class="mb-0">Total Users</h6>
                <small class="text-body-secondary">{{ $totalUsers }} pengguna terdaftar</small>
              </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-info">Kelola Users</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection