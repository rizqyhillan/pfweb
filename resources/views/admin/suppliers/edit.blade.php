@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Supplier</h4>
  <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Supplier *</label><input type="text" class="form-control" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Kontak</label><input type="text" class="form-control" name="kontak" value="{{ old('kontak', $supplier->kontak) }}" /></div>
      <div class="col-md-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $supplier->email) }}" /></div>
    </div>
    <div class="mb-6"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2">{{ old('alamat', $supplier->alamat) }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
