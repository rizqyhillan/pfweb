@extends('layouts.admin')

@section('title', 'Data Hewan Peliharaan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Hewan Peliharaan</h4>
  <a href="{{ route('admin.pets.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Hewan</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Spesies</th>
          <th>Ras</th>
          <th>Umur</th>
          <th>Berat</th>
          <th>Pemilik</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($pets as $pet)
        <tr>
          <td>{{ $loop->iteration + ($pets->currentPage() - 1) * $pets->perPage() }}</td>
          <td><strong>{{ $pet->name }}</strong></td>
          <td>{{ $pet->species }}</td>
          <td>{{ $pet->breed ?? '-' }}</td>
          <td>{{ $pet->age ?? '-' }}</td>
          <td>{{ $pet->weight ? $pet->weight . ' kg' : '-' }}</td>
          <td>{{ $pet->owner->name ?? '-' }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.pets.edit', $pet) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data hewan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($pets->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $pets->links() }}
  </div>
  @endif
</div>
@endsection
