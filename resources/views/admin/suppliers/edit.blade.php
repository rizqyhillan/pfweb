@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Supplier: {{ $supplier->name }}</h4>
  <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control" name="name" value="{{ old('name', $supplier->name) }}" required /></div>
      <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $supplier->email) }}" /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Kontak</label><input type="text" class="form-control" name="contact" value="{{ old('contact', $supplier->contact) }}" /></div>
      <div class="col-md-6"><label class="form-label">Alamat</label><textarea class="form-control" name="address" rows="2">{{ old('address', $supplier->address) }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
  </form>
</div></div>
@endsection
