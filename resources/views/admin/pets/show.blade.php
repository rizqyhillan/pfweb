@extends('layouts.admin')
@section('title', 'Detail Hewan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Detail Hewan: {{ $pet->nama_hewan }}</h4>
  <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Foto Hewan</h5>
      </div>
      <div class="card-body pt-4 text-center">
        @if($pet->foto)
          <img src="{{ Storage::url($pet->foto) }}" alt="Foto {{ $pet->nama_hewan }}" class="img-fluid rounded" style="max-height: 250px; object-fit: cover;">
        @else
          <div class="p-5 bg-label-secondary rounded text-center">
            <i class="bx bx-image text-muted" style="font-size: 3rem;"></i>
            <p class="mt-2 mb-0 text-muted">Belum ada foto</p>
          </div>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Informasi Hewan</h5>
      </div>
      <div class="card-body pt-4">
        <table class="table table-borderless table-sm mb-0">
          <tr>
            <th width="30%" class="ps-0">Nama</th>
            <td>: <span class="fw-medium text-primary">{{ $pet->nama_hewan }}</span></td>
          </tr>
          <tr>
            <th class="ps-0">Pemilik</th>
            <td>: {{ $pet->owner->nama ?? '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Jenis</th>
            <td>: <span class="text-capitalize">{{ $pet->jenis }}</span></td>
          </tr>
          <tr>
            <th class="ps-0">Ras</th>
            <td>: {{ $pet->ras ?? '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Jenis Kelamin</th>
            <td>: {{ $pet->jenis_kelamin ?? '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Tanggal Lahir</th>
            <td>: {{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') : '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Umur</th>
            <td>: {{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->locale('id')->diffForHumans(null, true) : '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Berat</th>
            <td>: {{ $pet->berat ? $pet->berat . ' kg' : '-' }}</td>
          </tr>
          <tr>
            <th class="ps-0">Catatan</th>
            <td>: {{ $pet->catatan ?? '-' }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-0">Riwayat Rekam Medis</h5>
  </div>
  <div class="card-body pt-4">
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Dokter</th>
            <th>Diagnosa</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pet->rekamMedis as $rm)
            <tr>
              <td>{{ \Carbon\Carbon::parse($rm->tanggal)->format('d M Y') }}</td>
              <td>{{ $rm->dokter->nama ?? '-' }}</td>
              <td>{{ Str::limit($rm->diagnosa, 50) }}</td>
              <td>{{ Str::limit($rm->tindakan, 50) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">Belum ada riwayat rekam medis.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
