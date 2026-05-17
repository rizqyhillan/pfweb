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
              <td><strong>{{ $boarding->hewan?->nama_hewan ?? '-' }}</strong></td>
              <td>{{ $boarding->hewan?->owner?->nama ?? '-' }}</td>
              <td>{{ $boarding->kamar?->nama_kamar ?? '-' }}</td>
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

@endsection