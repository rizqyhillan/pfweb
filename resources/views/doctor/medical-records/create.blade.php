@extends('layouts.admin')
@section('title', 'Tambah Rekam Medis')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Rekam Medis</h4>
  <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.medical-records.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Hewan *</label>
        <select class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan" required><option value="">-- Pilih --</option>
          @foreach($pets as $p)<option value="{{ $p->id }}" {{ old('id_hewan') == $p->id ? 'selected' : '' }}>{{ $p->nama_hewan }} ({{ $p->owner->nama ?? '-' }})</option>@endforeach
        </select>@error('id_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat_saat_itu" value="{{ old('berat_saat_itu') }}" /></div>
      <div class="col-md-3"><label class="form-label">Tanggal *</label><input type="datetime-local" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Diagnosa</label><textarea class="form-control" name="diagnosa" rows="4">{{ old('diagnosa') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Tindakan</label><textarea class="form-control" name="tindakan" rows="4">{{ old('tindakan') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Resep Obat</label><textarea class="form-control" name="resep" rows="4">{{ old('resep') }}</textarea></div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan Tambahan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
