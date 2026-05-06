@extends('layouts.admin')
@section('title', 'Transaksi / POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Transaksi / POS Kasir</h4>
    <a href="{{ route('karyawan.transactions') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i> Kembali</a>
</div>




<form action="{{ route('karyawan.transactions.store') }}" method="POST" id="posForm">
    @csrf
    <div class="row">
        <!-- Kolom Kiri: Keranjang -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="card-title mb-0 text-white">Keranjang Belanja</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tambah Barang</label>
                            <select class="form-select select2" id="product_select">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->harga }}" data-name="{{ $p->nama_barang }}" data-stock="{{ $p->stok }}">{{ $p->nama_barang }} - Rp {{ number_format($p->harga, 0, ',', '.') }} (Stok: {{ $p->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tambah Layanan</label>
                            <select class="form-select select2" id="service_select">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->harga }}" data-name="{{ $s->nama_layanan }}">{{ $s->nama_layanan }} - Rp {{ number_format($s->harga, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="cart_table">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th width="150">Harga</th>
                                    <th width="120">Qty</th>
                                    <th width="150">Subtotal</th>
                                    <th width="80">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Items will be appended here via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Receipt Section (hidden initially) -->
            <div class="card mb-4 d-none" id="receiptCard">
                <div class="card-header bg-success text-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white"><i class="bx bx-receipt me-2"></i>Struk Pembayaran</h5>
                    <button type="button" class="btn btn-light btn-sm" onclick="printReceipt()"><i class="bx bx-printer me-1"></i> Print</button>
                </div>
                <div class="card-body" id="receiptBody">
                    <!-- Receipt content injected via JS -->
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pembayaran -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Pelanggan *</label>
                        <select class="form-select" name="id_pelanggan" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('id_pelanggan') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control text-end" id="display_subtotal" value="0" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Promo / Diskon (Rp)</label>
                        <input type="number" class="form-control text-end" name="diskon" id="input_diskon" value="{{ old('diskon', 0) }}" min="0" onkeyup="calculateTotal()" onchange="calculateTotal()">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Total Akhir</label>
                        <input type="text" class="form-control form-control-lg text-end fw-bold text-primary" id="display_total" value="0" readonly>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Metode Bayar *</label>
                        <select class="form-select" name="metode_bayar" required>
                            <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash (Tunai)</option>
                            <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('metode_bayar') == 'ewallet' ? 'selected' : '' }}>E-Wallet / QRIS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar (Input Kasir) *</label>
                        <input type="number" class="form-control form-control-lg text-end" name="jumlah_bayar" id="input_bayar" value="{{ old('jumlah_bayar') }}" min="0" required onkeyup="calculateKembalian()" onchange="calculateKembalian()">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kembalian</label>
                        <input type="text" class="form-control text-end text-success fw-bold" id="display_kembalian" value="0" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="button" class="btn btn-success w-100 btn-lg" id="btnSubmitPOS" onclick="submitPOS()"><i class="bx bx-check-circle me-1"></i> Proses Pembayaran</button>
                    
                    <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-outline-primary w-100 btn-lg mt-3 d-none" id="btnNewTransaction"><i class="bx bx-plus me-1"></i> Transaksi Baru</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let cart = [];
    
    document.getElementById('product_select').addEventListener('change', function() {
        if(!this.value) return;
        let opt = this.options[this.selectedIndex];
        addItem('product', this.value, opt.getAttribute('data-name'), opt.getAttribute('data-price'), opt.getAttribute('data-stock'));
        this.value = '';
    });

    document.getElementById('service_select').addEventListener('change', function() {
        if(!this.value) return;
        let opt = this.options[this.selectedIndex];
        addItem('service', this.value, opt.getAttribute('data-name'), opt.getAttribute('data-price'), null);
        this.value = '';
    });

    function addItem(type, id, name, price, maxStock) {
        price = parseFloat(price);
        let existing = cart.find(item => item.type === type && item.id === id);
        
        if (existing) {
            if (type === 'product' && existing.qty >= maxStock) {
                Swal.fire({ icon: 'warning', title: 'Stok Habis', text: 'Stok tidak mencukupi!', timer: 2000, showConfirmButton: false });
                return;
            }
            existing.qty++;
        } else {
            cart.push({
                type: type,
                id: id,
                name: name,
                price: price,
                qty: 1,
                maxStock: type === 'product' ? parseInt(maxStock) : null
            });
        }
        renderCart();
    }

    function updateQty(index, newQty) {
        newQty = parseInt(newQty);
        if (isNaN(newQty) || newQty < 1) newQty = 1;
        
        let item = cart[index];
        if (item.type === 'product' && newQty > item.maxStock) {
            Swal.fire({ icon: 'warning', title: 'Batas Stok', text: 'Maksimal stok adalah ' + item.maxStock, timer: 2000, showConfirmButton: false });
            newQty = item.maxStock;
        }
        
        cart[index].qty = newQty;
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        let tbody = document.querySelector('#cart_table tbody');
        tbody.innerHTML = '';
        
        let subtotal = 0;
        
        if(cart.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Keranjang kosong</td></tr>';
        } else {
            cart.forEach((item, index) => {
                let itemSub = item.price * item.qty;
                subtotal += itemSub;
                
                let inputNameId = item.type === 'product' ? 'product_ids[]' : 'service_ids[]';
                let inputNameQty = item.type === 'product' ? 'product_qtys[]' : 'service_qtys[]';
                let badge = item.type === 'product' ? '<span class="badge bg-label-info ms-2">Brg</span>' : '<span class="badge bg-label-warning ms-2">Lyan</span>';
                
                let tr = `
                    <tr>
                        <td>
                            ${item.name} ${badge}
                            <input type="hidden" name="${inputNameId}" value="${item.id}">
                        </td>
                        <td class="text-end">${formatRp(item.price)}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm text-center" name="${inputNameQty}" value="${item.qty}" min="1" ${item.maxStock ? 'max="'+item.maxStock+'"' : ''} onchange="updateQty(${index}, this.value)">
                        </td>
                        <td class="text-end">${formatRp(itemSub)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="removeItem(${index})"><i class="bx bx-trash"></i></button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', tr);
            });
        }
        
        document.getElementById('display_subtotal').value = formatRp(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let subtotalStr = document.getElementById('display_subtotal').value.replace(/[^0-9]/g, '');
        let subtotal = parseFloat(subtotalStr) || 0;
        let diskon = parseFloat(document.getElementById('input_diskon').value) || 0;
        
        if(diskon > subtotal) {
            diskon = subtotal;
            document.getElementById('input_diskon').value = diskon;
        }
        
        let total = subtotal - diskon;
        document.getElementById('display_total').value = formatRp(total);
        calculateKembalian();
    }

    function calculateKembalian() {
        let totalStr = document.getElementById('display_total').value.replace(/[^0-9]/g, '');
        let total = parseFloat(totalStr) || 0;
        let bayar = parseFloat(document.getElementById('input_bayar').value) || 0;
        
        let kembalian = bayar - total;
        if(kembalian < 0) kembalian = 0;
        
        document.getElementById('display_kembalian').value = formatRp(kembalian);
    }

    function formatRp(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function submitPOS() {
        if(cart.length === 0) {
            Swal.fire({ icon: 'info', title: 'Keranjang Kosong', text: 'Tambahkan barang atau layanan terlebih dahulu!' });
            return;
        }
        
        let totalStr = document.getElementById('display_total').value.replace(/[^0-9]/g, '');
        let total = parseFloat(totalStr) || 0;
        let bayar = parseFloat(document.getElementById('input_bayar').value) || 0;
        
        if(bayar < total) {
            Swal.fire({ icon: 'warning', title: 'Pembayaran Kurang', text: 'Jumlah uang bayar kurang dari total belanja!' });
            return;
        }

        let btn = document.getElementById('btnSubmitPOS');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
        
        let formData = new FormData(document.getElementById('posForm'));

        fetch('{{ route("karyawan.transactions.store") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-secondary');
                btn.innerHTML = '<i class="bx bx-check-circle me-1"></i> Pembayaran Berhasil';
                document.getElementById('btnNewTransaction').classList.remove('d-none');

                // Disable all form inputs
                document.querySelectorAll('#posForm select, #posForm input, #posForm textarea').forEach(el => el.disabled = true);

                Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', text: 'Transaksi telah diproses.', timer: 2500, showConfirmButton: false });
                showReceipt(data.transaction, data.kembalian);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="bx bx-check-circle me-1"></i> Proses Pembayaran';
                Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan.' });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check-circle me-1"></i> Proses Pembayaran';
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal menghubungi server.' });
            console.error(err);
        });
    }

    function showReceipt(trx, kembalian) {
        let receiptCard = document.getElementById('receiptCard');
        let receiptBody = document.getElementById('receiptBody');

        let itemsHtml = '';
        if(trx.barang && trx.barang.length > 0) {
            trx.barang.forEach(item => {
                itemsHtml += `<tr>
                    <td>${item.barang ? item.barang.nama_barang : '-'}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-end">Rp ${formatRp(item.harga_satuan)}</td>
                    <td class="text-end">Rp ${formatRp(item.subtotal)}</td>
                </tr>`;
            });
        }
        if(trx.layanan && trx.layanan.length > 0) {
            trx.layanan.forEach(item => {
                itemsHtml += `<tr>
                    <td>${item.layanan ? item.layanan.nama_layanan : '-'}</td>
                    <td class="text-center">${item.jumlah}</td>
                    <td class="text-end">Rp ${formatRp(item.harga_satuan)}</td>
                    <td class="text-end">Rp ${formatRp(item.subtotal)}</td>
                </tr>`;
            });
        }

        let dateStr = trx.tanggal ? new Date(trx.tanggal).toLocaleString('id-ID', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'}) : '-';

        receiptBody.innerHTML = `
            <div id="receiptPrintArea">
                <div class="text-center mb-3">
                    <h5 class="mb-1">🐾 PawPet Clinic</h5>
                    <small class="text-muted">Struk Pembayaran</small>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-6"><small class="text-muted">Kode:</small><br><strong>${trx.kode_transaksi}</strong></div>
                    <div class="col-6 text-end"><small class="text-muted">Tanggal:</small><br><strong>${dateStr}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-6"><small class="text-muted">Pelanggan:</small><br>${trx.pelanggan ? trx.pelanggan.nama : '-'}</div>
                    <div class="col-6 text-end"><small class="text-muted">Kasir:</small><br>${trx.kasir ? trx.kasir.nama : '-'}</div>
                </div>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Item</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                </table>
                <table class="table table-sm mb-0">
                    <tr><td class="text-end border-0">Subtotal:</td><td class="text-end border-0 fw-bold" width="150">Rp ${formatRp(trx.subtotal)}</td></tr>
                    <tr><td class="text-end border-0">Diskon:</td><td class="text-end border-0 fw-bold">Rp ${formatRp(trx.diskon)}</td></tr>
                    <tr class="table-primary"><td class="text-end fw-bold">TOTAL:</td><td class="text-end fw-bold">Rp ${formatRp(trx.total)}</td></tr>
                    <tr><td class="text-end border-0">Bayar (${trx.metode_bayar}):</td><td class="text-end border-0 fw-bold">Rp ${formatRp(trx.jumlah_bayar)}</td></tr>
                    <tr class="table-success"><td class="text-end fw-bold">Kembalian:</td><td class="text-end fw-bold">Rp ${formatRp(kembalian)}</td></tr>
                </table>
                <hr>
                <p class="text-center text-muted mb-0"><small>Terima kasih telah berkunjung ke PawPet Clinic!</small></p>
            </div>
        `;

        receiptCard.classList.remove('d-none');
        receiptCard.scrollIntoView({ behavior: 'smooth' });
    }

    function printReceipt() {
        let printContent = document.getElementById('receiptPrintArea').innerHTML;
        let printWindow = window.open('', '_blank', 'width=400,height=600');
        let printHtml = '<html><head><title>Struk PawPet<\/title>'
            + '<style>'
            + 'body { font-family: "Segoe UI", sans-serif; font-size: 12px; padding: 15px; max-width: 380px; margin: 0 auto; }'
            + 'table { width: 100%; border-collapse: collapse; }'
            + 'th, td { padding: 4px 6px; font-size: 11px; }'
            + '.table-bordered th, .table-bordered td { border: 1px solid #ddd; }'
            + '.text-center { text-align: center; }'
            + '.text-end { text-align: right; }'
            + '.text-muted { color: #888; }'
            + '.fw-bold { font-weight: bold; }'
            + '.table-primary td { background: #e3f2fd; }'
            + '.table-success td { background: #e8f5e9; }'
            + '.table-light th { background: #f8f9fa; }'
            + 'hr { border: 1px dashed #ccc; }'
            + '.mb-0 { margin-bottom: 0; } .mb-1 { margin-bottom: 4px; } .mb-2 { margin-bottom: 8px; } .mb-3 { margin-bottom: 12px; }'
            + '.row { display: flex; } .col-6 { width: 50%; }'
            + 'small { font-size: 10px; }'
            + '<\/style><\/head><body>' + printContent + '<\/body><\/html>';
        printWindow.document.write(printHtml);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() { printWindow.print(); printWindow.close(); }, 300);
    }

    // ==========================================
    // REAL-TIME via Laravel Reverb (Echo)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.Echo !== 'undefined') {
            console.log('✅ Echo connected. Listening on pos-updates...');

            window.Echo.channel('pos-updates')
                .listen('.product-stock-updated', (e) => {
                    console.log('📦 Stock updated:', e);
                    let options = document.querySelectorAll('#product_select option');
                    options.forEach(opt => {
                        if(opt.value == e.product.id) {
                            opt.setAttribute('data-stock', e.product.stok);
                            opt.innerHTML = `${e.product.nama_barang} - Rp ${formatRp(e.product.harga)} (Stok: ${e.product.stok})`;
                        }
                    });
                    
                    let changed = false;
                    cart.forEach(item => {
                        if(item.type === 'product' && item.id == e.product.id) {
                            item.maxStock = e.product.stok;
                            if (item.qty > item.maxStock) {
                                item.qty = Math.max(0, item.maxStock);
                                changed = true;
                            }
                        }
                    });
                    if(changed) {
                        renderCart();
                        PawPetRealtime.showToast('Stok Berubah', `Stok "${e.product.nama_barang}" berubah menjadi ${e.product.stok}. Keranjang disesuaikan.`, 'warning');
                    }
                })
                .listen('.low-stock-alert', (e) => {
                    console.log('⚠️ Low stock:', e);
                    PawPetRealtime.showToast('⚠️ Stok Menipis!', `"${e.product.nama_barang}" hanya tersisa ${e.product.stok} unit!`, 'danger');
                });
        } else {
            console.error('❌ Echo not available');
        }
    });
</script>
@endsection