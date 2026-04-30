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
              <td><strong>{{ $boarding->pet->name ?? '-' }}</strong></td>
              <td>{{ $boarding->pet->owner->name ?? '-' }}</td>
              <td>{{ $boarding->room->name ?? '-' }}</td>
              <td>{{ $boarding->check_in_date ? $boarding->check_in_date->format('d/m/Y') : '-' }}</td>
              <td>{{ $boarding->planned_check_out_date ? $boarding->planned_check_out_date->format('d/m/Y') : '-' }}</td>
              <td>
                @if($boarding->status === 'active')
                  <span class="badge bg-label-primary">Aktif</span>
                @elseif($boarding->status === 'completed')
                  <span class="badge bg-label-success">Selesai</span>
                @else
                  <span class="badge bg-label-danger">Dibatalkan</span>
                @endif
              </td>
              <td>Rp {{ number_format($boarding->total_cost, 0, ',', '.') }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="icon-base bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.boardings.edit', $boarding) }}"><i
                        class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                    <form action="{{ route('admin.boardings.destroy', $boarding) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus data ini?')">
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