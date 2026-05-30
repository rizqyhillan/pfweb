@extends('layouts.admin')

@section('title', 'Tambah Layanan Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tambah Layanan Dokter</h1>
            <p class="text-muted mb-0">Buat layanan medis baru untuk dokter.</p>
        </div>

        <a href="{{ route('admin.doctor-services.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.doctor-services.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_layanan" class="form-label">Nama Layanan</label>
                    <input 
                        type="text" 
                        name="nama_layanan" 
                        id="nama_layanan" 
                        class="form-control" 
                        value="{{ old('nama_layanan') }}"
                        placeholder="Contoh: Pemeriksaan Umum"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="id_dokter" class="form-label">Dokter</label>
                    <select name="id_dokter" id="id_dokter" class="form-control">
                        <option value="">Semua Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('id_dokter') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Kosongkan jika layanan bisa dilayani semua dokter.</small>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input 
                        type="number" 
                        name="harga" 
                        id="harga" 
                        class="form-control" 
                        value="{{ old('harga') }}"
                        min="0"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        id="deskripsi" 
                        rows="4" 
                        class="form-control"
                        placeholder="Tulis deskripsi layanan..."
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-check form-switch mb-4">
                    <input type="hidden" name="is_aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.doctor-services.index') }}" class="btn btn-light">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection