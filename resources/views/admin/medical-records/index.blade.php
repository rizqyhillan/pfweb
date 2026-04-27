@extends('layouts.admin')

@section('title', 'Rekam Medis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Rekam Medis</h4>
  <a href="{{ route('admin.medical-records.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Rekam Medis</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Hewan</th>
          <th>Pemilik</th>
          <th>Dokter</th>
          <th>Diagnosis</th>
          <th>Berat</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($records as $record)
        <tr>
          <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
          <td><strong>{{ $record->pet->name ?? '-' }}</strong></td>
          <td>{{ $record->pet->owner->name ?? '-' }}</td>
          <td>{{ $record->doctor->name ?? '-' }}</td>
          <td>{{ Str::limit($record->diagnosis ?? '-', 40) }}</td>
          <td>{{ $record->current_weight ? $record->current_weight . ' kg' : '-' }}</td>
          <td>{{ $record->date ? $record->date->format('d/m/Y') : '-' }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <form action="{{ route('admin.medical-records.destroy', $record) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($records->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $records->links() }}
  </div>
  @endif
</div>
@endsection
