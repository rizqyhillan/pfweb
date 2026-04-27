@extends('layouts.admin')
@section('title', 'Boarding Baru')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Boarding Baru</h4>
    <a href="{{ route('admin.boardings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>
      Kembali</a>
  </div>
  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.boardings.store') }}" method="POST">@csrf
        <div class="row mb-6">
          <div class="col-md-6"><label class="form-label">Hewan *</label>
            <select class="form-select @error('pet_id') is-invalid @enderror" name="pet_id" required>
              <option value="">-- Pilih Hewan --</option>
              @foreach($pets as $pet)<option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
              {{ $pet->name }} ({{ $pet->owner->name ?? '-' }})</option>@endforeach
            </select>@error('pet_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6"><label class="form-label">Kamar *</label>
            <select class="form-select @error('room_id') is-invalid @enderror" name="room_id" required>
              <option value="">-- Pilih Kamar --</option>
              @foreach($rooms as $room)<option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                {{ $room->name }} ({{ ucfirst($room->type) }} - Rp
              {{ number_format($room->price_per_day, 0, ',', '.') }}/hari)</option>@endforeach
            </select>@error('room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-4"><label class="form-label">Check-in *</label><input type="date" class="form-control"
              name="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" required /></div>
          <div class="col-md-4"><label class="form-label">Rencana Check-out *</label><input type="date"
              class="form-control" name="planned_check_out_date" value="{{ old('planned_check_out_date') }}" required />
          </div>
          <div class="col-md-4"><label class="form-label">Total Biaya *</label><input type="number" step="0.01"
              class="form-control" name="total_cost" value="{{ old('total_cost', 0) }}" required /></div>
        </div>
        <div class="mb-6"><label class="form-label">Catatan Drop-off</label><textarea class="form-control"
            name="drop_off_notes" rows="2">{{ old('drop_off_notes') }}</textarea></div>
        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
      </form>
    </div>
  </div>
@endsection