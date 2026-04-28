@extends('layouts.doctor')

@section('title', 'Jadwal Saya')
@section('page-title', 'Jadwal Saya')

@section('content')

    <div class="mb-4">
        <h5 class="fw-700 mb-1" style="color:#1e293b;">Jadwal Praktik</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Jadwal praktik mingguan Anda di PawPet.
        </p>
    </div>

    @php
        $hariUrut = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $hariIni  = now()->locale('id')->translatedFormat('l');

        // Kelompokkan per hari
        $grouped = $schedules->groupBy('hari');
    @endphp

    @if($schedules->isEmpty())
        <div class="card-section">
            <div class="text-center text-muted py-5">
                <i class="bi bi-calendar-x d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                <p class="mb-0">Belum ada jadwal praktik yang terdaftar.</p>
                <small>Hubungi admin untuk menambahkan jadwal Anda.</small>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($hariUrut as $hari)
                @php $schsHari = $grouped->get($hari, collect()); @endphp

                <div class="col-sm-6 col-lg-4">
                    <div class="card-section h-100
                        {{ $hari === $hariIni ? 'border-warning' : '' }}"
                         style="{{ $hari === $hariIni ? 'border-width:2px!important;' : '' }}">

                        {{-- Header hari --}}
                        <div class="card-section-header"
                             style="{{ $hari === $hariIni
                                 ? 'background:rgba(245,158,11,.08);'
                                 : 'background:#f8fafc;' }}">
                            <h6 class="mb-0" style="{{ $hari === $hariIni ? 'color:#d97706;' : '' }}">
                                <i class="bi bi-calendar3 me-2"></i>{{ $hari }}
                            </h6>
                            @if($hari === $hariIni)
                                <span class="badge-status"
                                      style="background:rgba(245,158,11,.15);color:#d97706;font-size:.68rem;">
                                    Hari Ini
                                </span>
                            @endif
                        </div>

                        <div class="p-3">
                            @if($schsHari->isEmpty())
                                <p class="text-muted text-center mb-0 py-2" style="font-size:.82rem;">
                                    <i class="bi bi-dash-circle me-1"></i> Tidak ada jadwal
                                </p>
                            @else
                                @foreach($schsHari as $sch)
                                    <div class="d-flex align-items-center gap-3 py-2
                                                {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:40px;height:40px;
                                             background:{{ $sch->is_aktif
                                                 ? 'rgba(16,185,129,.1)' : 'rgba(148,163,184,.1)' }};">
                                            <i class="bi bi-clock"
                                               style="color:{{ $sch->is_aktif ? '#10b981' : '#94a3b8' }};"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-600" style="font-size:.85rem;">
                                                {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }}
                                                –
                                                {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>Kuota: {{ $sch->kuota }} pasien
                                            </small>
                                        </div>
                                        <span class="badge-status"
                                              style="{{ $sch->is_aktif
                                                  ? 'background:rgba(16,185,129,.12);color:#059669;'
                                                  : 'background:rgba(148,163,184,.15);color:#64748b;' }}">
                                            {{ $sch->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
