@extends('layouts.doctor')

@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="fw-700 mb-1" style="color:#1e293b;">Data Pasien</h5>
            <p class="text-muted mb-0" style="font-size:.85rem;">
                Semua hewan peliharaan yang terdaftar di PawPet.
            </p>
        </div>
    </div>

    <div class="card-section">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-3 py-3 text-muted" style="font-size:.72rem;font-weight:700;">#</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">NAMA HEWAN</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">JENIS / RAS</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">UMUR / BERAT</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">PEMILIK</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pets as $pet)
                        <tr>
                            <td class="ps-3 py-3 align-middle text-muted">{{ $loop->iteration }}</td>
                            <td class="py-3 align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:36px;height:36px;background:rgba(245,158,11,.1);">
                                        <i class="bi bi-paw" style="color:#f59e0b;font-size:.85rem;"></i>
                                    </div>
                                    <span class="fw-600">{{ $pet->nama_hewan }}</span>
                                </div>
                            </td>
                            <td class="py-3 align-middle">
                                <div>{{ ucfirst($pet->jenis) }}</div>
                                <small class="text-muted">{{ $pet->ras ?: '—' }}</small>
                            </td>
                            <td class="py-3 align-middle">
                                <div>{{ $pet->umur ? $pet->umur . ' bln' : '—' }}</div>
                                <small class="text-muted">{{ $pet->berat ? $pet->berat . ' kg' : '—' }}</small>
                            </td>
                            <td class="py-3 align-middle">
                                <div class="fw-600">{{ $pet->owner->nama ?? '—' }}</div>
                                <small class="text-muted">{{ $pet->owner->no_hp ?? '' }}</small>
                            </td>
                            <td class="py-3 align-middle text-muted" style="max-width:160px;">
                                <span class="text-truncate d-block">{{ $pet->catatan ?: '—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-paw d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                                Belum ada pasien terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pets->hasPages())
            <div class="px-3 py-2 border-top">
                {{ $pets->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
