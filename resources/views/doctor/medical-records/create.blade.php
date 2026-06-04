@extends('layouts.admin')
@section('title', 'Tambah Rekam Medis')
@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
  <h4 class="mb-0">Tambah Rekam Medis</h4>
  <a href="{{ route('doctor.medical-records') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.medical-records.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row mb-6">
      <div class="col-md-6">
        <label class="form-label">Hewan *</label>
        <select id="hewanSelect" class="form-select @error('id_hewan') is-invalid @enderror" name="id_hewan" required>
          <option value="">-- Pilih Hewan --</option>
          @foreach($pets as $p)
            <option value="{{ $p->id }}" data-owner="{{ $p->owner?->nama ?? '-' }}" {{ old('id_hewan', $selectedPetId ?? '') == $p->id ? 'selected' : '' }}>
              {{ $p->nama_hewan }} - {{ $p->owner?->nama ?? 'Tanpa Pemilik' }}
            </option>
          @endforeach
        </select>
        @error('id_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Pemilik (Otomatis)</label>
        <input type="text" id="ownerDisplay" class="form-control" readonly />
      </div>
    </div>

    <div class="row mb-6">
      <div class="col-md-6">
        <label class="form-label">Dokter</label>
        <input type="text" class="form-control" value="drh. {{ Auth::user()->nama }}" readonly />
        <input type="hidden" name="id_dokter" value="{{ Auth::id() }}" />
      </div>
      <div class="col-md-3">
        <label class="form-label">Berat (kg)</label>
        <input type="number" step="0.01" class="form-control" name="berat_saat_itu" value="{{ old('berat_saat_itu') }}" />
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal *</label>
        <input type="datetime-local" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required />
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Diagnosa</label><textarea class="form-control" name="diagnosa" rows="4">{{ old('diagnosa') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Tindakan</label><textarea class="form-control" name="tindakan" rows="4">{{ old('tindakan') }}</textarea></div>
      <div class="col-md-4"><label class="form-label">Resep Obat</label><textarea class="form-control" name="resep" rows="4">{{ old('resep') }}</textarea></div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
    <div class="mb-6">
      <label class="form-label">Foto-Foto (Bisa lebih dari 1)</label>
      <input id="image-input" type="file" class="form-control" name="fotos[]" multiple accept="image/*" />
      <small class="text-muted">Pilih lebih dari satu gambar jika perlu. Format: JPG, JPEG, PNG.</small>
      <div id="image-preview-summary" class="mt-3 text-muted"></div>
      <div id="image-preview-container" class="mt-2 d-flex flex-wrap gap-2"></div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image-input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewSummary = document.getElementById('image-preview-summary');
        let selectedFiles = [];
        const selectedKeys = new Set();

        function getFileKey(file) {
          return `${file.name}_${file.size}_${file.type}_${file.lastModified}`;
        }

        function updateInputFiles() {
          if (!imageInput) {
            return;
          }

          if (typeof DataTransfer !== 'undefined') {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
          }
        }

        function removeFile(key) {
          selectedFiles = selectedFiles.filter(file => getFileKey(file) !== key);
          selectedKeys.delete(key);
          updateInputFiles();
          renderImagePreview();
        }

        function renderImagePreview() {
          previewContainer.innerHTML = '';
          previewSummary.textContent = '';

          if (selectedFiles.length === 0) {
            previewSummary.textContent = 'Belum ada gambar yang dipilih.';
            return;
          }

          previewSummary.textContent = `${selectedFiles.length} gambar dipilih.`;

          selectedFiles.forEach(file => {
            if (!file.type.startsWith('image/')) {
              return;
            }

            const item = document.createElement('div');
            item.className = 'position-relative border rounded overflow-hidden';
            item.style.width = '120px';
            item.style.height = '120px';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.className = 'btn btn-sm btn-danger position-absolute';
            removeButton.style.top = '5px';
            removeButton.style.right = '5px';
            removeButton.style.width = '28px';
            removeButton.style.height = '28px';
            removeButton.style.padding = '0';
            removeButton.style.lineHeight = '1';
            removeButton.style.zIndex = '2';
            removeButton.addEventListener('click', function () {
              removeFile(getFileKey(file));
            });

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.title = file.name;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';

            item.appendChild(removeButton);
            item.appendChild(img);
            previewContainer.appendChild(item);

            img.onload = function () {
              URL.revokeObjectURL(this.src);
            };
          });
        }

        function addFiles(files) {
          for (let i = 0; i < files.length; i += 1) {
            const file = files[i];
            const key = getFileKey(file);
            if (!file.type.startsWith('image/') || selectedKeys.has(key)) {
              continue;
            }
            selectedFiles.push(file);
            selectedKeys.add(key);
          }

          updateInputFiles();
          renderImagePreview();
        }

        if (imageInput) {
          imageInput.addEventListener('change', function () {
            addFiles(this.files);
          });
          renderImagePreview();
        }
      });
    </script>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    var hewanSelect = $('#hewanSelect');
    var ownerDisplay = $('#ownerDisplay');

    hewanSelect.select2({
      theme: 'bootstrap-5',
      placeholder: '-- Pilih Hewan --',
      allowClear: true,
      minimumResultsForSearch: 0
    }).on('change', function() {
      var selected = this.options[this.selectedIndex];
      ownerDisplay.val(selected ? (selected.dataset.owner || '-') : '-');
    });

    // Initialize owner display on page load
    hewanSelect.trigger('change');
  });
</script>
@endsection
