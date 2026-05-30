@extends('layouts.admin')
@section('title', 'Tambah Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Kamar</h4>
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Nama Kamar *</label><input type="text" class="form-control @error('nama_kamar') is-invalid @enderror" name="nama_kamar" value="{{ old('nama_kamar') }}" required />@error('nama_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-4"><label class="form-label">Paket *</label>
        <select id="paketSelect" class="form-select @error('paket') is-invalid @enderror" name="paket" required>
          @foreach($packageTypes as $key => $label)
            <option value="{{ $key }}" {{ old('paket') == $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
        @error('paket')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4"><label class="form-label">Harga/Hari *</label><input id="hargaPerHari" type="number" step="0.01" class="form-control @error('harga_per_hari') is-invalid @enderror" name="harga_per_hari" value="{{ old('harga_per_hari') }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Kapasitas *</label><input type="number" class="form-control" name="kapasitas" value="{{ old('kapasitas', 1) }}" min="1" required /></div>
      <div class="col-md-8"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea></div>
    </div>
    <div class="row mb-6">
      <div class="col-12">
        <label class="form-label">Foto Kamar</label>
        <input type="file" class="form-control @error('foto_kamar') is-invalid @enderror @error('foto_kamar.*') is-invalid @enderror" name="foto_kamar[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple>
        <div class="form-text">Bisa pilih beberapa foto sekaligus. Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB per foto.</div>
        @error('foto_kamar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('foto_kamar.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const prices = @json($packagePrices);
    const paketSelect = document.getElementById('paketSelect');
    const hargaInput = document.getElementById('hargaPerHari');

    if (paketSelect && hargaInput) {
      paketSelect.addEventListener('change', function () {
        const selected = this.value;
        if (prices[selected] !== undefined) {
          hargaInput.value = prices[selected];
        }
      });
    }
  });
</script>
@endsection
