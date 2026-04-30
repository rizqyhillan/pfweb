@extends('layouts.admin')

@section('title', 'Jadwal Saya')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-2">Jadwal Praktik</h5>
            <p class="card-text text-muted">
              Berikut adalah jadwal praktik mingguan Anda. Anda dapat mengatur jadwal ini secara mandiri.
            </p>
          </div>
          <a href="{{ route('doctor.schedule.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tambah Jadwal
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
  $hariIni  = now()->locale('id')->isoFormat('dddd');
  $grouped = $schedules->groupBy('hari');
@endphp

@if($schedules->isEmpty())
  <div class="row">
    <div class="col-12 text-center py-5">
      <i class="bx bx-calendar-x mb-3 text-muted" style="font-size: 4rem;"></i>
      <h4>Belum Ada Jadwal</h4>
      <p class="text-muted">Anda belum memiliki jadwal praktik yang terdaftar di sistem.</p>
    </div>
  </div>
@else
  <div class="row">
    @foreach($hariUrut as $hari)
      @php $schsHari = $grouped->get($hari, collect()); @endphp
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 {{ $hari === $hariIni ? 'border border-primary shadow-sm' : '' }}">
          <div class="card-header {{ $hari === $hariIni ? 'bg-label-primary' : 'bg-lighter' }} d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 {{ $hari === $hariIni ? 'text-primary' : '' }}">
              <i class="bx bx-calendar me-1"></i> {{ $hari }}
            </h6>
            @if($hari === $hariIni)
              <span class="badge bg-primary">Hari Ini</span>
            @endif
          </div>
          <div class="card-body pt-3">
            @if($schsHari->isEmpty())
              <div class="text-center text-muted py-3">
                <small>Libur / Tidak ada jadwal</small>
              </div>
            @else
              <ul class="p-0 m-0">
                @foreach($schsHari as $sch)
                  <li class="d-flex mb-3 align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                      <span class="avatar-initial rounded {{ $sch->is_aktif ? 'bg-label-success' : 'bg-label-secondary' }}">
                        <i class="bx bx-time"></i>
                      </span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">
                          {{ \Carbon\Carbon::parse($sch->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->jam_selesai)->format('H:i') }}
                        </h6>
                        <small class="text-muted">Kuota: {{ $sch->kuota }}</small>
                      </div>
                      <div class="user-progress">
                        <span class="badge {{ $sch->is_aktif ? 'bg-label-success' : 'bg-label-secondary' }} d-block mb-2">
                          {{ $sch->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <div class="d-flex justify-content-end gap-1">
                          <a href="{{ route('doctor.schedule.edit', $sch) }}" class="btn btn-xs btn-icon btn-primary" title="Edit"><i class="bx bx-edit"></i></a>
                          <form action="{{ route('doctor.schedule.destroy', $sch) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-icon btn-danger" title="Hapus"><i class="bx bx-trash"></i></button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif
@endsection
