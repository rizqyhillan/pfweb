@extends('layouts.admin')
@section('title', 'Tambah Hewan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Hewan Peliharaan</h4>
  <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Pemilik *</label>
        <select class="form-select @error('id_pemilik') is-invalid @enderror" name="id_pemilik" required><option value="">-- Pilih --</option>
          @foreach($owners as $o)<option value="{{ $o->id }}" {{ old('id_pemilik') == $o->id ? 'selected' : '' }}>{{ $o->nama }} ({{ $o->email }})</option>@endforeach
        </select>@error('id_pemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Nama Hewan *</label><input type="text" class="form-control @error('nama_hewan') is-invalid @enderror" name="nama_hewan" value="{{ old('nama_hewan') }}" required />@error('nama_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4">
        @include('components.autocomplete', [
          'name' => 'jenis',
          'label' => 'Jenis Hewan *',
          'options' => $types->pluck('name'),
          'value' => old('jenis'),
          'placeholder' => 'Ketik jenis hewan...',
          'required' => true,
        ])
      </div>
      <div class="col-md-4">
        @include('components.autocomplete', [
          'name' => 'ras',
          'label' => 'Ras',
          'options' => $breeds->pluck('name'),
          'value' => old('ras'),
          'placeholder' => 'Ketik ras hewan...',
          'required' => false,
        ])
      </div>
      <div class="col-md-2"><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" /></div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat" value="{{ old('berat') }}" /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-12">
        <label class="form-label">Foto Hewan</label>
        <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*" />
        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
