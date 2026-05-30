@extends('layouts.admin')

@section('title', 'Edit Booking Dokter')

@section('page-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
  .select2-container--bootstrap-5 .select2-selection {
    border-color: #d9dee3 !important;
  }
  .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    color: #435971 !important;
  }
</style>
@endsection

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
                                    data-dokter-id="{{ $service->id_dokter }}"
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
                                <option 
                                    value="{{ $schedule->id }}" 
                                    data-dokter-id="{{ $schedule->id_dokter }}"
                                    data-hari="{{ strtolower($schedule->hari) }}"
                                    data-jam-mulai="{{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }}"
                                    data-jam-selesai="{{ \Carbon\Carbon::parse($schedule->jam_selesai)->format('H:i') }}"
                                    {{ old('id_jadwal', $doctorBooking->id_jadwal) == $schedule->id ? 'selected' : '' }}
                                >
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
    document.addEventListener('DOMContentLoaded', function () {
        const doctorSelect = document.getElementById('id_dokter');
        const layananSelect = document.getElementById('id_layanan');
        const jadwalSelect = document.getElementById('id_jadwal');
        const totalBiayaInput = document.getElementById('total_biaya');
        const tanggalInput = document.getElementById('tanggal_booking');
        const jamInput = document.getElementById('jam_booking');
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalTanggal = tanggalInput ? tanggalInput.value : '';
        const originalJam = jamInput ? jamInput.value : '';

        if (layananSelect) {
            layananSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected) {
                    const harga = selected.getAttribute('data-harga');
                    if (harga) {
                        totalBiayaInput.value = parseInt(harga);
                    }
                }
            });
        }

        // Function to validate booking time/day against selected schedule
        function validateScheduleTime() {
            if (!tanggalInput || !jamInput || !jadwalSelect) return true;

            // Remove previous error highlights/feedbacks
            document.querySelectorAll('.schedule-feedback').forEach(el => el.remove());
            tanggalInput.classList.remove('is-invalid');
            jamInput.classList.remove('is-invalid');

            const selectedSchedule = jadwalSelect.options[jadwalSelect.selectedIndex];
            if (!selectedSchedule || !selectedSchedule.value) {
                submitBtn.disabled = false;
                return true;
            }

            const schedHari = selectedSchedule.getAttribute('data-hari');
            const schedMulai = selectedSchedule.getAttribute('data-jam-mulai');
            const schedSelesai = selectedSchedule.getAttribute('data-jam-selesai');

            const dateVal = tanggalInput.value;
            const timeVal = jamInput.value;

            let isValid = true;

            if (dateVal) {
                const dateObj = new Date(dateVal);
                const days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
                const dayName = days[dateObj.getDay()];
                if (dayName !== schedHari) {
                    isValid = false;
                    tanggalInput.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block schedule-feedback';
                    feedback.textContent = `Hari tanggal booking harus jatuh pada hari ${schedHari.toUpperCase()} sesuai jadwal yang dipilih.`;
                    tanggalInput.parentNode.appendChild(feedback);
                }
            }

            if (timeVal) {
                if (timeVal < schedMulai || timeVal > schedSelesai) {
                    isValid = false;
                    jamInput.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block schedule-feedback';
                    feedback.textContent = `Jam booking harus berada di antara ${schedMulai} dan ${schedSelesai} sesuai jadwal yang dipilih.`;
                    jamInput.parentNode.appendChild(feedback);
                }
            }

            if (!isValid) {
                submitBtn.disabled = true;
                return false;
            }
            return true;
        }

        // Function to check slot availability
        async function checkSlotAvailability() {
            if (!validateScheduleTime()) {
                return;
            }

            const dokterID = doctorSelect.value;
            const tanggal = tanggalInput.value;
            const jam = jamInput.value;

            // If unchanged, skip checking
            if (tanggal === originalTanggal && jam === originalJam) {
                submitBtn.disabled = false;
                jamInput.classList.remove('is-invalid');
                const feedback = jamInput.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.remove();
                }
                return;
            }

            if (!dokterID || !tanggal || !jam) {
                submitBtn.disabled = false;
                return;
            }

            try {
                const response = await fetch('/api/check-doctor-booking-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({
                        id_dokter: dokterID,
                        tanggal_booking: tanggal,
                        jam_booking: jam,
                    }),
                });

                const data = await response.json();
                
                if (!data.available) {
                    submitBtn.disabled = true;
                    jamInput.classList.add('is-invalid');
                    if (!jamInput.nextElementSibling || !jamInput.nextElementSibling.classList.contains('invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback d-block';
                        feedback.textContent = 'Jam booking tidak tersedia untuk dokter ini pada tanggal dan jam tersebut.';
                        jamInput.parentNode.insertBefore(feedback, jamInput.nextSibling);
                    }
                } else {
                    submitBtn.disabled = false;
                    jamInput.classList.remove('is-invalid');
                    const feedback = jamInput.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.remove();
                    }
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                submitBtn.disabled = false;
            }
        }

        if (doctorSelect && tanggalInput && jamInput && jadwalSelect) {
            doctorSelect.addEventListener('change', () => {
                validateScheduleTime();
                checkSlotAvailability();
            });
            tanggalInput.addEventListener('change', () => {
                validateScheduleTime();
                checkSlotAvailability();
            });
            jamInput.addEventListener('change', () => {
                validateScheduleTime();
                checkSlotAvailability();
            });
            jadwalSelect.addEventListener('change', () => {
                validateScheduleTime();
                checkSlotAvailability();
            });
        }

        if (doctorSelect && layananSelect && jadwalSelect) {
            const originalLayananOptions = Array.from(layananSelect.options);
            const originalJadwalOptions = Array.from(jadwalSelect.options);

            function filterSelect(selectElement, originalOptions, selectedDoctorId, currentSelectedValue) {
                selectElement.innerHTML = '';
                
                const filtered = originalOptions.filter(option => {
                    if (option.value === "") return true;
                    const doctorId = option.getAttribute('data-dokter-id');
                    return !selectedDoctorId || doctorId === selectedDoctorId;
                });

                filtered.forEach(option => {
                    if (option.value === currentSelectedValue) {
                        option.selected = true;
                    } else {
                        option.selected = false;
                    }
                    selectElement.appendChild(option);
                });
            }

            function updateFilters() {
                const doctorId = doctorSelect.value;
                const currentLayanan = layananSelect.value;
                const currentJadwal = jadwalSelect.value;

                filterSelect(layananSelect, originalLayananOptions, doctorId, currentLayanan);
                filterSelect(jadwalSelect, originalJadwalOptions, doctorId, currentJadwal);
            }

            doctorSelect.addEventListener('change', function () {
                // Clear selected values of filtered inputs on doctor change so they don't point to other doctor's values
                layananSelect.value = "";
                jadwalSelect.value = "";
                totalBiayaInput.value = 0;
                updateFilters();
                validateScheduleTime();
                checkSlotAvailability();
            });

            // Initial run on page load
            updateFilters();
            validateScheduleTime();
        }
    });
</script>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#id_hewan').select2({
      theme: 'bootstrap-5',
      placeholder: 'Pilih Hewan',
      allowClear: true
    });
  });
</script>
@endsection