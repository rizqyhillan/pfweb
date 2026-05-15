@extends('layouts.admin')

@section('title', 'Edit Booking Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Booking Dokter</h1>
            <p class="text-muted mb-0">Perbarui data booking konsultasi dokter.</p>
        </div>

        <a href="{{ route('admin.doctor-bookings.index') }}" class="btn btn-secondary">
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
            <form action="{{ route('admin.doctor-bookings.update', $doctorBooking) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_hewan" class="form-label">Hewan</label>
                        <select name="id_hewan" id="id_hewan" class="form-control" required>
                            <option value="">Pilih Hewan</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}" {{ old('id_hewan', $doctorBooking->id_hewan) == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->nama_hewan }} - {{ $pet->owner->nama ?? 'Tanpa Pemilik' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_dokter" class="form-label">Dokter</label>
                        <select name="id_dokter" id="id_dokter" class="form-control" required>
                            <option value="">Pilih Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('id_dokter', $doctorBooking->id_dokter) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_layanan" class="form-label">Layanan</label>
                        <select name="id_layanan" id="id_layanan" class="form-control">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option 
                                    value="{{ $service->id }}" 
                                    data-harga="{{ $service->harga }}"
                                    {{ old('id_layanan', $doctorBooking->id_layanan) == $service->id ? 'selected' : '' }}
                                >
                                    {{ $service->nama_layanan }} - Rp {{ number_format($service->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_jadwal" class="form-label">Jadwal Dokter</label>
                        <select name="id_jadwal" id="id_jadwal" class="form-control">
                            <option value="">Pilih Jadwal</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('id_jadwal', $doctorBooking->id_jadwal) == $schedule->id ? 'selected' : '' }}>
                                    {{ ucfirst($schedule->hari) }} - 
                                    {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} sampai 
                                    {{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i') }}
                                    | {{ $schedule->dokter->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_booking" class="form-label">Tanggal Booking</label>
                        <input 
                            type="date" 
                            name="tanggal_booking" 
                            id="tanggal_booking" 
                            class="form-control"
                            value="{{ old('tanggal_booking', optional($doctorBooking->tanggal_booking)->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_booking" class="form-label">Jam Booking</label>
                        <input 
                            type="time" 
                            name="jam_booking" 
                            id="jam_booking" 
                            class="form-control"
                            value="{{ old('jam_booking', \Carbon\Carbon::parse($doctorBooking->jam_booking)->format('H:i')) }}"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending" {{ old('status', $doctorBooking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikonfirmasi" {{ old('status', $doctorBooking->status) == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="selesai" {{ old('status', $doctorBooking->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ old('status', $doctorBooking->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="total_biaya" class="form-label">Total Biaya</label>
                        <input 
                            type="number" 
                            name="total_biaya" 
                            id="total_biaya" 
                            class="form-control"
                            value="{{ old('total_biaya', $doctorBooking->total_biaya) }}"
                            min="0"
                            required
                        >
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="keluhan" class="form-label">Keluhan</label>
                        <textarea 
                            name="keluhan" 
                            id="keluhan" 
                            rows="3" 
                            class="form-control"
                        >{{ old('keluhan', $doctorBooking->keluhan) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="catatan_dokter" class="form-label">Catatan Dokter</label>
                        <textarea 
                            name="catatan_dokter" 
                            id="catatan_dokter" 
                            rows="3" 
                            class="form-control"
                        >{{ old('catatan_dokter', $doctorBooking->catatan_dokter) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.doctor-bookings.index') }}" class="btn btn-light">
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

<script>
    const layananSelect = document.getElementById('id_layanan');
    const totalBiayaInput = document.getElementById('total_biaya');

    layananSelect?.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const harga = selected.getAttribute('data-harga');

        if (harga) {
            totalBiayaInput.value = harga;
        }
    });
</script>
@endsection