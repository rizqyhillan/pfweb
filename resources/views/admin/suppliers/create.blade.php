@extends('layouts.admin')
@section('title', 'Tambah Supplier')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Supplier</h4>
  <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.suppliers.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Supplier *</label><input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" name="nama_supplier" value="{{ old('nama_supplier') }}" required />@error('nama_supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Kontak</label><input type="number" class="form-control" name="kontak" value="{{ old('kontak') }}" /></div>
      <div class="col-md-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" /></div>
    </div>
    <div class="mb-6"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2">{{ old('alamat') }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
