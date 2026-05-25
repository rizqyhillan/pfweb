@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">{{ request('role') === 'dokter' ? 'Tambah Dokter' : 'Tambah Pengguna' }}</h4>
  <a href="{{ request('role') ? route('admin.users.role', ['role' => request('role')]) : route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.users.store', request('role') ? ['role' => request('role')] : []) }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required />@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required />@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Password *</label><input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required />@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-4"><label class="form-label">Konfirmasi Password *</label><input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required /></div>
      <div class="col-md-4"><label class="form-label">Role *</label>
        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
          @foreach(['admin'=>'Admin','dokter'=>'Dokter','karyawan'=>'Karyawan'] as $k=>$v)<option value="{{ $k }}" {{ old('role', request('role')) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select>@error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">No. HP</label><input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />@error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-8"><label class="form-label">Alamat</label><textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2">{{ old('alamat') }}</textarea>@error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
