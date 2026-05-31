@extends('layouts.admin')
@section('title', 'Data Hewan')
@section('content')
  <div class="d-flex justify-content-between align-items-end gap-3 mb-6">
    <h4 class="mb-0">Data Hewan Peliharaan</h4>
    <form method="GET" action="{{ route('admin.pets.index') }}" class="d-flex gap-2 align-items-end">
      <div style="min-width: 220px;">
        <label for="search" class="form-label mb-2">Cari Pemilik / Hewan</label>
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
        <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      @endif
      <a href="{{ route('admin.pets.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i> Tambah Hewan</a>
    </form>
  </div>

<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>No</th><th>Nama</th><th>Jenis</th><th>Ras</th><th>Umur</th><th>Berat</th><th>Pemilik</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($pets as $pet)
    <tr>
      <td>{{ $loop->iteration + ($pets->currentPage() - 1) * $pets->perPage() }}</td>
      <td><strong>{{ $pet->nama_hewan }}</strong></td>
      <td>{{ $pet->jenis }}</td>
      <td>{{ $pet->ras ?? '-' }}</td>
      <td>{{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->locale('id')->diffForHumans(null, true) : '-' }}</td>
      <td>{{ $pet->berat ? $pet->berat . ' kg' : '-' }}</td>
      <td>{{ $pet->owner->nama ?? '-' }}</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.pets.show', $pet) }}"><i class="icon-base bx bx-show me-1"></i> Detail</a>
            <a class="dropdown-item" href="{{ route('admin.pets.edit', $pet) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
            <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
          </div>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data hewan</td></tr>
    @endforelse
  </tbody>
</table></div>
@if($pets->hasPages())<div class="card-footer d-flex justify-content-center">{{ $pets->links() }}</div>@endif
</div>
@endsection
