@extends('layouts.admin')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="row">
  <!-- Welcome Card -->
  <div class="col-xxl-8 mb-6 order-0">
    <div class="card">
      <div class="d-flex align-items-start row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Selamat Datang, drh. {{ Auth::user()->nama }}! 🩺</h5>
            <p class="mb-6">
              Dashboard portal dokter Anda.<br />Cek jadwal, periksa rekam medis, dan lihat daftar pasien dari sini.
            </p>
            <a href="{{ route('doctor.schedule') }}" class="btn btn-sm btn-outline-primary">Lihat Jadwal Saya</a>
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

  <!-- Stats Cards -->
  <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bxs-dog icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1">Total Pasien</p>
            <h4 class="card-title mb-3">{{ number_format($totalPatients) }}</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success"><i class="icon-base bx bx-file icon-md"></i></span>
              </div>
            </div>
            <p class="mb-1">Rekam Medis Saya</p>
            <h4 class="card-title mb-3">{{ number_format($myRecords) }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Medical Records -->
  <div class="col-md-7 col-lg-8 order-2 mb-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Rekam Medis Terbaru</h5>
        <a href="{{ route('doctor.medical-records') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body px-0">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Pasien</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($recentRecords as $rec)
                <tr>
                  <td>
                    <strong>{{ $rec->hewan->nama_hewan ?? '-' }}</strong><br>
                    <small class="text-muted">{{ $rec->hewan->owner->nama ?? '-' }}</small>
                  </td>
                  <td>{{ Str::limit($rec->diagnosa ?: '-', 30) }}</td>
                  <td>{{ Str::limit($rec->tindakan ?: '-', 30) }}</td>
                  <td>{{ \Carbon\Carbon::parse($rec->tanggal)->format('d/m/Y') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">Belum ada rekam medis</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Today's Schedule -->
  <div class="col-md-5 col-lg-4 order-3 mb-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Jadwal Saya Hari Ini</h5>
        <a href="{{ route('doctor.schedule') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse($todaySchedules as $sch)
            <li class="d-flex align-items-center mb-6">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded {{ $sch->is_aktif ? 'bg-label-success' : 'bg-label-secondary' }}">
                  <i class="icon-base bx bx-time"></i>
                </span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">{{ $sch->hari }}</h6>
                  <small class="text-body-secondary">
                    {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                  </small>
                </div>
                <div class="user-progress">
                  <small class="text-body-secondary"><i class="bx bx-group"></i> {{ $sch->kuota }}</small>
                </div>
              </div>
            </li>
          @empty
            <li class="text-center text-muted">Belum ada jadwal untuk hari ini.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
