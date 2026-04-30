@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Pengguna</h4>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.users.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required />@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required />@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Password *</label><input type="password" class="form-control" name="password" required /></div>
      <div class="col-md-4"><label class="form-label">Konfirmasi Password</label><input type="password" class="form-control" name="password_confirmation" /></div>
      <div class="col-md-4"><label class="form-label">Role *</label>
        <select class="form-select" name="role" required>
          @foreach(['admin'=>'Admin','dokter'=>'Dokter','karyawan'=>'Karyawan'] as $k=>$v)<option value="{{ $k }}" {{ old('role') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">No. HP</label><input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}" /></div>
      <div class="col-md-8"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2">{{ old('alamat') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
