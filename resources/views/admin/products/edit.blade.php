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
      <div class="col-md-6">
        <label class="form-label">Gambar Produk</label>
        @if($product->images->isNotEmpty())
          <div class="mb-3 d-flex flex-wrap gap-2">
            @foreach($product->images as $image)
              <div class="text-center">
                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->nama_barang }}" width="120" height="120" style="object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;" />
              </div>
            @endforeach
          </div>
        @elseif($product->image)
          <div class="mb-2">
            <img src="{{ $product->image_url }}" alt="{{ $product->nama_barang }}" width="120" height="120" style="object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;" />
            <div class="mt-1"><small class="text-muted">Gambar saat ini</small></div>
          </div>
        @endif
        <input type="file" class="form-control @if($errors->has('images.*')) is-invalid @endif" name="images[]" accept="image/jpeg,image/png" multiple />
        <small class="text-muted">Pilih lebih dari satu gambar baru untuk ditambahkan. Format: JPG, JPEG, PNG. Maks 2MB per file.</small>
        @if($errors->has('images.*'))<div class="invalid-feedback">{{ $errors->first('images.*') }}</div>@endif
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
      });
    </script>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_aktif" {{ old('is_aktif', $product->is_aktif) ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
