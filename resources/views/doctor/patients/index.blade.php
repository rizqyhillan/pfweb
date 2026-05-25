@extends('layouts.admin')

@section('title', 'Data Pasien')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Pasien (Hewan)</h5>
        <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary">
          <i class="bx bx-plus me-1"></i> Tambah Pasien
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Hewan</th>
                <th>Jenis / Ras</th>
                <th>Umur / Berat</th>
                <th>Pemilik</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($pets as $pet)
                <tr>
                  <td>{{ $loop->iteration + $pets->firstItem() - 1 }}</td>
                  <td>
                    <strong>{{ $pet->nama_hewan }}</strong>
                  </td>
                  <td>
                    {{ ucfirst($pet->jenis) }}<br>
                    <small class="text-muted">{{ $pet->ras ?: '-' }}</small>
                  </td>
                  <td>
                    {{ $pet->umur ? $pet->umur . ' bln' : '-' }}<br>
                    <small class="text-muted">{{ $pet->berat ? $pet->berat . ' kg' : '-' }}</small>
                  </td>
                  <td>
                    {{ $pet->owner->nama ?? '-' }}<br>
                    <small class="text-muted">{{ $pet->owner->no_hp ?? '-' }}</small>
                  </td>
                  <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $pet->catatan }}">
                      {{ $pet->catatan ?: '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('doctor.patients.show', $pet) }}"><i class="icon-base bx bx-show me-1"></i> Detail</a>
                        <a class="dropdown-item" href="{{ route('doctor.patients.edit', $pet) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                        <form action="{{ route('doctor.patients.destroy', $pet) }}" method="POST">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Belum ada data pasien terdaftar.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        @if($pets->hasPages())
          <div class="mt-4">
            {{ $pets->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
