@extends('layouts.admin')
@section('title', 'Boarding Baru')

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
  <h4 class="mb-0">Penitipan Baru</h4>
  <a href="{{ route('karyawan.boardings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="card"><div class="card-body">
  <form action="{{ route('karyawan.boardings.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Hewan *</label>
        <select id="hewanSelect" class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan" required><option value="">-- Pilih Hewan --</option>
          @foreach($pets as $pet)<option value="{{ $pet->id }}" {{ old('id_hewan') == $pet->id ? 'selected' : '' }}>{{ $pet->nama_hewan }} ({{ $pet->owner->nama ?? '-' }})</option>@endforeach
        </select>@error('id_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6"><label class="form-label">Kamar *</label>
        <select class="form-select @error('id_kamar') is-invalid @enderror" name="id_kamar" id="id_kamar" required onchange="calculateCost()"><option value="">-- Pilih Kamar --</option>
          @foreach($rooms as $room)<option value="{{ $room->id }}" data-price="{{ $room->harga_per_hari }}" {{ old('id_kamar') == $room->id ? 'selected' : '' }}>{{ $room->nama_kamar }} ({{ ucfirst($room->tipe) }} - Rp {{ number_format($room->harga_per_hari, 0, ',', '.') }}/hari)</option>@endforeach
        </select>@error('id_kamar')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Check-in (Tanggal Masuk) *</label><input type="date" class="form-control" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required onchange="calculateCost()" /></div>
      <div class="col-md-4"><label class="form-label">Rencana Check-out *</label><input type="date" class="form-control" name="tanggal_rencana_keluar" id="tanggal_rencana_keluar" value="{{ old('tanggal_rencana_keluar') }}" required onchange="calculateCost()" /></div>
      <div class="col-md-4">
        <label class="form-label d-flex justify-content-between">
            Total Biaya * 
            <div class="form-check m-0"><input class="form-check-input" type="checkbox" id="manual_cost" onchange="toggleManualCost()"><label class="form-check-label text-muted" style="font-size: 0.8rem">Input Manual</label></div>
        </label>
        <input type="number" step="0.01" class="form-control" name="total_biaya" id="total_biaya" value="{{ old('total_biaya', 0) }}" readonly required />
      </div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan Titip (Drop-off)</label><textarea class="form-control" name="catatan_titip" rows="2">{{ old('catatan_titip') }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>

<script>
function calculateCost() {
    if(document.getElementById('manual_cost').checked) return;
    
    let inDate = new Date(document.getElementById('tanggal_masuk').value);
    let outDate = new Date(document.getElementById('tanggal_rencana_keluar').value);
    let roomSelect = document.getElementById('id_kamar');
    
    if(inDate && outDate && outDate > inDate && roomSelect.value) {
        let diffTime = Math.abs(outDate - inDate);
        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        let price = roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price');
        document.getElementById('total_biaya').value = diffDays * price;
    } else {
        document.getElementById('total_biaya').value = 0;
    }
}
function toggleManualCost() {
    let manual = document.getElementById('manual_cost').checked;
    document.getElementById('total_biaya').readOnly = !manual;
    if(!manual) calculateCost();
}
</script>
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