@extends('layouts.admin')

@section('title', 'Booking Masuk')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="card-title mb-2">Booking Konsultasi Masuk 📅</h5>
            <p class="card-text text-muted mb-0">
              Kelola dan pantau semua janji temu pasien yang masuk khusus untuk Anda.
            </p>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bx bx-home-alt me-1"></i> Dashboard
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Left Side: Statistics & Date Grouping (Kolom Hari) -->
  <div class="col-lg-4 mb-4">
    <!-- Summary Card -->
    <div class="card mb-4">
      <div class="card-header bg-label-primary py-3">
        <h6 class="m-0 text-primary"><i class="bx bx-pie-chart-alt-2 me-1"></i> Ringkasan Booking</h6>
      </div>
      <div class="card-body pt-3">
        <div class="d-flex align-items-center mb-3">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-time icon-md"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Menunggu (Pending)</h6>
            </div>
            <span class="badge bg-warning">{{ $bookings->where('status', 'pending')->count() }}</span>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-calendar icon-md"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Dikonfirmasi</h6>
            </div>
            <span class="badge bg-info">{{ $bookings->where('status', 'dikonfirmasi')->count() }}</span>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-double icon-md"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Selesai</h6>
            </div>
            <span class="badge bg-success">{{ $bookings->where('status', 'selesai')->count() }}</span>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-x icon-md"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Dibatalkan</h6>
            </div>
            <span class="badge bg-danger">{{ $bookings->where('status', 'batal')->count() }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Grouped Days Column (Kolom Hari Booking) -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center py-3 border-bottom">
        <h6 class="m-0"><i class="bx bx-calendar-event me-1"></i> Kolom Hari Booking</h6>
        @if($filterDate)
          <a href="{{ route('doctor.bookings', ['status' => $filterStatus]) }}" class="btn btn-xs btn-outline-danger">Reset Hari</a>
        @endif
      </div>
      <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
        @if($bookingCountsByDate->isEmpty())
          <div class="text-center text-muted py-4">
            <small>Belum ada jadwal booking aktif</small>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach($bookingCountsByDate as $countData)
              @php
                $carbonDate = \Carbon\Carbon::parse($countData->tanggal_booking);
                $isFiltered = $filterDate == $countData->tanggal_booking->format('Y-m-d');
              @endphp
              <a href="{{ route('doctor.bookings', ['tanggal' => $countData->tanggal_booking->format('Y-m-d'), 'status' => $filterStatus]) }}" 
                 class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 {{ $isFiltered ? 'active bg-label-primary text-primary' : '' }}">
                <div>
                  <h6 class="mb-0 {{ $isFiltered ? 'text-primary font-weight-bold' : '' }}">
                    {{ $carbonDate->locale('id')->isoFormat('dddd') }}, {{ $carbonDate->format('d M Y') }}
                  </h6>
                  @if($carbonDate->isToday())
                    <span class="badge bg-label-success btn-xs mt-1">Hari Ini</span>
                  @elseif($carbonDate->isTomorrow())
                    <span class="badge bg-label-info btn-xs mt-1">Besok</span>
                  @endif
                </div>
                <span class="badge {{ $isFiltered ? 'bg-primary text-white' : 'bg-label-primary' }} rounded-pill">
                  {{ $countData->total }} Pasien
                </span>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Right Side: Detailed Booking List -->
  <div class="col-lg-8 mb-4">
    <!-- Filter Card -->
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('doctor.bookings') }}" class="row g-3 align-items-end">
          @if($filterDate)
            <input type="hidden" name="tanggal" value="{{ $filterDate }}">
          @endif
          <div class="col-md-5">
            <label class="form-label">Filter Status</label>
            <select name="status" class="form-select form-select-sm">
              <option value="">Semua Status</option>
              <option value="pending" {{ $filterStatus == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
              <option value="dikonfirmasi" {{ $filterStatus == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
              <option value="selesai" {{ $filterStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="batal" {{ $filterStatus == 'batal' ? 'selected' : '' }}>Batal (Dibatalkan)</option>
            </select>
          </div>
          <div class="col-md-4">
            @if(!$filterDate)
              <label class="form-label">Filter Tanggal Mandiri</label>
              <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
            @else
              <div class="text-muted mb-2">
                <small>Memfilter hari: <strong>{{ \Carbon\Carbon::parse($filterDate)->locale('id')->isoFormat('dddd, d M Y') }}</strong></small>
              </div>
            @endif
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bx bx-filter-alt me-1"></i> Filter</button>
            <a href="{{ route('doctor.bookings') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center py-3 border-bottom">
        <h5 class="mb-0">Daftar Pasien</h5>
        <span class="text-muted small">Menampilkan {{ $bookings->count() }} Data Booking</span>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Pasien / Pemilik</th>
              <th>Layanan</th>
              <th>Keluhan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse($bookings as $booking)
              <tr>
                <td>
                  <strong>{{ $booking->jam_booking ? \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') : '-' }}</strong><br>
                  <small class="text-muted">{{ $booking->tanggal_booking->format('d/m/Y') }}</small>
                </td>
                <td>
                  <strong>{{ $booking->hewan->nama_hewan ?? '-' }}</strong> 
                  <span class="badge bg-label-secondary btn-xs">{{ $booking->hewan->jenis ?? '-' }}</span><br>
                  <small class="text-muted"><i class="bx bx-user"></i> {{ $booking->hewan->owner->nama ?? '-' }}</small>
                </td>
                <td>
                  <span class="text-primary">{{ $booking->layanan->nama_layanan ?? 'Konsultasi Umum' }}</span>
                </td>
                <td>
                  <span class="text-wrap d-block" style="max-width: 180px; font-size: 0.85rem;" title="{{ $booking->keluhan }}">
                    {{ Str::limit($booking->keluhan ?: '-', 40) }}
                  </span>
                </td>
                <td>
                  @if($booking->status == 'pending')
                    <span class="badge bg-label-warning">Pending</span>
                  @elseif($booking->status == 'dikonfirmasi')
                    <span class="badge bg-label-info">Dikonfirmasi</span>
                  @elseif($booking->status == 'selesai')
                    <span class="badge bg-label-success">Selesai</span>
                  @else
                    <span class="badge bg-label-danger">Batal</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <!-- Update Status Button -->
                    <button type="button" 
                            class="btn btn-xs btn-outline-primary edit-status-btn"
                            data-id="{{ $booking->id }}"
                            data-status="{{ $booking->status }}"
                            data-catatan="{{ $booking->catatan_dokter }}"
                            data-action="{{ route('doctor.bookings.update-status', $booking->id) }}"
                            data-bs-toggle="modal" 
                            data-bs-target="#updateStatusModal"
                            title="Update Status & Catatan">
                      <i class="bx bx-edit-alt"></i> Status
                    </button>

                    <!-- Add Medical Record shortcut if booking is confirmed or finished -->
                    @if(in_array($booking->status, ['dikonfirmasi', 'selesai']))
                      <a href="{{ route('doctor.medical-records.create', ['id_hewan' => $booking->id_hewan]) }}" 
                         class="btn btn-xs btn-success" 
                         title="Tambah Rekam Medis">
                        <i class="bx bx-plus-medical"></i> Rekam Medis
                      </a>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bx bx-calendar-x mb-2" style="font-size: 3rem;"></i>
                  <p class="mb-0">Tidak ditemukan data booking yang cocok dengan filter.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Update Status & Doctor Notes Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="updateStatusForm" method="POST" action="">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Update Status & Catatan Dokter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="statusSelect" class="form-label">Status Pemeriksaan/Kunjungan</label>
              <select id="statusSelect" name="status" class="form-select">
                <option value="pending">Pending (Menunggu)</option>
                <option value="dikonfirmasi">Dikonfirmasi (Aktif/Hadir)</option>
                <option value="selesai">Selesai (Pemeriksaan Rampung)</option>
                <option value="batal">Batal (Dibatalkan)</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-0">
              <label for="catatanDokter" class="form-label">Catatan Awal / Rekomendasi Dokter</label>
              <textarea id="catatanDokter" name="catatan_dokter" class="form-control" rows="4" placeholder="Tuliskan catatan keluhan awal, berat badan hewan saat datang, atau catatan rujukan di sini..."></textarea>
              <small class="text-muted mt-1 d-block">Catatan ini akan tersimpan pada riwayat booking pasien.</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.edit-status-btn');
    const form = document.getElementById('updateStatusForm');
    const statusSelect = document.getElementById('statusSelect');
    const catatanInput = document.getElementById('catatanDokter');

    editBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const action = this.getAttribute('data-action');
        const status = this.getAttribute('data-status');
        const catatan = this.getAttribute('data-catatan');

        form.setAttribute('action', action);
        statusSelect.value = status;
        catatanInput.value = catatan || '';
      });
    });
  });
</script>
@endsection
