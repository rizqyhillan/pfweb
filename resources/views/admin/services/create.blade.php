@extends('layouts.admin')
@section('title', 'Tambah Layanan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Layanan</h4>
  <a href="{{ route('admin.services.index', request()->only('jenis_layanan')) }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.services.store') }}" method="POST">@csrf
    <input type="hidden" name="jenis_layanan" value="{{ request('jenis_layanan') }}" />
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Layanan *</label><input type="text" class="form-control @error('nama_layanan') is-invalid @enderror" name="nama_layanan" value="{{ old('nama_layanan') }}" required />@error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Jenis *</label>
        <select class="form-select" name="jenis_layanan" required>
          @foreach(['konsultasi'=>'Konsultasi','vaksinasi'=>'Vaksinasi','grooming'=>'Grooming','operasi'=>'Operasi','penitipan'=>'Penitipan','lainnya'=>'Lainnya'] as $k=>$v)<option value="{{ $k }}" {{ old('jenis_layanan', request('jenis_layanan')) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="harga" value="{{ old('harga', 0) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Durasi (menit)</label><input type="number" class="form-control" name="durasi_menit" value="{{ old('durasi_menit') }}" /></div>
      <div class="col-md-3"><label class="form-label">Dokter</label>
        <select class="form-select" name="id_dokter"><option value="">-- Tanpa Dokter --</option>
          @foreach($doctors as $d)<option value="{{ $d->id }}" {{ old('id_dokter') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>@endforeach
        </select></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
