@extends('layouts.admin')

@section('title', 'Data Layanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Layanan</h4>
  <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Layanan</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Tipe</th>
          <th>Harga</th>
          <th>Durasi</th>
          <th>Dokter</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($services as $service)
        <tr>
          <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
          <td><strong>{{ $service->name }}</strong></td>
          <td><span class="badge bg-label-info">{{ ucfirst($service->type) }}</span></td>
          <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
          <td>{{ $service->duration_minutes ? $service->duration_minutes . ' menit' : '-' }}</td>
          <td>{{ $service->doctor->name ?? '-' }}</td>
          <td>
            @if($service->is_active)
              <span class="badge bg-label-success">Aktif</span>
            @else
              <span class="badge bg-label-secondary">Non-aktif</span>
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.services.edit', $service) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data layanan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($services->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $services->links() }}
  </div>
  @endif
</div>
@endsection
