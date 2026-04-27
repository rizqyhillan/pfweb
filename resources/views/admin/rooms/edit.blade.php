@extends('layouts.admin')
@section('title', 'Edit Kamar')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Kamar: {{ $room->name }}</h4>
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('admin.rooms.update', $room) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Nama *</label><input type="text" class="form-control" name="name" value="{{ old('name', $room->name) }}" required /></div>
      <div class="col-md-4"><label class="form-label">Tipe *</label>
        <select class="form-select" name="type" required>
          @foreach(['small','medium','large'] as $t)<option value="{{ $t }}" {{ old('type', $room->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>@endforeach
        </select>
      </div>
      <div class="col-md-4"><label class="form-label">Status *</label>
        <select class="form-select" name="status" required>
          @foreach(['available'=>'Tersedia','occupied'=>'Terisi','maintenance'=>'Maintenance'] as $k=>$v)<option value="{{ $k }}" {{ old('status', $room->status) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
        </select>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-4"><label class="form-label">Harga/Hari *</label><input type="number" step="0.01" class="form-control" name="price_per_day" value="{{ old('price_per_day', $room->price_per_day) }}" required /></div>
      <div class="col-md-4"><label class="form-label">Kapasitas *</label><input type="number" class="form-control" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" required /></div>
      <div class="col-md-4"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="2">{{ old('description', $room->description) }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
  </form>
</div></div>
@endsection
