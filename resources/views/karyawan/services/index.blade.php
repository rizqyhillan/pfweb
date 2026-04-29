@extends('layouts.admin')

@section('title', 'Layanan')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Layanan</h5>
        <span class="badge bg-label-info">Read-Only</span>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Layanan</th>
                <th>Jenis</th>
                <th>Harga</th>
                <th>Durasi</th>
                <th>Dokter</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($services as $svc)
                <tr>
                  <td>{{ $loop->iteration + $services->firstItem() - 1 }}</td>
                  <td><strong>{{ $svc->nama_layanan }}</strong></td>
                  <td>{{ ucfirst($svc->jenis_layanan ?? '-') }}</td>
                  <td>Rp {{ number_format($svc->harga, 0, ',', '.') }}</td>
                  <td>{{ $svc->durasi_menit ? $svc->durasi_menit . ' menit' : '-' }}</td>
                  <td>{{ $svc->dokter->nama ?? '-' }}</td>
                  <td>
                    <span class="badge {{ $svc->is_aktif ? 'bg-label-success' : 'bg-label-secondary' }}">
                      {{ $svc->is_aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">Belum ada data layanan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($services->hasPages())
          <div class="mt-4">
            {{ $services->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
