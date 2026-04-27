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
      <thead><tr><th>#</th><th>Hewan</th><th>Pemilik</th><th>Dokter</th><th>Diagnosa</th><th>Tindakan</th><th>Resep</th><th>Berat</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody class="table-border-bottom-0">
        @forelse($records as $r)
        <tr>
          <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
          <td><strong>{{ $r->hewan->nama_hewan ?? '-' }}</strong></td>
          <td>{{ $r->hewan->owner->nama ?? '-' }}</td>
          <td>{{ $r->dokter->nama ?? '-' }}</td>
          <td>{{ Str::limit($r->diagnosa ?? '-', 30) }}</td>
          <td>{{ Str::limit($r->tindakan ?? '-', 30) }}</td>
          <td>{{ Str::limit($r->resep ?? '-', 30) }}</td>
          <td>{{ $r->berat_saat_itu ? $r->berat_saat_itu . ' kg' : '-' }}</td>
          <td>{{ $r->tanggal ? $r->tanggal->format('d/m/Y') : '-' }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.medical-records.show', $r) }}"><i class="icon-base bx bx-show me-1"></i> Detail</a>
                <a class="dropdown-item" href="{{ route('admin.medical-records.edit', $r) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.medical-records.destroy', $r) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($records->hasPages())<div class="card-footer d-flex justify-content-center">{{ $records->links() }}</div>@endif
</div>
@endsection
