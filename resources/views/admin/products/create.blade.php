@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Produk</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.products.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Produk *</label><input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="category" value="{{ old('category') }}" /></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', 0) }}" required />@error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Stok *</label><input type="number" class="form-control" name="stock" value="{{ old('stock', 0) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Unit</label><input type="text" class="form-control" name="unit" value="{{ old('unit', 'pcs') }}" /></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea></div>
    </div>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked /><label class="form-check-label" for="is_active">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
