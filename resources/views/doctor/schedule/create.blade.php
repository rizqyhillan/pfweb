@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Tambah Jadwal Praktek</h4>
  <a href="{{ route('doctor.schedule') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>
<div class="card"><div class="card-body">
  <form action="{{ route('doctor.schedule.store') }}" method="POST">@csrf
    <div class="row mb-6">
      <div class="col-md-3"><label class="form-label">Hari *</label>
        <select class="form-select" name="hari" required>
            <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
            <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa</option>
            <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
            <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
            <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
            <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
            <option value="minggu" {{ old('hari') == 'minggu' ? 'selected' : '' }}>Minggu</option>
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Jam Mulai *</label><input type="time" class="form-control" name="jam_mulai" value="{{ old('jam_mulai', '08:00') }}" required /></div>
      <div class="col-md-3"><label class="form-label">Jam Selesai *</label><input type="time" class="form-control" name="jam_selesai" value="{{ old('jam_selesai', '16:00') }}" required /></div>
      <div class="col-md-3"><label class="form-label">Kuota *</label><input type="number" class="form-control" name="kuota" value="{{ old('kuota', 10) }}" min="1" required /></div>
    </div>
    <div class="mb-6 form-check">
      <input type="checkbox" class="form-check-input" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_aktif">Aktifkan Jadwal Ini</label>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
  </form>
</div></div>
@endsection
