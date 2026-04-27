@extends('layouts.admin')
@section('title', 'Edit Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Barang</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.products.update', $product) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Barang *</label><input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="kategori" value="{{ old('kategori', $product->kategori) }}" /></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="harga" value="{{ old('harga', $product->harga) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Stok *</label><input type="number" class="form-control" name="stok" value="{{ old('stok', $product->stok) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Satuan</label><input type="text" class="form-control" name="satuan" value="{{ old('satuan', $product->satuan) }}" /></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi', $product->deskripsi) }}</textarea></div>
    </div>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_aktif" {{ old('is_aktif', $product->is_aktif) ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
