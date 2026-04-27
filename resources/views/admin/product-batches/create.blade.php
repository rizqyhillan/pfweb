@extends('layouts.admin')
@section('title', 'Tambah Batch Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tambah Batch Produk</h4>
    <a href="{{ route('admin.product-batches.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.product-batches.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Barang *</label>
                    <select class="form-select @error('id_barang') is-invalid @enderror" name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('id_barang') == $p->id ? 'selected' : '' }}>{{ $p->nama_barang }}</option>
                        @endforeach
                    </select>
                    @error('id_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supplier</label>
                    <select class="form-select" name="id_supplier">
                        <option value="">-- Tanpa Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('id_supplier') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">No. Batch</label>
                    <input type="text" class="form-control" name="no_batch" value="{{ old('no_batch') }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Beli *</label>
                    <input type="number" step="0.01" class="form-control" name="harga_beli" value="{{ old('harga_beli', 0) }}" required />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Masuk *</label>
                    <input type="number" class="form-control" name="jumlah_masuk" value="{{ old('jumlah_masuk', 1) }}" min="1" required />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk *</label>
                    <input type="date" class="form-control" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Kadaluarsa (Expired)</label>
                    <input type="date" class="form-control" name="tanggal_expired" value="{{ old('tanggal_expired') }}" />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Batch</button>
        </form>
    </div>
</div>
@endsection
