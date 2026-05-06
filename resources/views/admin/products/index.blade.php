@extends('layouts.admin')
@section('title', 'Data Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Barang / Produk</h4>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Barang</a>
</div>
<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>#</th><th>Gambar</th><th>Nama Barang</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Satuan</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($products as $p)
    <tr>
      <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
      <td>
        <img src="{{ $p->image_url }}" alt="{{ $p->nama_barang }}" width="50" height="50" style="object-fit:cover; border-radius:6px;" />
      </td>
      <td><strong>{{ $p->nama_barang }}</strong></td>
      <td>{{ $p->kategori ?? '-' }}</td>
      <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
      <td>@if($p->stok < 10)<span class="badge bg-label-danger">{{ $p->stok }}</span>@else{{ $p->stok }}@endif</td>
      <td>{{ $p->satuan }}</td>
      <td>@if($p->is_aktif)<span class="badge bg-label-success">Aktif</span>@else<span class="badge bg-label-secondary">Non-aktif</span>@endif</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.products.edit', $p) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
            <form action="{{ route('admin.products.destroy', $p) }}" method="POST">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
          </div>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data barang</td></tr>
    @endforelse
  </tbody>
</table></div>
@if($products->hasPages())<div class="card-footer d-flex justify-content-center">{{ $products->links() }}</div>@endif
</div>
@endsection
