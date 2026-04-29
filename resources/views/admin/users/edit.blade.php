@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Pengguna</h4>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.users.update', $user) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control" name="nama" value="{{ old('nama', $user->nama) }}" required /></div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Password (kosongkan jika tidak ganti)</label><input type="password" class="form-control" name="password" /></div>
      <div class="col-md-4"><label class="form-label">Role *</label>
        <select class="form-select" name="role" required>
          @foreach(['admin'=>'Admin','doctor'=>'Doctor','karyawan'=>'Karyawan'] as $k=>$v)<option value="{{ $k }}" {{ old('role', $user->role) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
      <div class="col-md-4"><label class="form-label">No. HP</label><input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-8"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2">{{ old('alamat', $user->alamat) }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Status</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_aktif" {{ old('is_aktif', $user->is_aktif) ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
