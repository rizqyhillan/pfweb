@extends('layouts.admin')
@section('title', 'Edit Layanan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Layanan: {{ $service->name }}</h4>
  <a href="{{ route('admin.services.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.services.update', $service) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" class="form-control" name="name" value="{{ old('name', $service->name) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Tipe *</label>
        <select class="form-select" name="type" required>
          @foreach(['consultation','vaccination','grooming','surgery','boarding','other'] as $t)
          <option value="{{ $t }}" {{ old('type', $service->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Harga *</label><input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $service->price) }}" required /></div>
    </div>
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Durasi (menit)</label><input type="number" class="form-control" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" /></div>
      <div class="col-md-3"><label class="form-label">Dokter</label>
        <select class="form-select" name="doctor_id"><option value="">-- Tanpa Dokter --</option>
          @foreach($doctors as $doc)<option value="{{ $doc->id }}" {{ old('doctor_id', $service->doctor_id) == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>@endforeach
        </select>
      </div>
      <div class="col-md-6"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="2">{{ old('description', $service->description) }}</textarea></div>
    </div>
    <div class="mb-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" {{ $service->is_active ? 'checked' : '' }} /><label class="form-check-label">Aktif</label></div></div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
  </form>
</div></div>
@endsection
