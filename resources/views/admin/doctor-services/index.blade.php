@extends('layouts.admin')

@section('title', 'Layanan Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Layanan Dokter</h1>
            <p class="text-muted mb-0">Kelola layanan medis yang tersedia untuk booking dokter.</p>
        </div>

        <a href="{{ route('admin.doctor-services.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Layanan
        </a>
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Layanan Dokter</h5>
        </div>

        <div class="card-body">
            @if($services->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Dokter</th>
                                <th>Estimasi Harga</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $service->nama_layanan }}</td>
                                    <td>{{ $service->dokter->nama ?? 'Semua Dokter' }}</td>
                                    <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($service->is_aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($service->deskripsi, 50) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.doctor-services.show', $service) }}" class="btn btn-sm btn-outline-info">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.doctor-services.edit', $service) }}" class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.doctor-services.destroy', $service) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus layanan ini?">
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
                    <h5 class="text-muted">Belum ada layanan dokter</h5>
                    <p class="text-muted mb-3">Tambahkan layanan medis yang bisa dipilih customer saat booking dokter.</p>
                    <a href="{{ route('admin.doctor-services.create') }}" class="btn btn-primary">
                        Tambah Layanan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection