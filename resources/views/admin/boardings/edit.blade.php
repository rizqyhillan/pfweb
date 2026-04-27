@extends('layouts.admin')
@section('title', 'Edit Boarding')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Edit Boarding</h4>
    <a href="{{ route('admin.boardings.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>
      Kembali</a>
  </div>
  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.boardings.update', $boarding) }}" method="POST">@csrf @method('PUT')
        <div class="row mb-6">
          <div class="col-md-6"><label class="form-label">Hewan *</label>
            <select class="form-select" name="pet_id" required>
              @foreach($pets as $pet)<option value="{{ $pet->id }}" {{ old('pet_id', $boarding->pet_id) == $pet->id ? 'selected' : '' }}>{{ $pet->name }} ({{ $pet->owner->name ?? '-' }})</option>@endforeach
            </select>
          </div>
          <div class="col-md-6"><label class="form-label">Kamar *</label>
            <select class="form-select" name="room_id" required>
              @foreach($rooms as $room)<option value="{{ $room->id }}" {{ old('room_id', $boarding->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }} ({{ ucfirst($room->type) }})</option>@endforeach
            </select>
          </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-3"><label class="form-label">Check-in *</label><input type="date" class="form-control"
              name="check_in_date" value="{{ old('check_in_date', $boarding->check_in_date?->format('Y-m-d')) }}"
              required /></div>
          <div class="col-md-3"><label class="form-label">Plan Check-out *</label><input type="date" class="form-control"
              name="planned_check_out_date"
              value="{{ old('planned_check_out_date', $boarding->planned_check_out_date?->format('Y-m-d')) }}" required />
          </div>
          <div class="col-md-3"><label class="form-label">Check-out Aktual</label><input type="date" class="form-control"
              name="check_out_date" value="{{ old('check_out_date', $boarding->check_out_date?->format('Y-m-d')) }}" />
          </div>
          <div class="col-md-3"><label class="form-label">Status *</label>
            <select class="form-select" name="status" required>
              @foreach(['active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $k => $v)<option
              value="{{ $k }}" {{ old('status', $boarding->status) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-4"><label class="form-label">Total Biaya *</label><input type="number" step="0.01"
              class="form-control" name="total_cost" value="{{ old('total_cost', $boarding->total_cost) }}" required />
          </div>
          <div class="col-md-4"><label class="form-label">Catatan Drop-off</label><textarea class="form-control"
              name="drop_off_notes" rows="2">{{ old('drop_off_notes', $boarding->drop_off_notes) }}</textarea></div>
          <div class="col-md-4"><label class="form-label">Catatan Pick-up</label><textarea class="form-control"
              name="pick_up_notes" rows="2">{{ old('pick_up_notes', $boarding->pick_up_notes) }}</textarea></div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
      </form>
    </div>
  </div>
@endsection