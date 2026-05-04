<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekam Medis</title>
    <style>
        body { font-family: sans-serif; }
        h2 { margin-bottom: 5px; }
        p { margin: 2px 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<h2>Rekam Medis Hewan</h2>

<p><strong>Nama Hewan:</strong> {{ $hewan->nama_hewan }}</p>
<p><strong>Pemilik:</strong> {{ $hewan->owner->nama ?? '-' }}</p>

<hr>

<table>
    <tr>
        <th>#</th>
        <th>Dokter</th>
        <th>Diagnosa</th>
        <th>Tindakan</th>
        <th>Resep</th>
        <th>Berat</th>
        <th>Tanggal</th>
    </tr>

    @foreach($records as $r)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $r->dokter->nama ?? '-' }}</td>
        <td>{{ $r->diagnosa }}</td>
        <td>{{ $r->tindakan }}</td>
        <td>{{ $r->resep }}</td>
        <td>{{ $r->berat_saat_itu ? $r->berat_saat_itu . ' kg' : '-' }}</td>
        <td>{{ $r->tanggal ? $r->tanggal->format('d/m/Y') : '-' }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>