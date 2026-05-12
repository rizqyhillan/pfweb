@extends('layouts.admin')
@section('title', 'Tambah Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Kamar</h4>
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.rooms.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Nama Kamar *</label><input type="text" class="form-control @error('nama_kamar') is-invalid @enderror" name="nama_kamar" value="{{ old('nama_kamar') }}" required />@error('nama_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-4"><label class="form-label">Paket *</label>
        <select class="form-select" name="paket" required>
          @foreach(\App\Models\Room::paketOptions() as $k=>$v)<option value="{{ $k }}" {{ old('paket') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
      <div class="col-md-4"><label class="form-label">Harga/Hari *</label><input type="number" step="0.01" class="form-control" name="harga_per_hari" value="{{ old('harga_per_hari', 0) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Kapasitas *</label><input type="number" class="form-control" name="kapasitas" value="{{ old('kapasitas', 1) }}" min="1" required /></div>
      <div class="col-md-8"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
