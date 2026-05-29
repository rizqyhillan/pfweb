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
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('admin.doctor-services.show', $service) }}"><i class="icon-base bx bx-show-alt me-1"></i> Detail</a>
                                                <a class="dropdown-item" href="{{ route('admin.doctor-services.edit', $service) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                                                <form action="{{ route('admin.doctor-services.destroy', $service) }}" method="POST" class="m-0" data-confirm="Yakin ingin menghapus layanan ini?">
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