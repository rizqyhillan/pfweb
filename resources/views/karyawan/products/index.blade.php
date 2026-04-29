@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Produk</h5>
        <span class="badge bg-label-info">Read-Only</span>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($products as $product)
                <tr>
                  <td>{{ $loop->iteration + $products->firstItem() - 1 }}</td>
                  <td><strong>{{ $product->nama_barang }}</strong></td>
                  <td>{{ ucfirst($product->kategori ?? '-') }}</td>
                  <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                  <td>
                    <span class="badge {{ $product->stok > 10 ? 'bg-label-success' : ($product->stok > 0 ? 'bg-label-warning' : 'bg-label-danger') }}">
                      {{ $product->stok }} {{ $product->satuan ?? 'pcs' }}
                    </span>
                  </td>
                  <td>
                    <span class="badge {{ $product->is_aktif ? 'bg-label-success' : 'bg-label-secondary' }}">
                      {{ $product->is_aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Belum ada data produk.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($products->hasPages())
          <div class="mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
