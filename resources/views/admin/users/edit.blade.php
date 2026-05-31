@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">{{ request('role') === 'dokter' ? 'Edit Dokter' : 'Edit Pengguna' }}</h4>
  <a href="{{ request('role') ? route('admin.users.role', ['role' => request('role')]) : route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.users.update', [$user] + (request('role') ? ['role' => request('role')] : [])) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" required />@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required />@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4">
        <label class="form-label">Password (kosongkan jika tidak ganti)</label>
        <div class="position-relative">
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" style="padding-right: 40px;" />
          <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none text-muted toggle-password" style="z-index: 10; border: none; background: transparent; padding-right: 12px;" tabindex="-1">
            <i class="bx bx-hide" style="font-size: 1.25rem;"></i>
          </button>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Konfirmasi Password Baru</label>
        <div class="position-relative">
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" style="padding-right: 40px;" />
          <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none text-muted toggle-password" style="z-index: 10; border: none; background: transparent; padding-right: 12px;" tabindex="-1">
            <i class="bx bx-hide" style="font-size: 1.25rem;"></i>
          </button>
        </div>
      </div>
      <div class="col-md-4"><label class="form-label">Role *</label>
        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
          @foreach(['admin'=>'Admin','dokter'=>'Dokter','karyawan'=>'Karyawan'] as $k=>$v)<option value="{{ $k }}" {{ old('role', $user->role) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select>@error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">No. HP</label><input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />@error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Alamat</label><textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2">{{ old('alamat', $user->alamat) }}</textarea>@error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-2"><label class="form-label">Status</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_aktif" {{ old('is_aktif', $user->is_aktif) ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
