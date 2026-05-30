@extends('layouts.admin')
@section('title', 'Edit Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Barang</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama Barang *</label><input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="kategori" value="{{ old('kategori', $product->kategori) }}" /></div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="harga" value="{{ old('harga', $product->harga) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Stok *</label><input type="number" class="form-control" name="stok" value="{{ old('stok', $product->stok) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Satuan</label><input type="text" class="form-control" name="satuan" value="{{ old('satuan', $product->satuan) }}" /></div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2">{{ old('deskripsi', $product->deskripsi) }}</textarea></div>
    </div>
    <div class="row mb-6">
      <div class="col-12">
        <label class="form-label">Variasi Produk (misal warna)</label>
        <div id="variation-container">
          @foreach(old('variations', $product->variations->map->toArray()) as $index => $variation)
            <div class="variation-row row gx-2 align-items-end mb-3">
              <div class="col-md-4">
                <label class="form-label">Nama Variasi</label>
                <input type="text" class="form-control" name="variations[{{ $index }}][nama_variasi]" value="{{ $variation['nama_variasi'] ?? '' }}" placeholder="Misal: Merah" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Harga Variasi</label>
                <input type="number" step="0.01" class="form-control" name="variations[{{ $index }}][harga]" value="{{ $variation['harga'] ?? '' }}" placeholder="Opsional" />
              </div>
              <div class="col-md-3">
                <label class="form-label">Stok Variasi</label>
                <input type="number" class="form-control" name="variations[{{ $index }}][stok]" value="{{ $variation['stok'] ?? '' }}" placeholder="0" />
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm w-100 remove-variation-button">Hapus</button>
              </div>
            </div>
          @endforeach
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" id="add-variation-button">Tambah Variasi</button>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-12">
        <label class="form-label">Gambar Produk</label>
        <div class="d-flex gap-4">
          <!-- Gambar Tersimpan -->
          <div class="flex-grow-1">
            <small class="d-block mb-2 text-muted" style="font-size: 12px;">Tersimpan</small>
            <div id="existing-images-container" class="d-flex flex-wrap gap-3">
              @if($product->images->isNotEmpty())
                @foreach($product->images as $image)
                  <div class="existing-image position-relative" data-image-id="{{ $image->id }}" style="width: 90px;">
                    <div style="width: 90px; height: 90px; border: 1.5px solid #639922; border-radius: 8px; overflow: hidden; position: relative;">
                      <button type="button" class="btn btn-sm btn-danger position-absolute delete-existing-image" style="top: 5px; right: 5px; width: 26px; height: 26px; padding: 0; line-height: 1; z-index: 2; font-size: 16px;">×</button>
                      <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->nama_barang }}" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <div style="text-align: center; margin-top: 6px;">
                      <small style="background-color: #C0DD97; color: #27500A; padding: 2px 6px; border-radius: 3px; display: inline-block; font-size: 10px;">✓ tersimpan</small>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <!-- Divider -->
          <div style="width: 1px; background-color: #e0e0e0; min-height: 120px;"></div>

          <!-- Gambar Baru -->
          <div class="flex-grow-1">
            <small class="d-block mb-2 text-muted" style="font-size: 12px;">Akan ditambahkan</small>
            <div id="image-preview-container" class="d-flex flex-wrap gap-3"></div>
          </div>
        </div>

        <div class="mt-3">
          <input id="image-input" type="file" class="form-control @if($errors->has('images.*')) is-invalid @endif" name="images[]" accept="image/jpeg,image/png" multiple />
          <small class="text-muted">Pilih lebih dari satu gambar baru untuk ditambahkan. Format: JPG, JPEG, PNG. Maks 2MB per file.</small>
          @if($errors->has('images.*'))<div class="invalid-feedback">{{ $errors->first('images.*') }}</div>@endif
        </div>
        <input type="hidden" id="deleted-images" name="deleted_images" value="" />
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
        let variationIndex = container.querySelectorAll('.variation-row').length;

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

        container.querySelectorAll('.remove-variation-button').forEach(button => {
          button.addEventListener('click', function () {
            this.closest('.variation-row').remove();
          });
        });

        const imageInput = document.getElementById('image-input');
        const previewContainer = document.getElementById('image-preview-container');
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

          selectedFiles.forEach(file => {
            if (!file.type.startsWith('image/')) {
              return;
            }

            const wrapper = document.createElement('div');
            wrapper.style.width = '90px';

            const item = document.createElement('div');
            item.style.width = '90px';
            item.style.height = '90px';
            item.style.border = '1.5px dashed #BA7517';
            item.style.borderRadius = '8px';
            item.style.overflow = 'hidden';
            item.style.position = 'relative';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.className = 'btn btn-sm btn-danger position-absolute';
            removeButton.style.top = '5px';
            removeButton.style.right = '5px';
            removeButton.style.width = '26px';
            removeButton.style.height = '26px';
            removeButton.style.padding = '0';
            removeButton.style.lineHeight = '1';
            removeButton.style.zIndex = '2';
            removeButton.style.fontSize = '16px';
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

            const badge = document.createElement('div');
            badge.style.textAlign = 'center';
            badge.style.marginTop = '6px';

            const badgeText = document.createElement('small');
            badgeText.textContent = '+ baru';
            badgeText.style.backgroundColor = '#FAC775';
            badgeText.style.color = '#633806';
            badgeText.style.padding = '2px 6px';
            badgeText.style.borderRadius = '3px';
            badgeText.style.display = 'inline-block';
            badgeText.style.fontSize = '10px';

            badge.appendChild(badgeText);

            wrapper.appendChild(item);
            wrapper.appendChild(badge);
            previewContainer.appendChild(wrapper);

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
          renderImagePreview();
          imageInput.addEventListener('change', function () {
            addFiles(this.files);
          });
        }

        const deletedImagesInput = document.getElementById('deleted-images');
        const deletedImageIds = [];

        document.querySelectorAll('.delete-existing-image').forEach(button => {
          button.addEventListener('click', function (e) {
            e.preventDefault();
            const imageElement = this.closest('.existing-image');
            const imageId = imageElement.getAttribute('data-image-id');
            
            if (imageId && !deletedImageIds.includes(imageId)) {
              deletedImageIds.push(imageId);
            }
            
            deletedImagesInput.value = deletedImageIds.join(',');
            imageElement.style.display = 'none';
          });
        });
      });
    </script>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_aktif" {{ old('is_aktif', $product->is_aktif) ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
