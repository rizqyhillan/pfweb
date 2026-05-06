@extends('layouts.admin')
@section('title', 'Data Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <h4 class="mb-0">Data Pengguna</h4>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Pengguna</a>
</div>
<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>No. HP</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($users as $user)
    <tr>
      <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
      <td><strong>{{ $user->nama }}</strong></td>
      <td>{{ $user->email }}</td>
      <td><span class="badge bg-label-primary">{{ ucfirst($user->role) }}</span></td>
      <td>{{ $user->no_hp ?? '-' }}</td>
      <td>@if($user->is_aktif)<span class="badge bg-label-success">Aktif</span>@else<span class="badge bg-label-secondary">Non-aktif</span>@endif</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button></form>
            @endif
          </div>
        </div>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengguna</td></tr>
    @endforelse
  </tbody>
</table></div>
@if($users->hasPages())<div class="card-footer d-flex justify-content-center">{{ $users->links() }}</div>@endif
</div>
@endsection
