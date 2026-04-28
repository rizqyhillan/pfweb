@extends('layouts.doctor')

@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Greeting ─────────────────────────────── --}}
    <div class="mb-4">
        <h4 class="fw-700 mb-1" style="color:#1e293b;">
            Selamat datang, drh. {{ $doctor->nama }} 👋
        </h4>
        <p class="text-muted mb-0" style="font-size:.875rem;">
            {{ now()->locale('id')->translatedFormat('l, d F Y') }} — Berikut ringkasan aktivitas Anda hari ini.
        </p>
    </div>

    {{-- Statistik ─────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b;">
                    <i class="bi bi-paw"></i>
                </div>
                <div class="stat-value">{{ $totalPatients }}</div>
                <div class="stat-label">Total Pasien Terdaftar</div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(59,130,246,.12);color:#3b82f6;">
                    <i class="bi bi-file-medical"></i>
                </div>
                <div class="stat-value">{{ $myRecords }}</div>
                <div class="stat-label">Rekam Medis Saya</div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $mySchedulesToday }}</div>
                <div class="stat-label">Jadwal Aktif Hari Ini</div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        {{-- Rekam Medis Terbaru ─────────────────── --}}
        <div class="col-lg-7">
            <div class="card-section h-100">
                <div class="card-section-header">
                    <h6><i class="bi bi-file-medical me-2" style="color:#3b82f6;"></i>Rekam Medis Terbaru</h6>
                    <a href="{{ route('doctor.medical-records') }}"
                       style="font-size:.78rem;color:#f59e0b;font-weight:600;text-decoration:none;">
                        Lihat Semua →
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.83rem;">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="ps-3 py-2 text-muted fw-600" style="font-size:.72rem;">PASIEN</th>
                                <th class="py-2 text-muted fw-600" style="font-size:.72rem;">DIAGNOSA</th>
                                <th class="py-2 text-muted fw-600" style="font-size:.72rem;">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRecords as $rec)
                                <tr>
                                    <td class="ps-3 py-2 align-middle">
                                        <div class="fw-600">{{ $rec->hewan->nama_hewan }}</div>
                                        <small class="text-muted">{{ $rec->hewan->owner->nama ?? '—' }}</small>
                                    </td>
                                    <td class="py-2 align-middle" style="max-width:180px;">
                                        <span class="text-truncate d-block">{{ $rec->diagnosa ?: '—' }}</span>
                                    </td>
                                    <td class="py-2 align-middle text-muted">
                                        {{ \Carbon\Carbon::parse($rec->tanggal)->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem;"></i>
                                        Belum ada rekam medis
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Jadwal Hari Ini ─────────────────────── --}}
        <div class="col-lg-5">
            <div class="card-section h-100">
                <div class="card-section-header">
                    <h6><i class="bi bi-calendar-week me-2" style="color:#10b981;"></i>Jadwal Saya</h6>
                    <a href="{{ route('doctor.schedule') }}"
                       style="font-size:.78rem;color:#f59e0b;font-weight:600;text-decoration:none;">
                        Lihat Semua →
                    </a>
                </div>
                <div class="p-3">
                    @forelse($todaySchedules as $sch)
                        <div class="d-flex align-items-center gap-3 py-2
                                    {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;background:rgba(245,158,11,.1);">
                                <i class="bi bi-clock" style="color:#f59e0b;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-600" style="font-size:.85rem;">{{ $sch->hari }}</div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}
                                    – {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                                    &nbsp;·&nbsp; Kuota: {{ $sch->kuota }}
                                </small>
                            </div>
                            <span class="badge-status
                                {{ $sch->is_aktif ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ $sch->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x d-block mb-1" style="font-size:1.5rem;"></i>
                            Belum ada jadwal
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Quick Links ─────────────────────────────── --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <p class="text-muted mb-2" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">
                Akses Cepat
            </p>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('doctor.patients') }}" class="text-decoration-none">
                <div class="stat-card d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon mb-0" style="background:rgba(245,158,11,.12);color:#f59e0b;flex-shrink:0;">
                        <i class="bi bi-paw"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="color:#1e293b;font-size:.9rem;">Data Pasien</div>
                        <small class="text-muted">Semua hewan terdaftar</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('doctor.medical-records') }}" class="text-decoration-none">
                <div class="stat-card d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon mb-0" style="background:rgba(59,130,246,.12);color:#3b82f6;flex-shrink:0;">
                        <i class="bi bi-file-medical"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="color:#1e293b;font-size:.9rem;">Rekam Medis</div>
                        <small class="text-muted">Riwayat penanganan</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('doctor.schedule') }}" class="text-decoration-none">
                <div class="stat-card d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon mb-0" style="background:rgba(16,185,129,.12);color:#10b981;flex-shrink:0;">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="color:#1e293b;font-size:.9rem;">Jadwal Saya</div>
                        <small class="text-muted">Jam & hari praktik</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

@endsection
