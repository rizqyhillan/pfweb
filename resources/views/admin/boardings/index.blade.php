@extends('layouts.admin')

@section('title', 'Data Boarding')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Data Boarding (Penitipan)</h4>
    <a href="{{ route('admin.boardings.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Boarding
      Baru</a>
  </div>




  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Hewan</th>
            <th>Pemilik</th>
            <th>Kamar</th>
            <th>Check-in</th>
            <th>Plan Check-out</th>
            <th>Status</th>
            <th>Biaya</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($boardings as $boarding)
            <tr>
              <td>{{ $loop->iteration + ($boardings->currentPage() - 1) * $boardings->perPage() }}</td>
              <td><strong>{{ $boarding->hewan->nama_hewan ?? '-' }}</strong></td>
              <td>{{ $boarding->hewan->owner->nama ?? '-' }}</td>
              <td>{{ $boarding->kamar->nama_kamar ?? '-' }}</td>
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
              <td colspan="9" class="text-center text-muted py-4">Belum ada data boarding</td>
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
          console.log('✅ Echo connected. Listening on boardings (admin)...');

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

              let html = `
                <tr style="animation: slideIn .3s ease;">
                  <td><span class="badge bg-success">Baru</span></td>
                  <td><strong>${petName}</strong></td>
                  <td>${ownerName}</td>
                  <td>${roomName}</td>
                  <td>${checkinStr}</td>
                  <td>${checkoutStr}</td>
                  <td>${statusBadge}</td>
                  <td>Rp ${biayaFormat}</td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="/admin/boardings/${b.id}/edit"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                      </div>
                    </div>
                  </td>
                </tr>`;

              tbody.insertAdjacentHTML('afterbegin', html);
            });
        }
      });
    </script>
  @endsection
@endsection