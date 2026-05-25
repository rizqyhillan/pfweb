@extends('layouts.admin')

@section('title', 'Jadwal Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Jadwal Dokter</h1>
            <p class="text-muted mb-0">Kelola hari dan jam praktik dokter.</p>
        </div>

        <a href="{{ route('admin.doctor-schedules.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Jadwal
        </a>
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Jadwal Dokter</h5>
        </div>

        <div class="card-body">
            @if($schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dokter</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $schedule->dokter->nama ?? '-' }}</td>
                                    <td>{{ ucfirst($schedule->hari) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i') }}</td>
                                    <td>
                                        @if($schedule->is_aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.doctor-schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.doctor-schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.doctor-schedules.destroy', $schedule) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus jadwal ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="text-muted">Belum ada jadwal dokter</h5>
                    <p class="text-muted mb-3">Tambahkan jadwal praktik dokter terlebih dahulu.</p>
                    <a href="{{ route('admin.doctor-schedules.create') }}" class="btn btn-primary">
                        Tambah Jadwal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection