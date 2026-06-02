
@extends('layouts.admin')

@section('title', 'Booking Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Booking Dokter</h1>
            <p class="text-muted mb-0">Kelola jadwal booking konsultasi dokter hewan.</p>
        </div>
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
                                <th>Aksi</th>
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
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('karyawan.doctor-bookings.show', $booking) }}">
                                                    <i class="icon-base bx bx-show me-1"></i> Detail
                                                </a>

                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('karyawan.doctor-bookings.update-status', $booking) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="dikonfirmasi">
                                                        <button type="submit" class="dropdown-item text-primary">
                                                            <i class="icon-base bx bx-check me-1"></i> Konfirmasi
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($booking->status === 'dikonfirmasi')
                                                    <a class="dropdown-item text-success" href="{{ route('karyawan.doctor-bookings.payment', $booking) }}">
                                                        <i class="icon-base bx bx-wallet me-1"></i> Bayar & Selesaikan
                                                    </a>
                                                @endif

                                                @if(in_array($booking->status, ['pending', 'dikonfirmasi']))
                                                    <form action="{{ route('karyawan.doctor-bookings.update-status', $booking) }}" method="POST" data-confirm="Batalkan booking dokter ini?">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="batal">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="icon-base bx bx-x me-1"></i> Batal
                                                        </button>
                                                    </form>
                                                @endif
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
                    <h5 class="text-muted">Belum ada booking dokter</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
