@extends('layouts.admin')

@section('title', 'Data Pasien')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Pasien (Hewan)</h5>
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
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Belum ada data pasien terdaftar.</td>
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
