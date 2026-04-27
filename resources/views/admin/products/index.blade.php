@extends('layouts.admin')

@section('title', 'Data Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Produk</h4>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Produk</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Unit</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($products as $product)
        <tr>
          <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
          <td><strong>{{ $product->name }}</strong></td>
          <td>{{ $product->category ?? '-' }}</td>
          <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
          <td>{{ number_format($product->stock) }}</td>
          <td>{{ $product->unit }}</td>
          <td>
            @if($product->is_active)
              <span class="badge bg-label-success">Aktif</span>
            @else
              <span class="badge bg-label-secondary">Non-aktif</span>
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data produk</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($products->hasPages())
  <div class="card-footer d-flex justify-content-center">
    {{ $products->links() }}
  </div>
  @endif
</div>
@endsection
