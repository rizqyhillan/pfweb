@extends('layouts.admin')
@section('title', 'Data Supplier')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Supplier</h4>
  <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Supplier</a>
</div>
<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>No</th><th>Nama</th><th>Kontak</th><th>Email</th><th>Alamat</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($suppliers as $s)
    <tr>
      <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
      <td><strong>{{ $s->nama_supplier }}</strong></td>
      <td>{{ $s->kontak ?? '-' }}</td>
      <td>{{ $s->email ?? '-' }}</td>
      <td>{{ Str::limit($s->alamat ?? '-', 40) }}</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.suppliers.edit', $s) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
            <form action="{{ route('admin.suppliers.destroy', $s) }}" method="POST">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
          </div>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada supplier</td></tr>
    @endforelse
  </tbody>
</table></div>
@if($suppliers->hasPages())<div class="card-footer d-flex justify-content-center">{{ $suppliers->links() }}</div>@endif
</div>
@endsection
