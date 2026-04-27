@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit User: {{ $user->name }}</h4>
  <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.users.update', $user) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required /></div>
      <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Password (kosongkan jika tidak diubah)</label><input type="password" class="form-control @error('password') is-invalid @enderror" name="password" />@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-4"><label class="form-label">Konfirmasi Password</label><input type="password" class="form-control" name="password_confirmation" /></div>
      <div class="col-md-4"><label class="form-label">Role *</label>
        <select class="form-select" name="role" required>
          @foreach(['admin','doctor','owner','cashier'] as $r)<option value="{{ $r }}" {{ old('role', $user->role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>@endforeach
        </select>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Telepon</label><input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" /></div>
      <div class="col-md-8"><label class="form-label">Alamat</label><textarea class="form-control" name="address" rows="2">{{ old('address', $user->address) }}</textarea></div>
    </div>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" {{ $user->is_active ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
  </form>
</div></div>
@endsection
