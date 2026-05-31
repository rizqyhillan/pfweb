@extends('layouts.admin')

@section('title', 'Tambah Grooming')

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
    <h4 class="mb-0">Grooming Baru</h4>
    <a href="{{ route('admin.groomings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>
      Kembali</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.groomings.store') }}" method="POST">
        @csrf
        <div class="row mb-6">
          <div class="col-md-6">
            <label class="form-label">Hewan *</label>
            <select id="hevanSelect" class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan"
              required>
              <option value="">-- Pilih Hewan --</option>
              @foreach($pets as $pet)
                <option value="{{ $pet->id }}" data-owner="{{ $pet->owner?->nama ?? '-' }}"
                  {{ old('id_hewan') == $pet->id ? 'selected' : '' }}>
                  {{ $pet->nama_hewan }} - {{ $pet->owner?->nama ?? 'Tanpa Pemilik' }}
                </option>
              @endforeach
            </select>
            @error('id_hewan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Pemilik (Otomatis)</label>
            <input type="text" id="ownerDisplay" class="form-control" readonly />
          </div>
        </div>

        <div class="row mb-6">
          <div class="col-md-4">
            <label class="form-label">Paket Grooming *</label>
            <select id="paketSelect" class="form-select @error('id_paket') is-invalid @enderror" name="id_paket"
              required>
              <option value="">-- Pilih Paket --</option>
              @foreach($packages as $pkg)
                <option value="{{ $pkg->id }}" data-price="{{ $pkg->harga_per_malam }}"
                  {{ old('id_paket') == $pkg->id ? 'selected' : '' }}>
                  {{ $pkg->label }}
                </option>
              @endforeach
            </select>
            @error('id_paket')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Tanggal Grooming *</label>
            <input type="date" class="form-control @error('tanggal_grooming') is-invalid @enderror"
              name="tanggal_grooming" value="{{ old('tanggal_grooming') }}" required />
            @error('tanggal_grooming')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Jam Grooming *</label>
            <select class="form-select @error('waktu_grooming') is-invalid @enderror" name="waktu_grooming" required>
                <option value="">-- Pilih Jam --</option>
                @for($jam = 7; $jam <= 16; $jam++)
                @php $value = str_pad($jam, 2, '0', STR_PAD_LEFT) . ':00' @endphp
                <option value="{{ $value }}" {{ old('waktu_grooming') == $value ? 'selected' : '' }}>
                    {{ str_pad($jam, 2, '0', STR_PAD_LEFT) }}.00 WIB
                </option>
                @endfor
            </select>
            @error('waktu_grooming')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>

        <div class="row mb-6">
          <div class="col-md-4">
            <label class="form-label">Biaya (Rp)</label>
            <input id="biayaInput" type="number" class="form-control @error('total_biaya') is-invalid @enderror"
              name="total_biaya" value="{{ old('total_biaya', 0) }}" step="0.01" min="0" />
            @error('total_biaya')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
      </form>
    </div>
  </div>

  <!-- Script removed from content section, now unified in page-js section below -->
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    var hevanSelect = $('#hevanSelect');
    var ownerDisplay = $('#ownerDisplay');
    var paketSelect = $('#paketSelect');
    var biayaInput = $('#biayaInput');

    hevanSelect.select2({
      theme: 'bootstrap-5',
      placeholder: '-- Pilih Hewan --',
      allowClear: true
    }).on('change', function() {
      var selected = this.options[this.selectedIndex];
      ownerDisplay.val(selected ? (selected.dataset.owner || '-') : '-');
    });

    paketSelect.on('change', function() {
      var selected = this.options[this.selectedIndex];
      biayaInput.val(selected ? (selected.dataset.price || 0) : 0);
    });

    // Initialize on page load
    hevanSelect.trigger('change');
    paketSelect.trigger('change');
  });
</script>
@endsection
