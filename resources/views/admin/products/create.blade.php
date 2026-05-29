@extends('layouts.admin')
@section('title', 'Tambah Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Barang</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Barang *</label><input type="text" class="form-control @error('nama_barang') is-invalid @enderror" name="nama_barang" value="{{ old('nama_barang') }}" required />@error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="kategori" value="{{ old('kategori') }}" /></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="harga" value="{{ old('harga') }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Stok *</label><input type="number" class="form-control" name="stok" value="{{ old('stok') }}" required /></div>
      <div class="col-md-3"><label class="form-label">Satuan</label><input type="text" class="form-control" name="satuan" value="{{ old('satuan', 'pcs') }}" /></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea></div>
    </div>
    <div class="row mb-6">
      <div class="col-12">
        <label class="form-label">Variasi Produk</label>
        <div id="variation-container"></div>
        <button type="button" class="btn btn-outline-primary btn-sm" id="add-variation-button">Tambah Variasi</button>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-6">
        <label class="form-label">Gambar Produk</label>
        <input id="image-input" type="file" class="form-control @if($errors->has('images.*')) is-invalid @endif" name="images[]" accept="image/jpeg,image/png" multiple />
        <small class="text-muted">Pilih lebih dari satu gambar jika perlu (Ctrl/Cmd + klik atau Shift untuk memilih banyak). Format: JPG, JPEG, PNG. Maks 2MB per file.</small>
        @if($errors->has('images.*'))<div class="invalid-feedback">{{ $errors->first('images.*') }}</div>@endif
        <div id="image-preview-summary" class="mt-3 text-muted"></div>
        <div id="image-preview-container" class="mt-2 d-flex flex-wrap gap-2"></div>
      </div>
    </div>
    <template id="variation-template">
      <div class="variation-row row gx-2 align-items-end mb-3">
        <div class="col-md-4">
          <label class="form-label">Nama Variasi</label>
          <input type="text" class="form-control" name="variations[__INDEX__][nama_variasi]" placeholder="Misal: Merah" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Harga Variasi</label>
          <input type="number" step="0.01" class="form-control" name="variations[__INDEX__][harga]" placeholder="Opsional" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Stok Variasi</label>
          <input type="number" class="form-control" name="variations[__INDEX__][stok]" placeholder="0" />
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger btn-sm w-100 remove-variation-button">Hapus</button>
        </div>
      </div>
    </template>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('variation-container');
        const template = document.getElementById('variation-template').innerHTML;
        const button = document.getElementById('add-variation-button');
        let variationIndex = 0;

        function addVariationRow(values = {}) {
          const html = template.replace(/__INDEX__/g, variationIndex);
          const wrapper = document.createElement('div');
          wrapper.innerHTML = html;
          const row = wrapper.firstElementChild;

          if (values.nama_variasi) row.querySelector('[name^="variations"][name$="[nama_variasi]"]').value = values.nama_variasi;
          if (values.harga) row.querySelector('[name^="variations"][name$="[harga]"]').value = values.harga;
          if (values.stok) row.querySelector('[name^="variations"][name$="[stok]"]').value = values.stok;

          row.querySelector('.remove-variation-button').addEventListener('click', function () {
            row.remove();
          });

          container.appendChild(row);
          variationIndex++;
        }

        button.addEventListener('click', () => addVariationRow());

        @if(old('variations'))
          const oldVariations = @json(old('variations'));
          oldVariations.forEach(variation => addVariationRow(variation));
        @endif

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
