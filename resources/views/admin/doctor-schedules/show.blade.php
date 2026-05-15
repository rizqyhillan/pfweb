@extends('layouts.admin')

@section('title', 'Detail Jadwal Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Jadwal Dokter</h1>
            <p class="text-muted mb-0">Informasi detail jadwal praktik dokter.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctor-schedules.edit', $doctorSchedule) }}" class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('admin.doctor-schedules.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Data Jadwal</h5>
        </div>

        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="220">Dokter</th>
                    <td>{{ $doctorSchedule->dokter->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Hari</th>
                    <td>{{ ucfirst($doctorSchedule->hari) }}</td>
                </tr>
                <tr>
                    <th>Jam Mulai</th>
                    <td>{{ \Carbon\Carbon::parse($doctorSchedule->jam_mulai)->format('H:i') }}</td>
                </tr>
                <tr>
                    <th>Jam Selesai</th>
                    <td>{{ \Carbon\Carbon::parse($doctorSchedule->jam_selesai)->format('H:i') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($doctorSchedule->is_aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection