<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi - PawPet</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #e67e22; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; color: #e67e22; }
        .header p { margin: 2px 0; color: #666; font-size: 12px; }
        .meta { margin-bottom: 15px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 5px; }
        .summary-cards { display: table; width: 100%; margin-bottom: 20px; }
        .summary-card { display: table-cell; width: 25%; text-align: center; padding: 10px; border: 1px solid #ddd; }
        .summary-card .value { font-size: 16px; font-weight: bold; color: #e67e22; }
        .summary-card .label { font-size: 10px; color: #666; margin-top: 3px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 5px 8px; text-align: left; }
        table.data th { background-color: #e67e22; color: white; font-size: 10px; text-transform: uppercase; }
        table.data td { font-size: 10px; }
        table.data tr:nth-child(even) { background-color: #fdf2e9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; color: white; }
        .badge-success { background-color: #27ae60; }
        .badge-warning { background-color: #f39c12; }
        .badge-danger { background-color: #e74c3c; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .totals { margin-top: 15px; }
        .totals table { width: 300px; float: right; }
        .totals td { padding: 3px 8px; }
        .totals .total-row { font-weight: bold; font-size: 13px; border-top: 2px solid #e67e22; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐾 PawPet Clinic</h1>
        <p>Laporan Transaksi</p>
        <p>
            @if($startDate && $endDate)
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @else
                Semua Periode
            @endif
        </p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <div class="value">{{ number_format($totalTransactions) }}</div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="summary-card">
            <div class="value">{{ number_format($paidTransactions) }}</div>
            <div class="label">Transaksi Lunas</div>
        </div>
        <div class="summary-card">
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
        <div class="summary-card">
            <div class="value">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</div>
            <div class="label">Total Diskon</div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Jenis</th>
                <th class="text-right">Total</th>
                <th>Metode</th>
                <th class="text-center">Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $trx)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->pelanggan->nama ?? '-' }}</td>
                <td>{{ $trx->kasir->nama ?? '-' }}</td>
                <td>{{ ucfirst($trx->jenis) }}</td>
                <td class="text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                <td>{{ ucfirst($trx->metode_bayar) }}</td>
                <td class="text-center">
                    @if($trx->status === 'lunas')
                        <span class="badge badge-success">Lunas</span>
                    @elseif($trx->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge badge-danger">Batal</span>
                    @endif
                </td>
                <td>{{ $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals" style="overflow: hidden;">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Diskon:</td>
                <td class="text-right">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Laporan digenerate pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }} oleh {{ auth()->user()->nama ?? 'System' }}
        <br>PawPet Clinic &copy; {{ date('Y') }}
    </div>
</body>
</html>
