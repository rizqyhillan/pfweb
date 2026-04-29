@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="row">
  <!-- Summary Cards -->
  <div class="col-lg-3 col-md-6 col-6 mb-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-money icon-md"></i></span>
          </div>
        </div>
        <p class="mb-1">Total Pendapatan</p>
        <h4 class="card-title mb-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
        <small class="text-success fw-medium">Semua transaksi paid</small>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6 mb-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-calendar icon-md"></i></span>
          </div>
        </div>
        <p class="mb-1">Pendapatan Bulan Ini</p>
        <h4 class="card-title mb-3">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
        <small class="text-body-secondary">{{ now()->locale('id')->isoFormat('MMMM Y') }}</small>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6 mb-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-receipt icon-md"></i></span>
          </div>
        </div>
        <p class="mb-1">Total Transaksi</p>
        <h4 class="card-title mb-3">{{ number_format($totalTransactions) }}</h4>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6 mb-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between mb-4">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-check-circle icon-md"></i></span>
          </div>
        </div>
        <p class="mb-1">Transaksi Paid</p>
        <h4 class="card-title mb-3">{{ number_format($paidTransactions) }}</h4>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body text-center py-5">
        <i class="bx bx-bar-chart-alt-2 mb-3 text-primary" style="font-size: 4rem;"></i>
        <h5>Laporan Detail</h5>
        <p class="text-muted">Laporan detail per periode dan grafik akan tersedia di pengembangan berikutnya.</p>
      </div>
    </div>
  </div>
</div>
@endsection
