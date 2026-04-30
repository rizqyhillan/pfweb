@extends('layouts.admin')
@section('title', 'Edit Pasien')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Data Pasien (Hewan)</h4>
  <a href="{{ route('doctor.patients') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.patients.update', $pet) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Pemilik *</label>
        <select class="form-select @error('id_pemilik') is-invalid @enderror" name="id_pemilik" required><option value="">-- Pilih --</option>
          @foreach($owners as $o)<option value="{{ $o->id }}" {{ old('id_pemilik', $pet->id_pemilik) == $o->id ? 'selected' : '' }}>{{ $o->nama }} ({{ $o->email }})</option>@endforeach
        </select>@error('id_pemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Nama Hewan *</label><input type="text" class="form-control @error('nama_hewan') is-invalid @enderror" name="nama_hewan" value="{{ old('nama_hewan', $pet->nama_hewan) }}" required />@error('nama_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Jenis *</label>
        <select class="form-select" name="jenis" required>
            <option value="kucing" {{ old('jenis', $pet->jenis) == 'kucing' ? 'selected' : '' }}>Kucing</option>
            <option value="anjing" {{ old('jenis', $pet->jenis) == 'anjing' ? 'selected' : '' }}>Anjing</option>
            <option value="burung" {{ old('jenis', $pet->jenis) == 'burung' ? 'selected' : '' }}>Burung</option>
            <option value="reptil" {{ old('jenis', $pet->jenis) == 'reptil' ? 'selected' : '' }}>Reptil</option>
            <option value="lainnya" {{ old('jenis', $pet->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
      </div>
      <div class="col-md-4"><label class="form-label">Ras</label><input type="text" class="form-control" name="ras" value="{{ old('ras', $pet->ras) }}" /></div>
      <div class="col-md-2"><label class="form-label">Umur (bln)</label><input type="number" class="form-control" name="umur" value="{{ old('umur', $pet->umur) }}" placeholder="2" /></div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat" value="{{ old('berat', $pet->berat) }}" /></div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $pet->catatan) }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
  </form>
</div></div>
@endsection
