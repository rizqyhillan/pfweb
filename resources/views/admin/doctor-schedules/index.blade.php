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
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('admin.doctor-schedules.show', $schedule) }}"><i class="icon-base bx bx-show-alt me-1"></i> Detail</a>
                                                <a class="dropdown-item" href="{{ route('admin.doctor-schedules.edit', $schedule) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                                                <form action="{{ route('admin.doctor-schedules.destroy', $schedule) }}" method="POST" class="m-0" data-confirm="Yakin ingin menghapus jadwal ini?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                                                </form>
                                            </div>
                                        </div>
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