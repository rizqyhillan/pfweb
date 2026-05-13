@extends('layouts.admin')
@section('title', 'Data Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <div>
    <h4 class="mb-0">Data Kamar / Kandang</h4>
    <small class="text-muted">Kelola jenis paket dan kamar untuk penitipan.</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.package-types.index') }}" class="btn btn-outline-secondary"><i class="bx bx-box me-1"></i> Jenis Paket</a>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Kamar</a>
  </div>
</div>

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bx bx-error-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>#</th><th>Nama</th><th>Paket</th><th>Harga/Hari</th><th>Kapasitas</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($rooms as $r)
    <tr>
      <td>{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
      <td><strong>{{ $r->nama_kamar }}</strong></td>
      <td>
        @php
          $paketColors = ['basic' => 'info', 'regular' => 'warning', 'premium' => 'success'];
          $paketLabels = ['basic' => 'Basic', 'regular' => 'Regular', 'premium' => 'Premium'];
        @endphp
        <span class="badge bg-label-{{ $paketColors[$r->paket] ?? 'secondary' }}">{{ $paketLabels[$r->paket] ?? ucfirst($r->paket) }}</span>
      </td>
      <td>Rp {{ number_format($r->harga_per_hari, 0, ',', '.') }}</td>
      <td>{{ $r->kapasitas }}</td>
      <td>
        @if($r->status === 'tersedia')<span class="badge bg-label-success">Tersedia</span>
        @elseif($r->status === 'terisi')<span class="badge bg-label-warning">Terisi</span>
        @else<span class="badge bg-label-secondary">Maintenance</span>@endif
      </td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.rooms.edit', $r) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
            <form action="{{ route('admin.rooms.destroy', $r) }}" method="POST"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar \'{{ $r->nama_kamar }}\'?')">
              @csrf @method('DELETE')
              <button class="dropdown-item text-danger">
                <i class="icon-base bx bx-trash me-1"></i> Hapus
              </button>
            </form>
          </div>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada kamar</td></tr>
    @endforelse
  </tbody>
</table></div>
@if($rooms->hasPages())<div class="card-footer d-flex justify-content-center">{{ $rooms->links() }}</div>@endif
</div>
@endsection
