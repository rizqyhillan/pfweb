@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Supplier</h4>
  <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Supplier</a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Kontak</th>
          <th>Email</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($suppliers as $supplier)
        <tr>
          <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
          <td><strong>{{ $supplier->name }}</strong></td>
          <td>{{ $supplier->contact ?? '-' }}</td>
          <td>{{ $supplier->email ?? '-' }}</td>
          <td>{{ Str::limit($supplier->address ?? '-', 40) }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.suppliers.edit', $supplier) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                  @csrf @method('DELETE')
                  <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data supplier</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($suppliers->hasPages())
  <div class="card-footer d-flex justify-content-center">{{ $suppliers->links() }}</div>
  @endif
</div>
@endsection
