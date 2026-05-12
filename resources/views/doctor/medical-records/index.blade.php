@extends('layouts.admin')

@section('title', 'Rekam Medis Saya')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Rekam Medis Saya</h5>
        <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary">
          <i class="bx bx-plus me-1"></i> Tambah Rekam Medis
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Pemilik</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($records as $rec)
                <tr>
                  <td>{{ $loop->iteration + $records->firstItem() - 1 }}</td>
                  <td>
                    <strong>{{ $rec->hewan->nama_hewan ?? '-' }}</strong><br>
                    <small class="text-muted">{{ $rec->hewan->jenis ?? '-' }}</small>
                  </td>
                  <td>{{ $rec->hewan->owner->nama ?? '-' }}</td>
                  <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $rec->diagnosa }}">
                      {{ $rec->diagnosa ?: '-' }}
                    </span>
                  </td>
                  <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $rec->tindakan }}">
                      {{ $rec->tindakan ?: '-' }}
                    </span>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($rec->tanggal)->format('d/m/Y') }}</td>
                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('doctor.medical-records.show', $rec) }}">
                            <i class="icon-base bx bx-show me-1"></i> Detail
                        </a>
                        <a class="dropdown-item" href="{{ route('doctor.medical-records.edit', $rec) }}">
                          <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                        </a>
                        <form action="{{ route('doctor.medical-records.destroy', $rec) }}" method="POST">
                          @csrf @method('DELETE')
                          <button class="dropdown-item text-danger">
                            <i class="icon-base bx bx-trash me-1"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Belum ada riwayat rekam medis.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        @if($records->hasPages())
          <div class="mt-4">
            {{ $records->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
