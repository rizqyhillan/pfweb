@extends('layouts.admin')
@section('title', 'Banner Home Mobile')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
  <div>
    <h4 class="mb-0">Banner Home Mobile</h4>
    <small class="text-muted">Banner yang tampil di carousel bagian atas aplikasi mobile.</small>
  </div>
  <a href="{{ route('admin.home-banners.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Banner</a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Gambar</th>
          <th>Judul</th>
          <th>Urutan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($banners as $banner)
          <tr>
            <td>{{ $loop->iteration + ($banners->currentPage() - 1) * $banners->perPage() }}</td>
            <td>
              <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" width="120" height="64" style="object-fit:cover; border-radius:8px;" />
            </td>
            <td>
              <strong>{{ $banner->title ?? '-' }}</strong>
              @if($banner->subtitle)<div class="small text-muted">{{ $banner->subtitle }}</div>@endif
              @if($banner->link_url)<div class="small text-muted text-truncate" style="max-width:280px;">{{ $banner->link_url }}</div>@endif
            </td>
            <td>{{ $banner->sort_order }}</td>
            <td>
              @if($banner->is_active)
                <span class="badge bg-label-success">Aktif</span>
              @else
                <span class="badge bg-label-secondary">Non-aktif</span>
              @endif
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('admin.home-banners.edit', $banner) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                  <form action="{{ route('admin.home-banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')">
                    @csrf @method('DELETE')
                    <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">Belum ada banner. Mobile akan memakai banner asset bawaan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($banners->hasPages())<div class="card-footer d-flex justify-content-center">{{ $banners->links() }}</div>@endif
</div>
@endsection
