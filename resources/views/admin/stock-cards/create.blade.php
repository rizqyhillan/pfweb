@extends('layouts.admin')
@section('title', 'Catat Mutasi Stok')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Catat Mutasi Stok Manual</h4>
    <a href="{{ route('admin.stock-cards.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.stock-cards.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Barang *</label>
                    <select class="form-select @error('id_barang') is-invalid @enderror" name="id_barang" id="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('id_barang') == $p->id ? 'selected' : '' }}>{{ $p->nama_barang }} (Stok: {{ $p->stok }})</option>
                        @endforeach
                    </select>
                    @error('id_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Batch (Opsional)</label>
                    <select class="form-select" name="id_batch" id="id_batch">
                        <option value="">-- Tanpa Batch --</option>
                        @foreach($batches as $b)
                            <option value="{{ $b->id }}" data-barang="{{ $b->id_barang }}" {{ old('id_batch') == $b->id ? 'selected' : '' }}>
                                {{ $b->no_batch ?? 'Batch #'.$b->id }} (Sisa: {{ $b->sisa_stok }} | Exp: {{ $b->tanggal_expired ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Jenis Mutasi *</label>
                    <select class="form-select" name="jenis_mutasi" required>
                        <option value="masuk" {{ old('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>Barang Masuk (+)</option>
                        <option value="keluar" {{ old('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>Barang Keluar (-)</option>
                        <option value="adjustment" {{ old('jenis_mutasi') == 'adjustment' ? 'selected' : '' }}>Penyesuaian Hilang/Rusak (-)</option>
                        <option value="retur" {{ old('jenis_mutasi') == 'retur' ? 'selected' : '' }}>Retur (+)</option>
                        <option value="expired" {{ old('jenis_mutasi') == 'expired' ? 'selected' : '' }}>Kadaluarsa (-)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah *</label>
                    <input type="number" class="form-control" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referensi</label>
                    <input type="text" class="form-control" name="referensi" value="{{ old('referensi') }}" placeholder="Contoh: INV-001, Hilang di gudang, dll" />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Mutasi</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('id_barang').addEventListener('change', function() {
        let barangId = this.value;
        let batchSelect = document.getElementById('id_batch');
        let options = batchSelect.options;

        for (let i = 1; i < options.length; i++) {
            let opt = options[i];
            if (barangId === "" || opt.getAttribute('data-barang') === barangId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        }
        batchSelect.value = "";
    });
</script>
@endsection
