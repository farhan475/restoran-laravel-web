@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Antrian Kasir</h1>
    <p class="text-sm text-neutral-400">Daftar transaksi yang menunggu pembayaran.</p>
  </div>
  {{-- Tombol Kembali kini tidak diperlukan karena sudah ada di layout utama --}}
</div>

{{-- 
  CATATAN: Notifikasi sukses setelah pembayaran sudah ditangani oleh 
  layouts/app.blade.php, jadi tidak perlu ditambahkan di sini.
--}}

<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b border-neutral-700">
        <tr class="text-neutral-400">
          <th class="px-4 py-3 text-left font-semibold">ID Transaksi</th>
          <th class="px-4 py-3 text-left font-semibold">Meja</th>
          <th class="px-4 py-3 text-right font-semibold">Total Tagihan</th>
          <th class="px-4 py-3 text-left font-semibold">Waktu Order</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="text-neutral-300">
        @forelse($drafts as $d)
        <tr class="border-b border-neutral-800 last:border-b-0">
          <td class="px-4 py-3 font-semibold text-white">{{ $d->id }}</td>
          <td class="px-4 py-3">{{ $d->meja }}</td>
          <td class="px-4 py-3 text-right">Rp {{ number_format($d->total, 0, ',', '.') }}</td>
          <td class="px-4 py-3">{{ \Carbon\Carbon::parse($d->created_at)->diffForHumans() }}</td>
          <td class="px-4 py-3 text-right">
            {{-- Tombol "Proses Bayar" dengan gaya baru yang menonjol --}}
            <a href="{{ route('kasir.bayar', $d->id) }}" class="inline-block px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-xs font-semibold transition">
              Proses Bayar
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="p-6 text-center text-neutral-500">
            Tidak ada transaksi yang menunggu pembayaran.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Navigasi Paginasi dengan gaya baru --}}
@if ($drafts->hasPages())
  <div class="mt-6">
    {{ $drafts->links() }}
  </div>
@endif
@endsection