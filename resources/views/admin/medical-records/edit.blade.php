@extends('layouts.admin')
@section('title', 'Edit Rekam Medis')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Rekam Medis</h4>
  <a href="{{ route('admin.medical-records.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.medical-records.update', $medical_record) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Hewan *</label>
        <select class="form-select" name="id_hewan" required><option value="">-- Pilih --</option>
          @foreach($pets as $p)<option value="{{ $p->id }}" {{ old('id_hewan', $medical_record->id_hewan) == $p->id ? 'selected' : '' }}>{{ $p->nama_hewan }} ({{ $p->owner->nama ?? '-' }})</option>@endforeach
        </select></div>
      <div class="col-md-4"><label class="form-label">Dokter</label>
        <select class="form-select" name="id_dokter"><option value="">-- Pilih --</option>
          @foreach($doctors as $d)<option value="{{ $d->id }}" {{ old('id_dokter', $medical_record->id_dokter) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
        </select></div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat_saat_itu" value="{{ old('berat_saat_itu', $medical_record->berat_saat_itu) }}" /></div>
      <div class="col-md-2"><label class="form-label">Tanggal *</label><input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', $medical_record->tanggal?->format('Y-m-d')) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Diagnosa</label><textarea class="form-control" name="diagnosa" rows="4">{{ old('diagnosa', $medical_record->diagnosa) }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Tindakan</label><textarea class="form-control" name="tindakan" rows="4">{{ old('tindakan', $medical_record->tindakan) }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Resep Obat</label><textarea class="form-control" name="resep" rows="4">{{ old('resep', $medical_record->resep) }}</textarea></div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $medical_record->catatan) }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
