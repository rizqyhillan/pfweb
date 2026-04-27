@extends('layouts.admin')
@section('title', 'Tambah Rekam Medis')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Rekam Medis</h4>
  <a href="{{ route('admin.medical-records.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.medical-records.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Hewan *</label>
        <select class="form-select @error('pet_id') is-invalid @enderror" name="pet_id" required>
          <option value="">-- Pilih Hewan --</option>
          @foreach($pets as $pet)<option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>{{ $pet->name }} ({{ $pet->owner->name ?? '-' }})</option>@endforeach
        </select>@error('pet_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4"><label class="form-label">Dokter</label>
        <select class="form-select" name="doctor_id">
          <option value="">-- Pilih Dokter --</option>
          @foreach($doctors as $doc)<option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>@endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="current_weight" value="{{ old('current_weight') }}" /></div>
      <div class="col-md-2"><label class="form-label">Tanggal *</label><input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Diagnosis</label><textarea class="form-control" name="diagnosis" rows="3">{{ old('diagnosis') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Treatment</label><textarea class="form-control" name="treatment" rows="3">{{ old('treatment') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Resep</label><textarea class="form-control" name="prescription" rows="3">{{ old('prescription') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
