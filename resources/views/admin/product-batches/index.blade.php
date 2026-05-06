@extends('layouts.admin')
@section('title', 'Batch Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Batch Produk</h4>
    <a href="{{ route('admin.product-batches.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Batch</a>
</div>




<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>No. Batch</th>
                    <th>Supplier</th>
                    <th>Jumlah Masuk</th>
                    <th>Sisa Stok</th>
                    <th>Harga Beli</th>
                    <th>Tgl Masuk</th>
                    <th>Expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($batches as $batch)
                <tr>
                    <td><strong>{{ $batch->barang->nama_barang ?? '-' }}</strong></td>
                    <td>{{ $batch->no_batch ?? '-' }}</td>
                    <td>{{ $batch->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $batch->jumlah_masuk }}</td>
                    <td>
                        @if($batch->sisa_stok > 0)
                            <span class="badge bg-label-success">{{ $batch->sisa_stok }}</span>
                        @else
                            <span class="badge bg-label-danger">Habis</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($batch->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($batch->tanggal_masuk)->format('d M Y') }}</td>
                    <td>
                        @if($batch->tanggal_expired)
                            @if(\Carbon\Carbon::parse($batch->tanggal_expired)->isPast())
                                <span class="badge bg-label-danger">{{ \Carbon\Carbon::parse($batch->tanggal_expired)->format('d M Y') }}</span>
                            @elseif(\Carbon\Carbon::parse($batch->tanggal_expired)->diffInDays(now()) <= 30)
                                <span class="badge bg-label-warning">{{ \Carbon\Carbon::parse($batch->tanggal_expired)->format('d M Y') }}</span>
                            @else
                                <span class="badge bg-label-success">{{ \Carbon\Carbon::parse($batch->tanggal_expired)->format('d M Y') }}</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.product-batches.edit', $batch) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                                <form action="{{ route('admin.product-batches.destroy', $batch) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data batch produk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($batches->hasPages())
    <div class="card-footer d-flex justify-content-center">{{ $batches->links() }}</div>
    @endif
</div>
@endsection
