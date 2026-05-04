@extends('layouts.admin')
@section('title', 'Edit Jadwal')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Edit Jadwal Praktek</h4>
  <a href="{{ route('doctor.schedule') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.schedule.update', $schedule) }}" method="POST">@csrf @method('PUT')
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Hari *</label>
        <select class="form-select" name="hari" required>
            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $h)
            <option value="{{ $h }}" {{ old('hari', $schedule->hari) == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Jam Mulai *</label><input type="time" class="form-control" name="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i')) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Jam Selesai *</label><input type="time" class="form-control" name="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i')) }}" required /></div>
      <div class="col-md-3"><label class="form-label">Kuota *</label><input type="number" class="form-control" name="kuota" value="{{ old('kuota', $schedule->kuota) }}" min="1" required /></div>
    </div>
    <div class="mb-6 form-check">
      <input type="checkbox" class="form-check-input" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', $schedule->is_aktif) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_aktif">Aktifkan Jadwal Ini</label>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
  </form>
</div></div>
@endsection
