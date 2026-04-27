@extends('layouts.admin')
@section('title', 'Tambah Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Kamar</h4>
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.rooms.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Nama Kamar *</label><input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-4"><label class="form-label">Tipe *</label>
        <select class="form-select" name="type" required>
          <option value="small" {{ old('type') == 'small' ? 'selected' : '' }}>Small</option>
          <option value="medium" {{ old('type', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="large" {{ old('type') == 'large' ? 'selected' : '' }}>Large</option>
        </select>
      </div>
      <div class="col-md-4"><label class="form-label">Status *</label>
        <select class="form-select" name="status" required>
          <option value="available">Tersedia</option>
          <option value="occupied">Terisi</option>
          <option value="maintenance">Maintenance</option>
        </select>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Harga per Hari *</label><input type="number" step="0.01" class="form-control" name="price_per_day" value="{{ old('price_per_day', 0) }}" required /></div>
      <div class="col-md-4"><label class="form-label">Kapasitas *</label><input type="number" class="form-control" name="capacity" value="{{ old('capacity', 1) }}" min="1" required /></div>
      <div class="col-md-4"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
