@extends('layouts.admin')
@section('title', 'Edit Hewan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Hewan Peliharaan</h4>
  <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.pets.update', $pet) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Pemilik *</label>
        <select class="form-select" name="id_pemilik" required>
          @foreach($owners as $o)<option value="{{ $o->id }}" {{ old('id_pemilik', $pet->id_pemilik) == $o->id ? 'selected' : '' }}>{{ $o->nama }} ({{ $o->email }})</option>@endforeach
        </select></div>
      <div class="col-md-6"><label class="form-label">Nama Hewan *</label><input type="text" class="form-control" name="nama_hewan" value="{{ old('nama_hewan', $pet->nama_hewan) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Jenis *</label><input type="text" class="form-control" name="jenis" value="{{ old('jenis', $pet->jenis) }}" required /></div>
      <div class="col-md-4"><label class="form-label">Ras</label><input type="text" class="form-control" name="ras" value="{{ old('ras', $pet->ras) }}" /></div>
      <div class="col-md-2"><label class="form-label">Umur</label><input type="text" class="form-control" name="umur" value="{{ old('umur', $pet->umur) }}" /></div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat" value="{{ old('berat', $pet->berat) }}" /></div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $pet->catatan) }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
