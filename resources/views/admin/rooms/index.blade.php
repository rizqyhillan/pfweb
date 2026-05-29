@extends('layouts.admin')
@section('title', 'Data Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-end gap-3 mb-6">
  <div>
    <h4 class="mb-0">Data Kamar / Kandang</h4>
    <small class="text-muted">Kelola jenis paket dan kamar untuk penitipan.</small>
  </div>
  <form method="GET" action="{{ route('admin.rooms.index') }}" class="d-flex gap-2 align-items-end">
    <div style="min-width: 220px;">
      <label for="paket" class="form-label mb-2">Filter Paket</label>
      <select id="paket" name="paket" class="form-control form-control-sm">
        <option value="">-- Semua Paket --</option>
        @foreach($paketOptions as $key => $label)
          <option value="{{ $key }}" @if($selectedPaket === $key) selected @endif>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i> Tambah Kamar</a>
  </form>
</div>



<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>No</th><th>Nama</th><th>Paket</th><th>Harga/Hari</th><th>Kapasitas</th><th>Status</th><th>Aksi</th></tr></thead>
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
              data-confirm="Apakah Anda yakin ingin menghapus kamar '{{ $r->nama_kamar }}'?">
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
