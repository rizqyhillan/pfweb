@extends('layouts.admin')
@section('title', 'Tambah Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Barang</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.products.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Barang *</label><input type="text" class="form-control @error('nama_barang') is-invalid @enderror" name="nama_barang" value="{{ old('nama_barang') }}" required />@error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="kategori" value="{{ old('kategori') }}" /></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="harga" value="{{ old('harga', 0) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Stok *</label><input type="number" class="form-control" name="stok" value="{{ old('stok', 0) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Satuan</label><input type="text" class="form-control" name="satuan" value="{{ old('satuan', 'pcs') }}" /></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
