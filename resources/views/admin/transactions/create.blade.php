@extends('layouts.admin')
@section('title', 'Transaksi Baru')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Transaksi Baru</h4>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>
      Kembali</a>
  </div>
  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.transactions.store') }}" method="POST">@csrf
        <div class="row mb-6">
          <div class="col-md-4"><label class="form-label">Customer *</label>
            <select class="form-select @error('customer_id') is-invalid @enderror" name="customer_id" required>
              <option value="">-- Pilih Customer --</option>
              @foreach(\App\Models\User::where('role', 'owner')->get() as $cust)<option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>{{ $cust->name }}</option>@endforeach
            </select>@error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4"><label class="form-label">Tipe *</label>
            <select class="form-select" name="type" required>
              @foreach(['service' => 'Service', 'product' => 'Product', 'mixed' => 'Mixed'] as $k => $v)<option value="{{ $k }}" {{ old('type', 'mixed') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
            </select>
          </div>
          <div class="col-md-4"><label class="form-label">Total *</label><input type="number" step="0.01"
              class="form-control @error('total') is-invalid @enderror" name="total" value="{{ old('total', 0) }}"
              required />@error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        </div>
        <div class="row mb-6">
          <div class="col-md-4"><label class="form-label">Pembayaran *</label>
            <select class="form-select" name="payment_method" required>
              @foreach(['cash' => 'Cash', 'transfer' => 'Transfer', 'ewallet' => 'E-Wallet'] as $k => $v)<option value="{{ $k }}" {{ old('payment_method', 'cash') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
            </select>
          </div>
          <div class="col-md-4"><label class="form-label">Status *</label>
            <select class="form-select" name="status" required>
              @foreach(['pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled'] as $k => $v)<option value="{{ $k }}"
              {{ old('status', 'paid') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
            </select>
          </div>
          <div class="col-md-4"><label class="form-label">Catatan</label><textarea class="form-control" name="notes"
              rows="2">{{ old('notes') }}</textarea></div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan</button>
      </form>
    </div>
  </div>
@endsection