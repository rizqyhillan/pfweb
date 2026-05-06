@extends('layouts.admin')
@section('title', 'Kartu Stok')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Kartu Stok (Mutasi)</h4>
    <a href="{{ route('admin.stock-cards.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Catat Mutasi</a>
</div>




<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.stock-cards.index') }}" method="GET" class="row gx-3 gy-2 align-items-center">
            <div class="col-md-4">
                <select name="id_barang" class="form-select">
                    <option value="">-- Filter Semua Barang --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ request('id_barang') == $p->id ? 'selected' : '' }}>{{ $p->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="jenis_mutasi" class="form-select">
                    <option value="">-- Filter Semua Mutasi --</option>
                    @foreach(['masuk' => 'Masuk', 'keluar' => 'Keluar', 'adjustment' => 'Penyesuaian', 'retur' => 'Retur', 'expired' => 'Kadaluarsa'] as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis_mutasi') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('admin.stock-cards.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Batch</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Saldo</th>
                    <th>Referensi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($stockCards as $card)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($card->tanggal)->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $card->barang->nama_barang ?? '-' }}</strong></td>
                    <td>{{ $card->batch->no_batch ?? '-' }}</td>
                    <td>
                        @if($card->jenis_mutasi === 'masuk') <span class="badge bg-label-success">Masuk</span>
                        @elseif($card->jenis_mutasi === 'keluar') <span class="badge bg-label-danger">Keluar</span>
                        @elseif($card->jenis_mutasi === 'adjustment') <span class="badge bg-label-warning">Adjustment</span>
                        @elseif($card->jenis_mutasi === 'retur') <span class="badge bg-label-info">Retur</span>
                        @elseif($card->jenis_mutasi === 'expired') <span class="badge bg-label-secondary">Expired</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($card->jenis_mutasi, ['masuk', 'retur']))
                            <span class="text-success">+{{ $card->jumlah }}</span>
                        @else
                            <span class="text-danger">-{{ $card->jumlah }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $card->saldo }}</strong></td>
                    <td>{{ $card->referensi ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada catatan mutasi stok</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stockCards->hasPages())
    <div class="card-footer d-flex justify-content-center">{{ $stockCards->links() }}</div>
    @endif
</div>
@endsection
