@extends('layouts.admin')

@section('title', 'Detail Layanan Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Layanan Dokter</h1>
            <p class="text-muted mb-0">Informasi lengkap layanan dokter.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctor-services.edit', $doctorService) }}" class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('admin.doctor-services.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Data Layanan</h5>
        </div>

        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="220">Nama Layanan</th>
                    <td>{{ $doctorService->nama_layanan }}</td>
                </tr>
                <tr>
                    <th>Dokter</th>
                    <td>{{ $doctorService->dokter->nama ?? 'Semua Dokter' }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp {{ number_format($doctorService->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($doctorService->is_aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $doctorService->deskripsi ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection