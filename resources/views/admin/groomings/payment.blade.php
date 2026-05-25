@extends('layouts.admin')

@section('title', 'Pembayaran Grooming')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Pembayaran Grooming</h1>
            <p class="text-muted mb-0">Selesaikan transaksi pembayaran untuk layanan grooming.</p>
        </div>

        <a href="{{ route('admin.groomings.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Rincian Grooming -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 text-white"><i class="bx bx-info-circle me-2"></i> Rincian Booking Grooming</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="35%">Nama Hewan</th>
                                    <td>: <strong>{{ $grooming->hewan->nama_hewan ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Jenis / Ras</th>
                                    <td>: {{ ucfirst($grooming->hewan->jenis ?? '-') }} / {{ $grooming->hewan->ras ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Pemilik</th>
                                    <td>: {{ $grooming->hewan->owner->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor HP Pemilik</th>
                                    <td>: {{ $grooming->hewan->owner->no_hp ?? '-' }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td colspan="2" class="py-2"></td>
                                </tr>
                                <tr>
                                    <th>Paket Grooming</th>
                                    <td>: <span class="badge bg-label-success">{{ $grooming->paket->label ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Deskripsi Paket</th>
                                    <td>: <small class="text-muted">{{ $grooming->paket->description ?? '-' }}</small></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Grooming</th>
                                    <td>: {{ $grooming->tanggal_grooming ? $grooming->tanggal_grooming->format('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Grooming</th>
                                    <td>: {{ $grooming->waktu_grooming ? \Carbon\Carbon::parse($grooming->waktu_grooming)->format('H:i') : '-' }} WIB</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>: {{ $grooming->catatan_grooming ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Estimasi Biaya Awal</th>
                                    <td>: <strong class="text-primary">Rp {{ number_format($grooming->total_biaya, 0, ',', '.') }}</strong></td>
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
                    <form action="{{ route('admin.groomings.pay', $grooming) }}" method="POST" id="paymentForm">
                        @csrf

                        <!-- Subtotal / Total Layanan (bisa disesuaikan jika ada biaya tambahan) -->
                        <div class="mb-3">
                            <label for="total_biaya" class="form-label">Biaya Layanan (Rp)</label>
                            <input type="number" name="total_biaya" id="total_biaya" class="form-control text-end fw-bold" 
                                value="{{ old('total_biaya', (int) $grooming->total_biaya) }}" min="0" required oninput="calculateTotal()">
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
                                value="{{ old('jumlah_bayar', (int) $grooming->total_biaya) }}" min="0" required oninput="calculateChange()">
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
