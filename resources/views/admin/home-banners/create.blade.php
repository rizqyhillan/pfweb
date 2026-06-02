@extends('layouts.admin')
@section('title', 'Tambah Banner Home')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Banner Home</h4>
  <a href="{{ route('admin.home-banners.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data">@csrf
    <div class="row mb-6">
  <div class="col-md-6">
    <label class="form-label">Judul</label>
    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $banner->title ?? '') }}" placeholder="Contoh: Promo PawPet" />
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Subjudul</label>
    <input type="text" class="form-control @error('subtitle') is-invalid @enderror" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" placeholder="Opsional" />
    @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
<div class="row mb-6">
  <div class="col-md-6">
    <label class="form-label">Gambar Banner {{ isset($banner) ? '' : '*' }}</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/jpeg,image/png,image/webp" {{ isset($banner) ? '' : 'required' }} />
    <small class="text-muted">Rekomendasi rasio 16:7 atau 1200×525 px. Format JPG/PNG/WebP, maks 3MB.</small>
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @isset($banner)
      <div class="mt-3">
        <img src="{{ $banner->image_url }}" alt="Preview" style="width:260px;height:120px;object-fit:cover;border-radius:12px;" />
      </div>
    @endisset
  </div>
  <div class="col-md-3">
    <label class="form-label">Urutan</label>
    <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" />
    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-2">
      <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Aktif</label>
    </div>
  </div>
</div>
<div class="row mb-6">
  <div class="col-md-6">
    <label class="form-label">Link Tujuan</label>
    <input type="url" class="form-control @error('link_url') is-invalid @enderror" name="link_url" value="{{ old('link_url', $banner->link_url ?? '') }}" placeholder="https://... (opsional)" />
    @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
