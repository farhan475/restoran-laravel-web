<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi #{{ $transaksi->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 1.5em; }
        .info, .items, .footer { margin-bottom: 10px; }
        .info table, .items table { width: 100%; border-collapse: collapse; }
        .items table th, .items table td { padding: 5px 0; }
        .items .item-name { text-align: left; }
        .items .qty, .items .price, .items .subtotal { text-align: right; }
        .items .header-row th { border-top: 1px dashed #000; border-bottom: 1px dashed #000; }
        .footer table { width: 100%; }
        .footer .label { text-align: left; }
        .footer .value { text-align: right; }
        .footer .total { font-weight: bold; }
        .thank-you { text-align: center; margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px;}
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Restoran POS</h1>
        <p>Jalan Koding No. 123, Jakarta</p>
    </div>

    <div class="info">
        <table>
            <tr><td>No. Transaksi</td><td style="text-align:right;">#{{ $transaksi->id }}</td></tr>
            <tr><td>Tanggal</td><td style="text-align:right;">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Kasir</td><td style="text-align:right;">{{ auth()->user()->name }}</td></tr>
        </table>
    </div>

    <div class="items">
        <table>
            <tr class="header-row">
                <th class="item-name">Item</th>
                <th class="qty">Qty</th>
                <th class="price">Harga</th>
                <th class="subtotal">Total</th>
            </tr>
            @foreach($items as $item)
            <tr>
                <td class="item-name">{{ $item->nama }}</td>
                <td class="qty">{{ $item->jumlah }}</td>
                <td class="price">{{ number_format($item->harga_satuan) }}</td>
                <td class="subtotal">{{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        <table>
            <tr class="total"><td>TOTAL</td><td class="value">Rp {{ number_format($transaksi->total) }}</td></tr>
            <tr><td>BAYAR</td><td class="value">Rp {{ number_format($transaksi->dibayar) }}</td></tr>
            <tr><td>KEMBALI</td><td class="value">Rp {{ number_format($transaksi->dibayar - $transaksi->total) }}</td></tr>
        </table>
    </div>

    <div class="thank-you">
        <p>Terima Kasih Atas Kunjungan Anda</p>
    </div>

    <script>
        // Opsi untuk menutup tab setelah dialog print ditutup
        window.onafterprint = window.close;
    </script>
</body>
</html>