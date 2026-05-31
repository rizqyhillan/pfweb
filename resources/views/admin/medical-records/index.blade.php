@extends('layouts.admin')
@section('title', 'Rekam Medis')
@section('content')
  <div class="d-flex justify-content-between align-items-end gap-3 mb-6">
    <h4 class="mb-0">Rekam Medis</h4>
    <form method="GET" action="{{ route('admin.medical-records.index') }}" class="d-flex gap-2 align-items-end">
      <div style="min-width: 220px;">
        <label for="search" class="form-label mb-2">Cari Nama Hewan</label>
        <input 
          id="search"
          type="text" 
          name="search" 
          value="{{ request('search') }}" 
          class="form-control form-control-sm" 
          placeholder="Cari..."
        >
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Cari</button>
      @if(request('search'))
        <a href="{{ route('admin.medical-records.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      @endif
      <a href="{{ route('admin.medical-records.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i> Tambah Rekam Medis</a>
      <a href="{{ route('admin.medical-records.export.excel') }}" class="btn btn-success btn-sm"><i class="bx bx-download me-1"></i> Export Excel</a>
    </form>
  </div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 50px;">No</th>
          <th>Hewan</th>
          <th>Pemilik</th>
          <th>Dokter</th>
          <th>Diagnosa</th>
          <th style="width: 140px;">Tanggal</th>
          <th style="width: 90px; text-align: center;">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($records as $r)
        <tr>
          <td class="text-muted">{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
          <td><strong>{{ $r->hewan->nama_hewan ?? '-' }}</strong></td>
          <td>{{ $r->hewan->owner->nama ?? '-' }}</td>
          <td>{{ $r->dokter->nama ?? '-' }}</td>
          <td>
            <span class="text-truncate d-inline-block" style="max-width: 260px;">
              {{ Str::limit($r->diagnosa ?? '-', 40) }}
            </span>
          </td>
          <td>{{ $r->tanggal ? $r->tanggal->format('d/m/Y') : '-' }}</td>
          <td class="text-center">
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="icon-base bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('admin.medical-records.show', $r) }}">
                  <i class="icon-base bx bx-show me-1"></i> Detail
                </a>
                <a class="dropdown-item" href="{{ route('admin.medical-records.edit', $r) }}">
                  <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                </a>
                <form action="{{ route('admin.medical-records.destroy', $r) }}" method="POST" class="m-0">
                  @csrf @method('DELETE')
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="icon-base bx bx-trash me-1"></i> Hapus
                  </button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($records->hasPages())
    <div class="card-footer d-flex justify-content-center">{{ $records->links() }}</div>
  @endif
</div>
@endsection
