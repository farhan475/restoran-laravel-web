@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-white mb-6">Pembayaran — Meja {{ $meja->kode }}</h1>

<div class="grid lg:grid-cols-2 gap-6">
  {{-- Kolom Kiri: Rincian Pesanan --}}
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-white mb-4">Rincian Pesanan</h2> 
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="border-b border-neutral-700">
          <tr class="text-neutral-400">
            <th class="p-2 text-left font-semibold">Item</th>
            <th class="p-2 text-right font-semibold">Qty</th>
            <th class="p-2 text-right font-semibold">Harga</th>
            <th class="p-2 text-right font-semibold">Subtotal</th>
          </tr>
        </thead>
        <tbody class="text-neutral-300">
          @foreach($items as $it)
          <tr class="border-b border-neutral-800">
            <td class="p-2 text-white">{{ $it->nama }}</td>
            <td class="p-2 text-right">{{ $it->jumlah }}</td>
            <td class="p-2 text-right">Rp {{ number_format($it->harga_satuan,0,',','.') }}</td>
            <td class="p-2 text-right">Rp {{ number_format($it->subtotal,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot class="text-white">
          <tr class="border-t-2 border-neutral-700 font-bold">
            <td class="p-3" colspan="3">TOTAL TAGIHAN</td>
            <td class="p-3 text-right text-lg">Rp {{ number_format($transaksi->total,0,',','.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Kolom Kanan: Form Pembayaran --}}
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-white mb-4">Proses Pembayaran</h2>
    <form method="post" action="{{ route('transaksi.bayar') }}" class="space-y-5">
      @csrf
      <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
      
      {{-- Input Total (Readonly) --}}
      <div>
        <label class="block text-sm font-medium text-neutral-300">Total Tagihan</label>
        <div class="mt-2">
            {{-- Menyimpan nilai total untuk dibaca oleh JavaScript --}}
            <input id="totalAmount" class="w-full rounded-lg bg-neutral-800/50 border-neutral-700 p-3 text-white" value="{{ $transaksi->total }}" readonly>
        </div>
      </div>
      
      {{-- Input Uang Bayar --}}
      <div>
        <label for="paymentInput" class="block text-sm font-medium text-neutral-300">Uang Diterima</label>
        <div class="mt-2">
          <input id="paymentInput" name="bayar" type="number" min="{{ $transaksi->total }}" 
                 class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white focus:ring-2 focus:ring-inset focus:ring-blue-500 transition" 
                 required autofocus onfocus="this.select()">
        </div>
        @error('bayar') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
      </div>

      {{-- PENINGKATAN: Tampilan Uang Kembalian Real-time --}}
      <div>
          <label class="block text-sm font-medium text-neutral-300">Kembalian</label>
          <div id="changeDisplay" class="mt-2 w-full rounded-lg bg-neutral-800/50 border-neutral-700 p-3 text-2xl font-bold text-green-400">
              Rp 0
          </div>
      </div>

      {{-- Tombol Submit --}}
      <div class="pt-2">
        <button type="submit" class="w-full rounded-lg bg-white hover:bg-neutral-200 text-black py-3 text-sm font-semibold transition">
            Konfirmasi Pembayaran
        </button>
      </div>
    </form>
    <p class="text-xs text-neutral-500 mt-4 text-center">Setelah dibayar, status meja otomatis kembali kosong.</p>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalAmountEl = document.getElementById('totalAmount');
    const paymentInputEl = document.getElementById('paymentInput');
    const changeDisplayEl = document.getElementById('changeDisplay');

    // Fungsi untuk menghitung dan menampilkan kembalian
    function calculateChange() {
        const total = parseFloat(totalAmountEl.value) || 0;
        const payment = parseFloat(paymentInputEl.value) || 0;
        
        if (payment <= 0) {
            changeDisplayEl.textContent = 'Rp 0';
            changeDisplayEl.classList.remove('text-red-400');
            changeDisplayEl.classList.add('text-green-400');
            return;
        }

        const change = payment - total;

        if (change >= 0) {
            changeDisplayEl.textContent = `Rp ${change.toLocaleString('id-ID')}`;
            changeDisplayEl.classList.remove('text-red-400');
            changeDisplayEl.classList.add('text-green-400');
        } else {
            // Jika uang kurang
            changeDisplayEl.textContent = `Kurang Rp ${Math.abs(change).toLocaleString('id-ID')}`;
            changeDisplayEl.classList.remove('text-green-400');
            changeDisplayEl.classList.add('text-red-400');
        }
    }

    // Panggil fungsi setiap kali user mengetik di input uang bayar
    paymentInputEl.addEventListener('input', calculateChange);
});
</script>
@endpush