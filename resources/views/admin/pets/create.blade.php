@extends('layouts.admin')
@section('title', 'Tambah Hewan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Hewan Peliharaan</h4>
  <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.pets.store') }}" method="POST">
      @csrf
      <div class="row mb-6">
        <div class="col-md-6">
          <label class="form-label" for="owner_id">Pemilik <span class="text-danger">*</span></label>
          <select class="form-select @error('owner_id') is-invalid @enderror" id="owner_id" name="owner_id" required>
            <option value="">-- Pilih Pemilik --</option>
            @foreach($owners as $owner)
              <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }} ({{ $owner->email }})</option>
            @endforeach
          </select>
          @error('owner_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label" for="name">Nama Hewan <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required />
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="row mb-6">
        <div class="col-md-4">
          <label class="form-label" for="species">Spesies <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('species') is-invalid @enderror" id="species" name="species" value="{{ old('species') }}" placeholder="Kucing, Anjing, dll" required />
          @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label" for="breed">Ras</label>
          <input type="text" class="form-control" id="breed" name="breed" value="{{ old('breed') }}" />
        </div>
        <div class="col-md-2">
          <label class="form-label" for="age">Umur</label>
          <input type="text" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="2 tahun" />
        </div>
        <div class="col-md-2">
          <label class="form-label" for="weight">Berat (kg)</label>
          <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" />
        </div>
      </div>
      <div class="mb-6">
        <label class="form-label" for="notes">Catatan</label>
        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
    </form>
  </div>
</div>
@endsection
