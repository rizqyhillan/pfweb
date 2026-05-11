@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('content')
  <!-- Welcome Row -->
  <div class="row mb-6">
    <div class="col-12">
      <div class="card h-100">
        <div class="d-flex align-items-end row m-0">
          <div class="col-sm-8">
            <div class="card-body">
              <h5 class="card-title text-primary mb-3">Selamat Datang, {{ Auth::user()->nama }}! 🐾</h5>
              <p class="mb-6">
                Dashboard admin klinik hewan Anda.<br />Kelola pasien, layanan, dan transaksi dari sini.
              </p>
              <a href="{{ route('admin.pets.index') }}" class="btn btn-sm btn-outline-primary">Lihat Data Hewan</a>
            </div>
          </div>
          <div class="col-sm-4 text-center text-sm-end">
            <div class="card-body pb-0 px-0 px-md-6">
              <img src="{{ asset('admin-assets/img/illustrations/man-with-laptop.png') }}" height="175"
                alt="View Badge User" style="object-fit: contain;" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Primary Stats Row (Financials & Key Activities) -->
  <div class="row mb-6">
    <!-- Pendapatan Hari Ini -->
    <div class="col-lg-3 col-sm-6 mb-6 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between mb-4">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-money icon-md"></i></span>
            </div>
          </div>
          <p class="mb-1 text-truncate">Pendapatan Hari Ini</p>
          <h4 class="card-title mb-3">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
          <small class="text-warning fw-medium"><i class="icon-base bx bx-trending-up"></i> Pemasukan Kas</small>
        </div>
      </div>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="col-lg-3 col-sm-6 mb-6 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between mb-4">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-wallet icon-md"></i></span>
            </div>
          </div>
          <p class="mb-1 text-truncate">Pendapatan Bulan Ini</p>
          <h4 class="card-title mb-3">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
          <small class="text-primary fw-medium"><i class="icon-base bx bx-calendar"></i> Total Bulan Ini</small>
        </div>
      </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="col-lg-3 col-sm-6 mb-6 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between mb-4">
            <div class="avatar flex-shrink-0">
              <img src="{{ asset('admin-assets/img/icons/unicons/wallet-info.png') }}" alt="wallet info" class="rounded" />
            </div>
          </div>
          <p class="mb-1 text-truncate">Transaksi Hari Ini</p>
          <h4 class="card-title mb-3">{{ number_format($todayTransactions) }}</h4>
          <small class="text-info fw-medium"><i class="icon-base bx bx-receipt"></i> Order Masuk</small>
        </div>
      </div>
    </div>

    <!-- Total Hewan -->
    <div class="col-lg-3 col-sm-6 mb-6 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between mb-4">
            <div class="avatar flex-shrink-0">
              <img src="{{ asset('admin-assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
            </div>
          </div>
          <p class="mb-1 text-truncate">Total Hewan (Pasien)</p>
          <h4 class="card-title mb-3">{{ number_format($totalPets) }}</h4>
          <small class="text-success fw-medium"><i class="icon-base bx bx-check-circle"></i> Terdaftar</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Secondary Stats Row (Entity Information) -->
  <div class="row">
    <div class="col-12 mb-6">
      <div class="row">
        <!-- Total Users -->
        <div class="col-sm-6 col-lg-3 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-user icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1 text-truncate">Total Pengguna</p>
              <h4 class="card-title mb-3">{{ number_format($totalUsers) }}</h4>
              <small class="text-info fw-medium"><i class="icon-base bx bx-group"></i> Sistem</small>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-hotel icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1 text-truncate">Boarding Aktif</p>
              <h4 class="card-title mb-3">{{ number_format($activeBoarding) }}</h4>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-package icon-md"></i></span>
                </div>
              </div>
              <p class="mb-1 text-truncate">Total Produk</p>
              <h4 class="card-title mb-3">{{ number_format($totalProducts) }}</h4>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-first-aid icon-md"></i></span>
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
    <!-- Recent Transactions -->
    <div class="col-lg-8 mb-6">
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
    <div class="col-lg-4 mb-6">
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

  <div class="row">
    <!-- Low Stock Products -->
    <div class="col-lg-6 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2 text-warning"><i class="bx bx-error-circle me-2"></i>Peringatan Stok Rendah</h5>
          <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning">Kelola Produk</a>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($lowStockProducts as $product)
              <li class="d-flex align-items-center justify-content-between mb-4 pb-1">
                <div class="d-flex align-items-center">
                  <div class="avatar flex-shrink-0 me-3">
                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-package"></i></span>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="fw-medium">{{ $product->nama_barang }}</span>
                    <small class="text-muted">{{ $product->kategori->nama_kategori ?? 'Umum' }}</small>
                  </div>
                </div>
                <div class="text-end">
                  <span class="badge bg-label-danger">{{ $product->stok }} tersisa</span>
                </div>
              </li>
            @empty
              <li class="text-center text-muted">Semua stok produk dalam kondisi aman.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <!-- Near Expired Batches -->
    <div class="col-lg-6 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2 text-danger"><i class="bx bx-calendar-exclamation me-2"></i>Hampir Kedaluwarsa</h5>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($nearExpiredBatches as $batch)
              <li class="d-flex align-items-center justify-content-between mb-4 pb-1">
                <div class="d-flex align-items-center">
                  <div class="avatar flex-shrink-0 me-3">
                    <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-calendar-x"></i></span>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="fw-medium">{{ $batch->barang->nama_barang ?? 'Produk Dihapus' }}</span>
                    <small class="text-muted">Batch: {{ $batch->kode_batch }} | Sisa: {{ $batch->sisa_stok }}</small>
                  </div>
                </div>
                <div class="text-end">
                  <small class="text-danger fw-bold d-block">{{ \Carbon\Carbon::parse($batch->tanggal_expired)->diffForHumans() }}</small>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($batch->tanggal_expired)->format('d/m/Y') }}</small>
                </div>
              </li>
            @empty
              <li class="text-center text-muted">Tidak ada produk yang mendekati masa kedaluwarsa.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection