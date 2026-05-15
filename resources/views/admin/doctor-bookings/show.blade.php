@extends('layouts.admin')

@section('title', 'Detail Booking Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Booking Dokter</h1>
            <p class="text-muted mb-0">Informasi lengkap booking konsultasi dokter.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctor-bookings.edit', $doctorBooking) }}" class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('admin.doctor-bookings.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    @php
        $badgeClass = match($doctorBooking->status) {
            'pending' => 'warning',
            'dikonfirmasi' => 'info',
            'selesai' => 'success',
            'batal' => 'danger',
            default => 'secondary'
        };
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Data Booking</h5>
                </div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="220">Status</th>
                            <td>
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ ucfirst($doctorBooking->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Booking</th>
                            <td>{{ optional($doctorBooking->tanggal_booking)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jam Booking</th>
                            <td>{{ $doctorBooking->jam_booking ? \Carbon\Carbon::parse($doctorBooking->jam_booking)->format('H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Biaya</th>
                            <td>Rp {{ number_format($doctorBooking->total_biaya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Layanan</th>
                            <td>{{ $doctorBooking->layanan->nama_layanan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jadwal Dokter</th>
                            <td>
                                @if($doctorBooking->jadwal)
                                    {{ ucfirst($doctorBooking->jadwal->hari) }},
                                    {{ \Carbon\Carbon::parse($doctorBooking->jadwal->jam_mulai)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($doctorBooking->jadwal->jam_selesai)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keluhan</th>
                            <td>{{ $doctorBooking->keluhan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan Dokter</th>
                            <td>{{ $doctorBooking->catatan_dokter ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Transaksi</h5>
                </div>

                <div class="card-body">
                    @if($doctorBooking->transaksi)
                        <table class="table table-borderless">
                            <tr>
                                <th width="220">Kode Transaksi</th>
                                <td>{{ $doctorBooking->transaksi->kode_transaksi }}</td>
                            </tr>
                            <tr>
                                <th>Status Transaksi</th>
                                <td>{{ ucfirst($doctorBooking->transaksi->status) }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>Rp {{ number_format($doctorBooking->transaksi->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Metode Bayar</th>
                                <td>{{ $doctorBooking->transaksi->metode_bayar ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status Payment</th>
                                <td>{{ $doctorBooking->transaksi->payment_status ?? '-' }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted mb-0">Belum terhubung dengan transaksi.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Data Hewan</h5>
                </div>

                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $doctorBooking->hewan->nama_hewan ?? '-' }}</p>
                    <p class="mb-1"><strong>Jenis:</strong> {{ $doctorBooking->hewan->jenis ?? '-' }}</p>
                    <p class="mb-1"><strong>Ras:</strong> {{ $doctorBooking->hewan->ras ?? '-' }}</p>
                    <p class="mb-0"><strong>Berat:</strong> {{ $doctorBooking->hewan->berat ?? '-' }} kg</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pemilik</h5>
                </div>

                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $doctorBooking->hewan->owner->nama ?? '-' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $doctorBooking->hewan->owner->email ?? '-' }}</p>
                    <p class="mb-0"><strong>No HP:</strong> {{ $doctorBooking->hewan->owner->no_hp ?? '-' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dokter</h5>
                </div>

                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $doctorBooking->dokter->nama ?? '-' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $doctorBooking->dokter->email ?? '-' }}</p>
                    <p class="mb-0"><strong>No HP:</strong> {{ $doctorBooking->dokter->no_hp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection