@extends('layouts.admin')
@section('title', 'Edit Pasien')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Data Pasien (Hewan)</h4>
  <a href="{{ route('doctor.patients') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.patients.update', $pet) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Pemilik *</label>
        <select class="form-select @error('id_pemilik') is-invalid @enderror" name="id_pemilik" required><option value="">-- Pilih --</option>
          @foreach($owners as $o)<option value="{{ $o->id }}" {{ old('id_pemilik', $pet->id_pemilik) == $o->id ? 'selected' : '' }}>{{ $o->nama }} ({{ $o->email }})</option>@endforeach
        </select>@error('id_pemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
      <div class="col-md-6"><label class="form-label">Nama Hewan *</label><input type="text" class="form-control @error('nama_hewan') is-invalid @enderror" name="nama_hewan" value="{{ old('nama_hewan', $pet->nama_hewan) }}" required />@error('nama_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4">
        @include('components.autocomplete', [
          'name' => 'jenis',
          'label' => 'Jenis Hewan *',
          'options' => $types->pluck('name'),
          'value' => old('jenis', $pet->jenis),
          'placeholder' => 'Ketik jenis hewan...',
          'required' => true,
        ])
      </div>
      <div class="col-md-4">
        @include('components.autocomplete', [
          'name' => 'ras',
          'label' => 'Ras',
          'options' => $breeds->pluck('name'),
          'value' => old('ras', $pet->ras),
          'placeholder' => 'Ketik ras hewan...',
          'required' => false,
        ])
      </div>
      <div class="col-md-2"><label class="form-label">Umur (bln)</label><input type="number" class="form-control" name="umur" value="{{ old('umur', $pet->umur) }}" placeholder="2" /></div>
      <div class="col-md-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.01" class="form-control" name="berat" value="{{ old('berat', $pet->berat) }}" /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-12">
        <label class="form-label">Foto Hewan (Kosongkan jika tidak ingin mengubah)</label>
        @if($pet->foto)
          <div class="mb-2">
            <img src="{{ Storage::url($pet->foto) }}" alt="Foto" class="img-thumbnail" style="max-height: 150px;">
          </div>
        @endif
        <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*" />
        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="mb-6"><label class="form-label">Catatan</label><textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $pet->catatan) }}</textarea></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
  </form>
</div></div>
@endsection
