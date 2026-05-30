@extends('layouts.admin')

@section('title', 'Tambah Boarding')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
  .select2-container--bootstrap-5 .select2-selection {
    border-color: #d9dee3 !important;
  }
  .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    color: #435971 !important;
  }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Boarding</h4>
  <a href="{{ route('admin.boardings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.boardings.store') }}" method="POST">
      @csrf
      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label">Hewan *</label>
          <select id="hewanSelect" class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan" required>
            <option value="">-- Pilih Hewan --</option>
            @foreach($pets as $pet)
              <option value="{{ $pet->id }}" {{ old('id_hewan') == $pet->id ? 'selected' : '' }}>
                {{ $pet->nama_hewan }} - {{ $pet->owner->nama ?? 'Owner tidak ditemukan' }}
              </option>
            @endforeach
          </select>
          @error('id_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Kamar *</label>
          <select class="form-select @error('id_kamar') is-invalid @enderror" name="id_kamar" required>
            <option value="">-- Pilih Kamar --</option>
            @foreach($rooms as $room)
              <option value="{{ $room->id }}" {{ old('id_kamar') == $room->id ? 'selected' : '' }}>
                {{ $room->nama_kamar }} ({{ $room->paket_label }} - Rp {{ number_format($room->harga_per_hari, 0, ',', '.') }}/hari)
              </option>
            @endforeach
          </select>
          @error('id_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label">Tanggal Masuk *</label>
          <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
            name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required />
          @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Tanggal Rencana Keluar *</label>
          <input type="date" class="form-control @error('tanggal_rencana_keluar') is-invalid @enderror"
            name="tanggal_rencana_keluar" value="{{ old('tanggal_rencana_keluar') }}" required />
          @error('tanggal_rencana_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label">Total Biaya</label>
          <input type="number" step="0.01" class="form-control @error('total_biaya') is-invalid @enderror"
            name="total_biaya" value="{{ old('total_biaya') }}"
            placeholder="Biarkan kosong untuk hitung otomatis" />
          <small class="text-muted">Biarkan kosong untuk menghitung otomatis berdasarkan harga kamar × jumlah hari</small>
          @error('total_biaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Catatan Titipan</label>
          <textarea class="form-control @error('catatan_titip') is-invalid @enderror"
            name="catatan_titip" rows="3" placeholder="Catatan khusus untuk penitipan hewan">{{ old('catatan_titip') }}</textarea>
          @error('catatan_titip')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> Simpan Boarding
      </button>
    </form>
  </div>
</div>

@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#hewanSelect').select2({
      theme: 'bootstrap-5',
      placeholder: '-- Pilih Hewan --',
      allowClear: true
    });
  });
</script>
@endsection