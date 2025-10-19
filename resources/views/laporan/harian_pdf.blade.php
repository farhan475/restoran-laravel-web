<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian {{ $tanggal }}</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        thead { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Harian - {{ $tanggal }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>ID Meja</th>
                <th>ID Waiter</th>
                <th class="text-right">Total</th>
                <th class="text-right">Dibayar</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->meja_id }}</td>
                <td>{{ $row->waiter_id }}</td>
                <td class="text-right">Rp {{ number_format($row->total) }}</td>
                <td class="text-right">Rp {{ number_format($row->dibayar) }}</td>
                <td>{{ $row->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data untuk tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>