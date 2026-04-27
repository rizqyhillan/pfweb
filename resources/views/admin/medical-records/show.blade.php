@extends('layouts.admin')
@section('title', 'Detail Rekam Medis')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Detail Rekam Medis</h4>
  <div>
    <a href="{{ route('admin.medical-records.edit', $medical_record) }}" class="btn btn-warning"><i class="bx bx-edit me-1"></i> Edit</a>
    <a href="{{ route('admin.medical-records.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="card mb-6"><div class="card-header"><h5 class="card-title mb-0">Informasi Pemeriksaan</h5></div><div class="card-body">
      <div class="row mb-4">
        <div class="col-md-6"><label class="form-label fw-bold">Hewan</label><p>{{ $medical_record->hewan->nama_hewan ?? '-' }} ({{ $medical_record->hewan->jenis ?? '-' }})</p></div>
        <div class="col-md-6"><label class="form-label fw-bold">Pemilik</label><p>{{ $medical_record->hewan->owner->nama ?? '-' }}</p></div>
      </div>
      <div class="row mb-4">
        <div class="col-md-6"><label class="form-label fw-bold">Dokter</label><p>{{ $medical_record->dokter->nama ?? '-' }}</p></div>
        <div class="col-md-3"><label class="form-label fw-bold">Berat</label><p>{{ $medical_record->berat_saat_itu ? $medical_record->berat_saat_itu . ' kg' : '-' }}</p></div>
        <div class="col-md-3"><label class="form-label fw-bold">Tanggal</label><p>{{ $medical_record->tanggal?->format('d/m/Y H:i') }}</p></div>
      </div>
    </div></div>
    <div class="card mb-6"><div class="card-header bg-label-primary"><h5 class="card-title mb-0"><i class="bx bx-search-alt me-2"></i>Diagnosa</h5></div><div class="card-body pt-4"><p>{!! nl2br(e($medical_record->diagnosa ?? 'Belum ada diagnosa')) !!}</p></div></div>
    <div class="card mb-6"><div class="card-header bg-label-success"><h5 class="card-title mb-0"><i class="bx bx-first-aid me-2"></i>Tindakan</h5></div><div class="card-body pt-4"><p>{!! nl2br(e($medical_record->tindakan ?? 'Belum ada tindakan')) !!}</p></div></div>
    <div class="card mb-6"><div class="card-header bg-label-warning"><h5 class="card-title mb-0"><i class="bx bx-capsule me-2"></i>Resep Obat</h5></div><div class="card-body pt-4"><p>{!! nl2br(e($medical_record->resep ?? 'Belum ada resep')) !!}</p></div></div>
    @if($medical_record->catatan)<div class="card mb-6"><div class="card-header bg-label-info"><h5 class="card-title mb-0"><i class="bx bx-note me-2"></i>Catatan</h5></div><div class="card-body pt-4"><p>{!! nl2br(e($medical_record->catatan)) !!}</p></div></div>@endif
  </div>
  <div class="col-md-4"><div class="card"><div class="card-header"><h5 class="card-title mb-0">Info Hewan</h5></div><div class="card-body">
    <table class="table table-borderless">
      <tr><td class="fw-bold">Nama</td><td>{{ $medical_record->hewan->nama_hewan ?? '-' }}</td></tr>
      <tr><td class="fw-bold">Jenis</td><td>{{ $medical_record->hewan->jenis ?? '-' }}</td></tr>
      <tr><td class="fw-bold">Ras</td><td>{{ $medical_record->hewan->ras ?? '-' }}</td></tr>
      <tr><td class="fw-bold">Umur</td><td>{{ $medical_record->hewan->umur ?? '-' }}</td></tr>
      <tr><td class="fw-bold">Berat</td><td>{{ $medical_record->hewan->berat ? $medical_record->hewan->berat . ' kg' : '-' }}</td></tr>
    </table>
  </div></div></div>
</div>
@endsection
