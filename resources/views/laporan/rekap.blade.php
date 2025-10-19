@extends('layouts.app')
@section('content')

<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-semibold">Rekapitulasi Penjualan</h1>
    <a href="{{ route('laporan.rekap.pdf') }}" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm">
        Export ke PDF
    </a>
</div>

<div class="overflow-x-auto bg-white rounded-2xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">Tanggal</th>
        <th class="p-3 text-right">Jumlah Transaksi</th>
        <th class="p-3 text-right">Total Omset</th>
        {{-- HAPUS HEADER KOLOM AKSI DARI SINI --}}
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
        <tr class="border-t">
          <td class="p-3">{{ \Carbon\Carbon::parse($r->tgl)->isoFormat('dddd, D MMMM Y') }}</td>
          <td class="p-3 text-right">{{ $r->trx }}</td>
          <td class="p-3">
  {{-- Jadikan tanggal sebagai link ke Laporan Harian --}}
  <a href="{{ route('laporan.harian', ['tanggal' => $r->tgl]) }}" class="text-blue-600 hover:underline">
    {{ \Carbon\Carbon::parse($r->tgl)->isoFormat('dddd, D MMMM Y') }}
  </a>
</td>
      @empty
        <tr>
            <td colspan="3" class="p-4 text-center text-gray-500">Belum ada data rekap.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $rows->links() }}</div>

@endsection