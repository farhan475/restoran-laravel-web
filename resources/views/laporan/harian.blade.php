@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Laporan Harian</h1>
    <p class="text-sm text-neutral-400">Menampilkan semua transaksi yang telah dibayar pada tanggal yang dipilih.</p>
  </div>
  <div class="flex gap-2">
    {{-- Tombol-tombol ekspor --}}
    <a href="{{ route('laporan.harian.export', ['tanggal' => $tanggal]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        <span>Excel</span>
    </a>
    <a href="{{ route('laporan.harian.pdf', ['tanggal' => $tanggal]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v-2H5V8h14v2h-1v2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
        <span>PDF</span>
    </a>
  </div>
</div>

{{-- PENINGKATAN: Formulir Pemilih Tanggal --}}
<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg p-4 mb-6">
    <form method="GET" action="{{ route('laporan.harian') }}">
        <div class="flex items-center gap-3">
            <label for="tanggal" class="text-sm font-medium text-neutral-300">Pilih Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}"
                   class="rounded-lg bg-neutral-800 border-neutral-700 p-2 text-white focus:ring-2 focus:ring-blue-500 transition">
            <button type="submit" class="px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">
                Tampilkan
            </button>
        </div>
    </form>
</div>

<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b border-neutral-700">
        <tr class="text-neutral-400">
          <th class="px-4 py-3 text-left font-semibold">ID Transaksi</th>
          <th class="px-4 py-3 text-left font-semibold">Meja</th>
          <th class="px-4 py-3 text-left font-semibold">Waiter</th>
          <th class="px-4 py-3 text-right font-semibold">Total</th>
          <th class="px-4 py-3 text-right font-semibold">Dibayar</th>
          <th class="px-4 py-3 text-left font-semibold">Waktu</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="text-neutral-300">
        @forelse($rows as $r)
        <tr class="border-b border-neutral-800 last:border-b-0">
          <td class="px-4 py-3 font-semibold text-white">{{ $r->id }}</td>
          <td class="px-4 py-3">{{ \App\Models\Meja::find($r->meja_id)->kode ?? 'N/A' }}</td>
          <td class="px-4 py-3">{{ \App\Models\User::find($r->waiter_id)->name ?? 'N/A' }}</td>
          <td class="px-4 py-3 text-right">Rp {{ number_format($r->total,0,',','.') }}</td>
          <td class="px-4 py-3 text-right">Rp {{ number_format($r->dibayar,0,',','.') }}</td>
          <td class="px-4 py-3">{{ \Carbon\Carbon::parse($r->created_at)->format('H:i:s') }}</td>
          <td class="px-4 py-3 text-right">
            <a href="{{ route('kasir.struk.print', $r->id) }}" target="_blank" class="text-blue-400 hover:underline font-semibold">
                Print
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="p-6 text-center text-neutral-500">Tidak ada transaksi pada tanggal ini.</td></tr>
        @endforelse
      </tbody>
      {{-- PENINGKATAN: Ringkasan Total --}}
      @if($rows->count() > 0)
      <tfoot class="border-t-2 border-neutral-700 text-white font-bold">
        <tr>
            <td class="px-4 py-3" colspan="3">Total</td>
            <td class="px-4 py-3 text-right">Rp {{ number_format($rows->sum('total'),0,',','.') }}</td>
            <td class="px-4 py-3 text-right">Rp {{ number_format($rows->sum('dibayar'),0,',','.') }}</td>
            <td class="px-4 py-3" colspan="2"></td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>
@endsection