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
        <small class="text-success fw-medium">Semua transaksi lunas</small>
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
        <p class="mb-1">Transaksi Lunas</p>
        <h4 class="card-title mb-3">{{ number_format($paidTransactions) }}</h4>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-download me-2"></i>Export Laporan</h5>
      </div>
      <div class="card-body">
        <form id="exportForm" class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ $startDate ?? '' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $endDate ?? '' }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Filter</label>
            <button type="submit" class="btn btn-outline-primary d-block w-100">
              <i class="bx bx-filter-alt me-1"></i> Terapkan Filter
            </button>
          </div>
        </form>

        <hr class="my-4">

        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card bg-label-danger h-100">
              <div class="card-body text-center py-4">
                <i class="bx bxs-file-pdf mb-2" style="font-size: 2.5rem; color: #e74c3c;"></i>
                <h6 class="mb-2">Export PDF</h6>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">Download laporan transaksi dalam format PDF dengan ringkasan lengkap.</p>
                <a href="#" class="btn btn-danger" id="btnExportPdf">
                  <i class="bx bx-download me-1"></i> Download PDF
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card bg-label-success h-100">
              <div class="card-body text-center py-4">
                <i class="bx bxs-file mb-2" style="font-size: 2.5rem; color: #27ae60;"></i>
                <h6 class="mb-2">Export Excel</h6>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">Download data transaksi dalam format Excel untuk analisis lebih lanjut.</p>
                <a href="#" class="btn btn-success" id="btnExportExcel">
                  <i class="bx bx-download me-1"></i> Download Excel
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let start = document.getElementById('start_date').value;
        let end = document.getElementById('end_date').value;
        let params = new URLSearchParams();
        if(start) params.set('start_date', start);
        if(end) params.set('end_date', end);
        window.location.href = '{{ route("admin.reports.index") }}' + '?' + params.toString();
    });

    document.getElementById('btnExportPdf').addEventListener('click', function(e) {
        e.preventDefault();
        let start = document.getElementById('start_date').value;
        let end = document.getElementById('end_date').value;
        let params = new URLSearchParams();
        if(start) params.set('start_date', start);
        if(end) params.set('end_date', end);
        window.location.href = '{{ route("admin.reports.export-pdf") }}' + '?' + params.toString();
    });

    document.getElementById('btnExportExcel').addEventListener('click', function(e) {
        e.preventDefault();
        let start = document.getElementById('start_date').value;
        let end = document.getElementById('end_date').value;
        let params = new URLSearchParams();
        if(start) params.set('start_date', start);
        if(end) params.set('end_date', end);
        window.location.href = '{{ route("admin.reports.export-excel") }}' + '?' + params.toString();
    });
});
</script>
@endsection
