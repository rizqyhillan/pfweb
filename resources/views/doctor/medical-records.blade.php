@extends('layouts.doctor')

@section('title', 'Rekam Medis')
@section('page-title', 'Rekam Medis')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="fw-700 mb-1" style="color:#1e293b;">Rekam Medis Saya</h5>
            <p class="text-muted mb-0" style="font-size:.85rem;">
                Riwayat penanganan pasien yang telah Anda tangani.
            </p>
        </div>
    </div>

    <div class="card-section">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-3 py-3 text-muted" style="font-size:.72rem;font-weight:700;">#</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">PASIEN</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">PEMILIK</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">DIAGNOSA</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">TINDAKAN</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">BERAT</th>
                        <th class="py-3 text-muted" style="font-size:.72rem;font-weight:700;">TANGGAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                        <tr>
                            <td class="ps-3 py-3 align-middle text-muted">
                                {{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-3 align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:34px;height:34px;background:rgba(59,130,246,.1);">
                                        <i class="bi bi-paw" style="color:#3b82f6;font-size:.8rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-600">{{ $rec->hewan->nama_hewan ?? '—' }}</div>
                                        <small class="text-muted">{{ $rec->hewan->jenis ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 align-middle">
                                {{ $rec->hewan->owner->nama ?? '—' }}
                            </td>
                            <td class="py-3 align-middle" style="max-width:160px;">
                                <span class="text-truncate d-block" title="{{ $rec->diagnosa }}">
                                    {{ $rec->diagnosa ?: '—' }}
                                </span>
                            </td>
                            <td class="py-3 align-middle" style="max-width:160px;">
                                <span class="text-truncate d-block" title="{{ $rec->tindakan }}">
                                    {{ $rec->tindakan ?: '—' }}
                                </span>
                            </td>
                            <td class="py-3 align-middle text-muted">
                                {{ $rec->berat_saat_itu ? $rec->berat_saat_itu . ' kg' : '—' }}
                            </td>
                            <td class="py-3 align-middle text-muted">
                                {{ \Carbon\Carbon::parse($rec->tanggal)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-file-medical d-block mb-2"
                                   style="font-size:2rem;opacity:.3;"></i>
                                Belum ada rekam medis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
            <div class="px-3 py-2 border-top">
                {{ $records->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
