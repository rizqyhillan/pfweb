@extends('layouts.admin')

@section('title', 'Data Boarding')

@section('content')
  <div class="d-flex justify-content-between align-items-end gap-3 mb-6">
    <h4 class="mb-0">Data Boarding (Penitipan)</h4>
    <form method="GET" action="{{ route('admin.boardings.index') }}" class="d-flex gap-2 align-items-end">
      <div style="min-width: 200px;">
        <label for="paket" class="form-label mb-2">Filter Paket</label>
        <select id="paket" name="paket" class="form-control form-control-sm">
          <option value="">-- Semua Paket --</option>
          @foreach($paketOptions as $key => $label)
            <option value="{{ $key }}" @if($selectedPaket === $key) selected @endif>
              {{ $label }}
            </option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-sm">
        Filter
      </button>
      <a href="{{ route('admin.boardings.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i> Boarding Baru</a>
    </form>
  </div>

  <div class="card">

  <div class="card">

    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Hewan</th>
            <th>Pemilik</th>
            <th>Kamar</th>
            <th>Paket</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Biaya</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($boardings as $boarding)
            <tr>
              <td>{{ $loop->iteration + ($boardings->currentPage() - 1) * $boardings->perPage() }}</td>
              <td><strong>{{ $boarding->hewan?->nama_hewan ?? '-' }}</strong></td>
              <td>{{ $boarding->hewan?->owner?->nama ?? '-' }}</td>
              <td>{{ $boarding->kamar?->nama_kamar ?? '-' }}</td>
              <td>
                @php
                  $paketColors = ['basic' => 'info', 'regular' => 'warning', 'premium' => 'success'];
                  $paketLabels = ['basic' => 'Basic', 'regular' => 'Regular', 'premium' => 'Premium'];
                  $paket = $boarding->kamar?->paket ?? 'unknown';
                @endphp
                <span class="badge bg-label-{{ $paketColors[$paket] ?? 'secondary' }}">{{ $paketLabels[$paket] ?? ucfirst($paket) }}</span>
              </td>
              <td>{{ $boarding->tanggal_masuk ? $boarding->tanggal_masuk->format('d/m/Y') : '-' }}</td>
              <td>{{ $boarding->tanggal_rencana_keluar ? $boarding->tanggal_rencana_keluar->format('d/m/Y') : '-' }}</td>
              <td>
                @if($boarding->status === 'aktif')
                  <span class="badge bg-label-primary">Aktif</span>
                @elseif($boarding->status === 'selesai')
                  <span class="badge bg-label-success">Selesai</span>
                @elseif($boarding->status === 'pending')
                  <span class="badge bg-label-warning">Pending</span>
                @else
                  <span class="badge bg-label-danger">Batal</span>
                @endif
              </td>
              <td>Rp {{ number_format($boarding->total_biaya, 0, ',', '.') }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.boardings.edit', $boarding) }}"><i
                        class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                    @if($boarding->status === 'pending')
                      <form action="{{ route('admin.boardings.update-status', $boarding) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="aktif">
                        <button class="dropdown-item text-primary"><i class="icon-base bx bx-check me-1"></i> Check-in/Aktif</button>
                      </form>
                    @endif
                    @if($boarding->status === 'aktif')
                      <form action="{{ route('admin.boardings.update-status', $boarding) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button class="dropdown-item text-success"><i class="icon-base bx bx-check-double me-1"></i> Selesaikan</button>
                      </form>
                    @endif
                    @if(in_array($boarding->status, ['pending', 'aktif']))
                      <form action="{{ route('admin.boardings.update-status', $boarding) }}" method="POST" onsubmit="return confirm('Batalkan penitipan ini?')">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="batal">
                        <button class="dropdown-item text-danger"><i class="icon-base bx bx-x me-1"></i> Batalkan</button>
                      </form>
                    @endif
                    <form action="{{ route('admin.boardings.destroy', $boarding) }}" method="POST">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada data boarding</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($boardings->hasPages())
      <div class="card-footer d-flex justify-content-center">
        {{ $boardings->links() }}
      </div>
    @endif
  </div>

  @section('page-js')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Echo !== 'undefined') {
          console.log('✅ Echo connected. Listening on boardings (Admin)...');

          window.Echo.channel('boardings')
            .listen('.new-boarding', (e) => {
              let b = e.boarding;
              let tbody = document.querySelector('tbody.table-border-bottom-0');
              let noDataTr = tbody.querySelector('td[colspan="9"]');
              if (noDataTr) noDataTr.parentElement.remove();

              let biayaFormat = new Intl.NumberFormat('id-ID').format(b.total_biaya);
              let checkinStr = b.tanggal_masuk ? new Date(b.tanggal_masuk).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-';
              let checkoutStr = b.tanggal_rencana_keluar ? new Date(b.tanggal_rencana_keluar).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-';

              let statusMap = {
                'aktif': '<span class="badge bg-label-primary">Aktif</span>',
                'selesai': '<span class="badge bg-label-success">Selesai</span>',
                'pending': '<span class="badge bg-label-warning">Pending</span>',
                'batal': '<span class="badge bg-label-danger">Batal</span>',
              };
              let statusBadge = statusMap[b.status] || '<span class="badge bg-label-secondary">' + (b.status || '-') + '</span>';

              let petName = b.hewan ? b.hewan.nama_hewan : '-';
              let ownerName = (b.hewan && b.hewan.owner) ? b.hewan.owner.nama : '-';
              let roomName = b.kamar ? b.kamar.nama_kamar : '-';

              let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

              let actionsHtml = `
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="/admin/boardings/${b.id}/edit"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                    <form action="/admin/boardings/${b.id}" method="POST">
                      <input type="hidden" name="_token" value="${csrfToken}">
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                    </form>
                  </div>
                </div>`;

              let html = `
                <tr style="animation: slideIn .3s ease; background-color: rgba(40, 199, 111, 0.05);">
                  <td><span class="badge bg-success bx-tada">Baru</span></td>
                  <td><strong>${petName}</strong></td>
                  <td>${ownerName}</td>
                  <td>${roomName}</td>
                  <td>${checkinStr}</td>
                  <td>${checkoutStr}</td>
                  <td>${statusBadge}</td>
                  <td>Rp ${biayaFormat}</td>
                  <td>${actionsHtml}</td>
                </tr>`;

              tbody.insertAdjacentHTML('afterbegin', html);
            });
        }
      });
    </script>
  @endsection
@endsection