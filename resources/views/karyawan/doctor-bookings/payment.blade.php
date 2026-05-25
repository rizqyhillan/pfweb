@extends('layouts.admin')

@section('title', 'Pembayaran Booking Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Pembayaran Booking Dokter</h1>
            <p class="text-muted mb-0">Selesaikan transaksi pembayaran untuk layanan konsultasi dokter hewan.</p>
        </div>

        <a href="{{ route('karyawan.doctor-bookings.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Rincian Booking Dokter -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 text-white"><i class="bx bx-info-circle me-2"></i> Rincian Booking Dokter</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="35%">Nama Hewan</th>
                                    <td>: <strong>{{ $doctorBooking->hewan->nama_hewan ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Jenis / Ras</th>
                                    <td>: {{ ucfirst($doctorBooking->hewan->jenis ?? '-') }} / {{ $doctorBooking->hewan->ras ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Pemilik</th>
                                    <td>: {{ $doctorBooking->hewan->owner->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor HP Pemilik</th>
                                    <td>: {{ $doctorBooking->hewan->owner->no_hp ?? '-' }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td colspan="2" class="py-2"></td>
                                </tr>
                                <tr>
                                    <th>Nama Dokter</th>
                                    <td>: <strong>{{ $doctorBooking->dokter->nama ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Layanan Utama</th>
                                    <td>: <span class="badge bg-label-info">{{ $doctorBooking->layanan->nama_layanan ?? 'Konsultasi Dokter' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Hari & Tanggal Booking</th>
                                    <td>: {{ $doctorBooking->tanggal_booking ? $doctorBooking->tanggal_booking->format('d F Y') : '-' }} ({{ ucfirst($doctorBooking->jadwal->hari ?? '-') }})</td>
                                </tr>
                                <tr>
                                    <th>Jam Booking</th>
                                    <td>: {{ $doctorBooking->jam_booking ? \Carbon\Carbon::parse($doctorBooking->jam_booking)->format('H:i') : '-' }} WIB</td>
                                </tr>
                                <tr>
                                    <th>Keluhan Awal</th>
                                    <td>: <em>{{ $doctorBooking->keluhan ?: '-' }}</em></td>
                                </tr>
                                <tr>
                                    <th>Catatan Dokter</th>
                                    <td>: {{ $doctorBooking->catatan_dokter ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Estimasi Biaya Utama</th>
                                    <td>: <strong class="text-primary">Rp {{ number_format($doctorBooking->total_biaya, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pembayaran / Kasir -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="card-title mb-0 text-white"><i class="bx bx-credit-card me-2"></i> Pembayaran Kasir</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('karyawan.doctor-bookings.pay', $doctorBooking) }}" method="POST" id="paymentForm">
                        @csrf

                        <!-- Subtotal / Total Layanan (bisa disesuaikan jika ada tindakan medis tambahan) -->
                        <div class="mb-3">
                            <label for="total_biaya" class="form-label">Biaya Tindakan / Layanan (Rp)</label>
                            <input type="number" name="total_biaya" id="total_biaya" class="form-control text-end fw-bold" 
                                value="{{ old('total_biaya', (int) $doctorBooking->total_biaya) }}" min="0" required oninput="calculateTotal()">
                        </div>

                        <!-- Diskon -->
                        <div class="mb-3">
                            <label for="diskon" class="form-label">Promo / Diskon (Rp)</label>
                            <input type="number" name="diskon" id="diskon" class="form-control text-end" 
                                value="{{ old('diskon', 0) }}" min="0" oninput="calculateTotal()">
                        </div>

                        <!-- Total Akhir (Subtotal - Diskon) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Total Akhir</label>
                            <input type="text" id="display_total" class="form-control form-control-lg text-end fw-bold text-primary" readonly value="Rp 0">
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label for="metode_bayar" class="form-label">Metode Pembayaran</label>
                            <select name="metode_bayar" id="metode_bayar" class="form-select" required>
                                <option value="cash">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>

                        <!-- Uang Bayar -->
                        <div class="mb-3" id="uang_bayar_group">
                            <label for="jumlah_bayar" class="form-label">Jumlah Uang Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control text-end fw-bold" 
                                value="{{ old('jumlah_bayar', (int) $doctorBooking->total_biaya) }}" min="0" required oninput="calculateChange()">
                        </div>

                        <!-- Uang Kembali -->
                        <div class="mb-3" id="kembalian_group">
                            <label class="form-label">Uang Kembali (Kembalian)</label>
                            <input type="text" id="display_kembalian" class="form-control text-end fw-bold text-success" readonly value="Rp 0">
                        </div>

                        <!-- Catatan Transaksi -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan Transaksi</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder="Catatan pembayaran..."></textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="bx bx-check-circle me-1"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        calculateTotal();

        const paymentMethodSelect = document.getElementById('metode_bayar');
        paymentMethodSelect.addEventListener('change', function() {
            const method = this.value;
            const jumlahBayarInput = document.getElementById('jumlah_bayar');
            const totalVal = parseInt(document.getElementById('total_biaya').value || 0) - parseInt(document.getElementById('diskon').value || 0);

            if (method !== 'cash') {
                // Non-cash defaults amount paid to exact total
                jumlahBayarInput.value = Math.max(0, totalVal);
                jumlahBayarInput.readOnly = true;
            } else {
                jumlahBayarInput.readOnly = false;
            }
            calculateChange();
        });
    });

    function calculateTotal() {
        const biaya = parseInt(document.getElementById('total_biaya').value || 0);
        const diskon = parseInt(document.getElementById('diskon').value || 0);
        const total = Math.max(0, biaya - diskon);

        document.getElementById('display_total').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        
        // Auto adjust cash paid if non-cash selected
        const method = document.getElementById('metode_bayar').value;
        if (method !== 'cash') {
            document.getElementById('jumlah_bayar').value = total;
        }

        calculateChange();
    }

    function calculateChange() {
        const biaya = parseInt(document.getElementById('total_biaya').value || 0);
        const diskon = parseInt(document.getElementById('diskon').value || 0);
        const total = Math.max(0, biaya - diskon);

        const bayar = parseInt(document.getElementById('jumlah_bayar').value || 0);
        const kembalian = Math.max(0, bayar - total);

        document.getElementById('display_kembalian').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
    }
</script>
@endsection
