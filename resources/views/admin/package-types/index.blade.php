@extends('layouts.admin')
@section('title', request('section') === 'grooming' ? 'Jenis Grooming' : 'Jenis Paket')
@section('content')
@php $section = request('section'); @endphp
<div class="d-flex justify-content-between align-items-center mb-6">
  <div>
    <h4 class="mb-0">{{ $section === 'grooming' ? 'Jenis Grooming' : 'Jenis Paket' }}</h4>
    <small class="text-muted">{{ $section === 'grooming' ? 'Kelola daftar paket grooming.' : 'Kelola daftar paket yang dapat dipilih saat menambahkan kamar.' }}</small>
  </div>
  @if(empty($tableMissing))
    <a href="{{ route('admin.package-types.create', request()->only('section')) }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Tambah Paket</a>
  @else
    <button class="btn btn-secondary" disabled><i class="bx bx-plus me-1"></i> Tambah Paket</button>
  @endif
</div>

@if(! empty($tableMissing))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bx bx-info-square me-2"></i>
    Tabel <strong>package_types</strong> belum dibuat. Daftar default paket dasar ditampilkan sementara.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bx bx-error-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card"><div class="table-responsive text-nowrap"><table class="table">
  <thead><tr><th>#</th><th>Nama</th><th>Label</th><th>{{ $section === 'grooming' ? 'Harga Paket' : 'Harga/Malam' }}</th><th>Keterangan</th><th>Fasilitas</th><th>Aksi</th></tr></thead>
  <tbody class="table-border-bottom-0">
    @forelse($packageTypes as $packageType)
    <tr>
      <td>
        @php
          $index = $loop->iteration;
          if (method_exists($packageTypes, 'currentPage')) {
            $index += ($packageTypes->currentPage() - 1) * $packageTypes->perPage();
          }
        @endphp
        {{ $index }}
      </td>
      <td><strong>{{ $packageType->name }}</strong></td>
      <td>{{ $packageType->label }}</td>
      <td>Rp {{ number_format($packageType->harga_per_malam ?? 0, 0, ',', '.') }}</td>
      <td>{{ $packageType->description ?? '-' }}</td>
      <td style="white-space: normal; max-width: 250px;">
        @if(!empty($packageType->fasilitas) && is_array($packageType->fasilitas))
          @foreach($packageType->fasilitas as $item)
            <span class="badge bg-label-success mb-1"><i class="bx bx-check me-1"></i>{{ $item }}</span>
          @endforeach
        @else
          <span class="text-muted">-</span>
        @endif
      </td>
      <td>
        @if(empty($tableMissing))
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ route('admin.package-types.edit', array_merge(['package_type' => $packageType], request()->only('section'))) }}"><i class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
              <form action="{{ route('admin.package-types.destroy', $packageType) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $packageType->label }}?');">
                <input type="hidden" name="section" value="{{ $section }}" />
                @csrf @method('DELETE')
                <button class="dropdown-item text-danger"><i class="icon-base bx bx-trash me-1"></i> Hapus</button>
              </form>
            </div>
          </div>
        @else
          <span class="text-muted">Default</span>
        @endif
      </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada jenis paket</td></tr>
    @endforelse
  </tbody>
</table></div>
@if(method_exists($packageTypes, 'hasPages') && $packageTypes->hasPages())<div class="card-footer d-flex justify-content-center">{{ $packageTypes->links() }}</div>@endif
</div>
@endsection
