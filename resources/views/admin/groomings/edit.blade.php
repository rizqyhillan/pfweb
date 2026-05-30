@extends('layouts.admin')

@section('title', 'Edit Grooming')

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
    <h4 class="mb-0">Edit Grooming</h4>
    <a href="{{ route('admin.groomings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>
      Kembali</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.groomings.update', $grooming) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-6">
          <div class="col-md-6">
            <label class="form-label">Hewan *</label>
            <select id="hevanSelect" class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan"
              required>
              <option value="">-- Pilih Hewan --</option>
              @foreach($pets as $pet)
                <option value="{{ $pet->id }}" data-owner="{{ $pet->owner?->nama ?? '-' }}"
                  {{ old('id_hewan', $grooming->id_hewan) == $pet->id ? 'selected' : '' }}>
                  {{ $pet->nama_hewan }}
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
                  {{ old('id_paket', $grooming->id_paket) == $pkg->id ? 'selected' : '' }}>
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
              name="tanggal_grooming" value="{{ old('tanggal_grooming', $grooming->tanggal_grooming?->format('Y-m-d')) }}"
              required />
            @error('tanggal_grooming')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-4">
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
              name="total_biaya" value="{{ old('total_biaya', $grooming->total_biaya) }}" step="0.01" min="0" />
            @error('total_biaya')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row mb-6">
          <div class="col-md-4">
            <label class="form-label">Status *</label>
            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
              <option value="pending" {{ old('status', $grooming->status) == 'pending' ? 'selected' : '' }}>Pending
              </option>
              <option value="aktif" {{ old('status', $grooming->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
              <option value="selesai" {{ old('status', $grooming->status) == 'selesai' ? 'selected' : '' }}>Selesai
              </option>
              <option value="batal" {{ old('status', $grooming->status) == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const hevanSelect = document.getElementById('hevanSelect');
      const ownerDisplay = document.getElementById('ownerDisplay');
      const paketSelect = document.getElementById('paketSelect');
      const biayaInput = document.getElementById('biayaInput');

      hevanSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        ownerDisplay.value = selected ? (selected.dataset.owner || '-') : '-';
      });

      paketSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (biayaInput.value == 0 || biayaInput.value == '') {
          biayaInput.value = selected ? (selected.dataset.price || 0) : 0;
        }
      });

      // Initialize on page load
      hevanSelect.dispatchEvent(new Event('change'));
    });
  </script>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#hevanSelect').select2({
      theme: 'bootstrap-5',
      placeholder: '-- Pilih Hewan --',
      allowClear: true
    }).on('change', function() {
      this.dispatchEvent(new Event('change'));
    });
  });
</script>
@endsection
