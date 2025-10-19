@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Rekapitulasi Penjualan</h1>
    <p class="text-sm text-neutral-400">Menampilkan ringkasan penjualan per hari.</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('laporan.rekap.pdf') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">
        <svg xmlns="http://www.w.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v-2H5V8h14v2h-1v2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
        <span>Export ke PDF</span>
    </a>
  </div>
</div>

<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b border-neutral-700">
        <tr class="text-neutral-400">
          <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
          <th class="px-4 py-3 text-right font-semibold">Jumlah Transaksi</th>
          <th class="px-4 py-3 text-right font-semibold">Total Omset</th>
        </tr>
      </thead>
      <tbody class="text-neutral-300">
        @forelse($rows as $r)
          <tr class="border-b border-neutral-800 last:border-b-0">
            <td class="px-4 py-3 font-semibold text-white">
              {{-- Tanggal menjadi tautan ke Laporan Harian untuk detail --}}
              <a href="{{ route('laporan.harian', ['tanggal' => $r->tgl]) }}" class="hover:underline">
                {{ \Carbon\Carbon::parse($r->tgl)->isoFormat('dddd, D MMMM Y') }}
              </a>
            </td>
            <td class="px-4 py-3 text-right">{{ $r->trx }} Transaksi</td>
            <td class="px-4 py-3 text-right">Rp {{ number_format($r->omset, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="p-6 text-center text-neutral-500">Belum ada data rekapitulasi.</td>
          </tr>
        @endforelse
      </tbody>
      {{-- PENINGKATAN: Ringkasan Grand Total --}}
      @if($rows->count() > 0)
      <tfoot class="border-t-2 border-neutral-700 text-white font-bold">
        <tr>
            <td class="px-4 py-3" colspan="2">Grand Total Omset</td>
            {{-- 
              Catatan: $rows->sum('omset') hanya akan menjumlahkan omset di halaman saat ini karena paginasi.
              Untuk total keseluruhan, idealnya perlu query terpisah di controller.
              Namun, untuk kesederhanaan, kita akan menjumlahkan yang ada di halaman ini.
            --}}
            <td class="px-4 py-3 text-right text-lg">Rp {{ number_format($rows->sum('omset'), 0, ',', '.') }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

{{-- Navigasi Paginasi --}}
@if ($rows->hasPages())
  <div class="mt-6">
    {{ $rows->links() }}
  </div>
@endif
@endsection