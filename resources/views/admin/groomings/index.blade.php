@extends('layouts.admin')

@section('title', 'Data Grooming')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Data Grooming</h4>
    <a href="{{ route('admin.groomings.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Grooming
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
            <th>Paket Grooming</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Biaya</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($groomings as $grooming)
            <tr>
              <td>{{ $loop->iteration + ($groomings->currentPage() - 1) * $groomings->perPage() }}</td>
              <td><strong>{{ $grooming->hewan?->nama_hewan ?? '-' }}</strong></td>
              <td>{{ $grooming->hewan?->owner?->nama ?? '-' }}</td>
              <td>{{ $grooming->paket?->label ?? '-' }}</td>
              <td>{{ $grooming->tanggal_grooming ? $grooming->tanggal_grooming->format('d/m/Y') : '-' }}</td>
              <td>{{ $grooming->waktu_grooming ? $grooming->waktu_grooming->format('H:i') : '-' }}</td>
              <td>
                @if($grooming->status === 'aktif')
                  <span class="badge bg-label-primary">Aktif</span>
                @elseif($grooming->status === 'selesai')
                  <span class="badge bg-label-success">Selesai</span>
                @elseif($grooming->status === 'pending')
                  <span class="badge bg-label-warning">Pending</span>
                @else
                  <span class="badge bg-label-danger">Batal</span>
                @endif
              </td>
              <td>Rp {{ number_format($grooming->total_biaya, 0, ',', '.') }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.groomings.edit', $grooming) }}"><i
                        class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                    @if($grooming->status === 'pending')
                      <form action="{{ route('admin.groomings.update-status', $grooming) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="aktif">
                        <button class="dropdown-item text-primary"><i class="icon-base bx bx-check me-1"></i> Jadikan Aktif</button>
                      </form>
                    @endif
                    @if($grooming->status === 'aktif')
                      <form action="{{ route('admin.groomings.update-status', $grooming) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button class="dropdown-item text-success"><i class="icon-base bx bx-check-double me-1"></i> Selesaikan</button>
                      </form>
                    @endif
                    @if(in_array($grooming->status, ['pending', 'aktif']))
                      <form action="{{ route('admin.groomings.update-status', $grooming) }}" method="POST" onsubmit="return confirm('Batalkan grooming ini?')">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="batal">
                        <button class="dropdown-item text-danger"><i class="icon-base bx bx-x me-1"></i> Batalkan</button>
                      </form>
                    @endif
                    <form action="{{ route('admin.groomings.destroy', $grooming) }}" method="POST">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Belum ada data grooming</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($groomings->hasPages())
      <div class="card-footer d-flex justify-content-center">
        {{ $groomings->links() }}
      </div>
    @endif
  </div>

@endsection
