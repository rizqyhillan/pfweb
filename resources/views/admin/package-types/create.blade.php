@extends('layouts.admin')
@section('title', request('section') === 'grooming' ? 'Tambah Jenis Grooming' : 'Tambah Jenis Paket')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">{{ request('section') === 'grooming' ? 'Tambah Jenis Grooming' : 'Tambah Jenis Paket' }}</h4>
  <a href="{{ route('admin.package-types.index', request()->only('section')) }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.package-types.store') }}" method="POST">@csrf
    <input type="hidden" name="section" value="{{ request('section') }}" />
    <div class="row mb-4">
      <div class="col-md-3"><label class="form-label">Nama Paket *</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Label Paket *</label><input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}" required />@error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">{{ request('section') === 'grooming' ? 'Harga Paket' : 'Harga/Malam' }} *</label><input type="number" step="0.01" name="harga_per_malam" class="form-control @error('harga_per_malam') is-invalid @enderror" value="{{ old('harga_per_malam') }}" required />@error('harga_per_malam')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Deskripsi Singkat</label><input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="Contoh: Mandi + pengeringan bulu" />@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-4">
      <div class="col-md-12">
        <label class="form-label">Fasilitas / Layanan <small class="text-muted">(satu per baris, akan tampil sebagai checklist di mobile)</small></label>
        <textarea name="fasilitas_input" class="form-control @error('fasilitas_input') is-invalid @enderror" rows="5" placeholder="Mandi dengan shampoo khusus&#10;Pengeringan bulu&#10;Penyisiran bulu">{{ old('fasilitas_input') }}</textarea>
        @error('fasilitas_input')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
