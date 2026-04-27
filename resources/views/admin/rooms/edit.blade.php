@extends('layouts.admin')
@section('title', 'Edit Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Kamar</h4>
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.rooms.update', $room) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Nama Kamar *</label><input type="text" class="form-control" name="nama_kamar" value="{{ old('nama_kamar', $room->nama_kamar) }}" required /></div>
      <div class="col-md-4"><label class="form-label">Tipe *</label>
        <select class="form-select" name="tipe" required>
          @foreach(['kecil'=>'Kecil','sedang'=>'Sedang','besar'=>'Besar'] as $k=>$v)<option value="{{ $k }}" {{ old('tipe', $room->tipe) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
      <div class="col-md-4"><label class="form-label">Harga/Hari *</label><input type="number" step="0.01" class="form-control" name="harga_per_hari" value="{{ old('harga_per_hari', $room->harga_per_hari) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Kapasitas *</label><input type="number" class="form-control" name="kapasitas" value="{{ old('kapasitas', $room->kapasitas) }}" min="1" required /></div>
      <div class="col-md-4"><label class="form-label">Status *</label>
        <select class="form-select" name="status" required>
          @foreach(['tersedia'=>'Tersedia','terisi'=>'Terisi','maintenance'=>'Maintenance'] as $k=>$v)<option value="{{ $k }}" {{ old('status', $room->status) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
      <div class="col-md-4"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan', $room->keterangan) }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
