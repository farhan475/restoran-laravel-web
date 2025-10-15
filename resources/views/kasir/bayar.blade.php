@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Pembayaran — Meja {{ $meja->kode }}</h1>

<div class="grid lg:grid-cols-2 gap-4">
  <div class="bg-white rounded-2xl shadow p-4">
    <h2 class="font-semibold mb-2">Rincian</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100"><tr>
          <th class="p-2 text-left">Item</th>
          <th class="p-2 text-right">Qty</th>
          <th class="p-2 text-right">Harga</th>
          <th class="p-2 text-right">Subtotal</th>
        </tr></thead>
        <tbody>
          @foreach($items as $it)
          <tr class="border-t">
            <td class="p-2">{{ $it->nama }}</td>
            <td class="p-2 text-right">{{ $it->jumlah }}</td>
            <td class="p-2 text-right">{{ number_format($it->harga_satuan,0,',','.') }}</td>
            <td class="p-2 text-right">{{ number_format($it->subtotal,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="border-t font-semibold">
            <td class="p-2" colspan="3">Total</td>
            <td class="p-2 text-right">Rp {{ number_format($transaksi->total,0,',','.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow p-4">
    <h2 class="font-semibold mb-2">Bayar</h2>
    <form method="post" action="{{ url('transaksi/bayar') }}" class="space-y-3">
      @csrf
      <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
      <div>
        <label class="block text-sm">Total</label>
        <input class="mt-1 w-full rounded-lg border p-2.5" value="{{ $transaksi->total }}" readonly>
      </div>
      <div>
        <label class="block text-sm">Uang Bayar</label>
        <input name="bayar" type="number" min="{{ $transaksi->total }}" class="mt-1 w-full rounded-lg border p-2.5" required>
        @error('bayar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <button class="w-full rounded-lg bg-gray-900 text-white py-2.5">Konfirmasi Pembayaran</button>
    </form>
    <p class="text-xs text-gray-500 mt-3">Setelah dibayar, status meja otomatis kembali kosong.</p>
  </div>
</div>
@endsection
