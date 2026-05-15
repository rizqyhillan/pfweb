@extends('layouts.admin')

@section('title', 'Edit Jadwal Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Jadwal Dokter</h1>
            <p class="text-muted mb-0">Perbarui hari dan jam praktik dokter.</p>
        </div>

        <a href="{{ route('admin.doctor-schedules.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.doctor-schedules.update', $doctorSchedule) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_dokter" class="form-label">Dokter</label>
                    <select name="id_dokter" id="id_dokter" class="form-control" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('id_dokter', $doctorSchedule->id_dokter) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $doctorSchedule->hari) == $hari ? 'selected' : '' }}>
                                {{ ucfirst($hari) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input 
                            type="time" 
                            name="jam_mulai" 
                            id="jam_mulai" 
                            class="form-control" 
                            value="{{ old('jam_mulai', \Carbon\Carbon::parse($doctorSchedule->jam_mulai)->format('H:i')) }}" 
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input 
                            type="time" 
                            name="jam_selesai" 
                            id="jam_selesai" 
                            class="form-control" 
                            value="{{ old('jam_selesai', \Carbon\Carbon::parse($doctorSchedule->jam_selesai)->format('H:i')) }}" 
                            required
                        >
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input type="hidden" name="is_aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $doctorSchedule->is_aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.doctor-schedules.index') }}" class="btn btn-light">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection