@extends('layouts.admin')

@section('title', 'Booking Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Booking Dokter</h1>
            <p class="text-muted mb-0">Kelola jadwal booking konsultasi dokter hewan.</p>
        </div>

        <a href="{{ route('admin.doctor-bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Booking
        </a>
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Booking Dokter</h5>
        </div>

        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Hewan</th>
                                <th>Pemilik</th>
                                <th>Dokter</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Total Biaya</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->hewan->nama_hewan ?? '-' }}</td>
                                    <td>{{ $booking->hewan->owner->nama ?? '-' }}</td>
                                    <td>{{ $booking->dokter->nama ?? '-' }}</td>
                                    <td>{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                                    <td>{{ optional($booking->tanggal_booking)->format('d M Y') }}</td>
                                    <td>{{ $booking->jam_booking ? \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') : '-' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($booking->status) {
                                                'pending' => 'warning',
                                                'dikonfirmasi' => 'info',
                                                'selesai' => 'success',
                                                'batal' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.doctor-bookings.show', $booking) }}" class="btn btn-sm btn-outline-info">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.doctor-bookings.edit', $booking) }}" class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>

                                        @if($booking->status === 'pending')
                                            <form action="{{ route('admin.doctor-bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="dikonfirmasi">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Konfirmasi</button>
                                            </form>
                                        @endif

                                        @if(in_array($booking->status, ['pending', 'dikonfirmasi']))
                                            <form action="{{ route('admin.doctor-bookings.update-status', $booking) }}" method="POST" class="d-inline" data-confirm="Batalkan booking dokter ini?">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="batal">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Batal</button>
                                            </form>
                                        @endif

                                        @if($booking->status === 'dikonfirmasi')
                                            <form action="{{ route('admin.doctor-bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="btn btn-sm btn-outline-success">Selesai</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.doctor-bookings.destroy', $booking) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus booking ini?">
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
                    <h5 class="text-muted">Belum ada booking dokter</h5>
                    <p class="text-muted mb-3">Tambahkan booking dokter pertama untuk mulai mengelola konsultasi.</p>
                    <a href="{{ route('admin.doctor-bookings.create') }}" class="btn btn-primary">
                        Tambah Booking
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection