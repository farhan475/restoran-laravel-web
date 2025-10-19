<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi</title>
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
    <h1>Laporan Rekapitulasi Penjualan</h1>
    
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th class="text-right">Total Omset</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($rows as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->tgl)->format('d F Y') }}</td>
                <td class="text-right">{{ $row->trx }}</td>
                <td class="text-right">Rp {{ number_format($row->omset) }}</td>
            </tr>
            @php $grandTotal += $row->omset; @endphp
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
            @if(count($rows) > 0)
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="2">Grand Total Omset</td>
                <td class="text-right">Rp {{ number_format($grandTotal) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>