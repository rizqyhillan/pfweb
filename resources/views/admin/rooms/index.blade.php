@extends('layouts.admin')

@section('title', 'Data Kamar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Kamar</h4>
  <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Kamar</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Tipe</th>
          <th>Harga/Hari</th>
          <th>Kapasitas</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($rooms as $room)
        <tr>
          <td>{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
          <td><strong>{{ $room->name }}</strong></td>
          <td><span class="badge bg-label-info">{{ ucfirst($room->type) }}</span></td>
          <td>Rp {{ number_format($room->price_per_day, 0, ',', '.') }}</td>
          <td>{{ $room->capacity }}</td>
          <td>
            @if($room->status === 'available')
              <span class="badge bg-label-success">Tersedia</span>
            @elseif($room->status === 'occupied')
              <span class="badge bg-label-warning">Terisi</span>
            @else
              <span class="badge bg-label-secondary">Maintenance</span>
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.rooms.edit', $room) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kamar</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($rooms->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $rooms->links() }}
  </div>
  @endif
</div>
@endsection
