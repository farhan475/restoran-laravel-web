@extends('layouts.app')

@section('content')
  {{-- Header Dashboard Utama --}}
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-neutral-400">Ringkasan hari ini dan pintasan sesuai peran Anda.</p>
      </div>
      <div class="flex gap-2 flex-wrap">
        {{-- Tombol Aksi Cepat dengan Gaya Baru --}}
        @if($role==='waiter')
          <a href="{{ route('order.index') }}" class="px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Buat Order</a>
        @elseif($role==='kasir')
          <a href="{{ route('kasir.index') }}" class="px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Ke Kasir</a>
          <a href="{{ route('laporan.harian') }}" class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">Laporan Harian</a>
        @elseif(in_array($role,['administrator']))
          <a href="{{ route('menu.index') }}" class="px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Kelola Menu</a>
          <a href="{{ route('meja.index') }}" class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">Kelola Meja</a>
          <a href="{{ route('laporan.rekap') }}" class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">Rekap</a>
        @elseif($role==='owner')
          <a href="{{ route('laporan.harian') }}" class="px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Laporan Harian</a>
          <a href="{{ route('laporan.rekap') }}" class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-200 text-sm font-semibold transition">Rekap Keseluruhan</a>
        @endif
      </div>
    </div>
  </div>

  {{-- Kartu Statistik Utama --}}
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
      <p class="text-sm text-neutral-400">Omset Hari Ini</p>
      <p class="text-2xl font-semibold text-white mt-2">Rp {{ number_format($omsetHariIni,0,',','.') }}</p>
    </div>
    <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
      <p class="text-sm text-neutral-400">Transaksi Hari Ini</p>
      <p class="text-2xl font-semibold text-white mt-2">{{ $trxHariIni }}</p>
    </div>
    <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
      <p class="text-sm text-neutral-400">Meja Kosong</p>
      <p class="text-2xl font-semibold text-white mt-2">{{ $mejaKosong }}</p>
    </div>
    <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
      <p class="text-sm text-neutral-400">Meja Terpakai</p>
      <p class="text-2xl font-semibold text-white mt-2">{{ $mejaTerpakai }}</p>
    </div>
  </div>

  {{-- Konten Dinamis Berdasarkan Peran --}}
  @switch($role)
    @case('waiter')
      <div class="mt-6 grid lg:grid-cols-2 gap-4">
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Status Meja</h2>
            <a href="{{ route('order.index') }}" class="text-sm text-blue-400 hover:underline">Lihat semua</a>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $mejas = \App\Models\Meja::orderBy('kode')->limit(12)->get(); @endphp
            @foreach($mejas as $m)
              <a href="{{ route('order.pos',$m) }}" class="rounded-xl p-3 text-center border border-neutral-800 bg-neutral-900 hover:border-blue-500 transition">
                <p class="font-medium text-white">{{ $m->kode }}</p>
                <p class="text-xs mt-1">
                  @php
                    $statusClasses = [
                      'kosong'    => 'bg-green-500/10 text-green-400 border border-green-500/20',
                      'tersedia'  => 'bg-green-500/10 text-green-400 border border-green-500/20',
                      'terpakai'  => 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20',
                      'reserved'  => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                    ][$m->status] ?? 'bg-neutral-500/10 text-neutral-400 border border-neutral-500/20';
                  @endphp
                  <span class="px-2 py-0.5 rounded-full font-medium text-xs {{ $statusClasses }}">{{ ucfirst($m->status) }}</span>
                </p>
              </a>
            @endforeach
          </div>
        </div>
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Draft Terakhir</h2>
            <a href="{{ route('kasir.index') }}" class="text-sm text-blue-400 hover:underline">Ke kasir</a>
          </div>
          {{-- Panggil partial view untuk tabel --}}
          @include('dashboard._table_transaksi', ['items' => $drafts, 'empty_message' => 'Tidak ada draft transaksi.'])
        </div>
      </div>
      @break

    @case('kasir')
      <div class="mt-6 grid lg:grid-cols-2 gap-4">
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Menunggu Pembayaran</h2>
            <a href="{{ route('kasir.index') }}" class="text-sm text-blue-400 hover:underline">Lihat semua</a>
          </div>
          @include('dashboard._table_transaksi', ['items' => $drafts, 'empty_message' => 'Semua transaksi sudah dibayar.'])
        </div>
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.harian') }}" class="text-sm text-blue-400 hover:underline">Laporan Harian</a>
          </div>
          @include('dashboard._table_transaksi', ['items' => $recentPaid, 'empty_message' => 'Belum ada transaksi selesai hari ini.'])
        </div>
      </div>
      @break

    @case('administrator')
      <div class="mt-6 grid lg:grid-cols-3 gap-4">
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Ringkas Operasional</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm text-blue-400 hover:underline">Rekap</a>
          </div>
          <ul class="text-sm space-y-3 text-neutral-300">
            <li class="flex justify-between">Menu aktif: <span class="font-semibold text-white">{{ $jumlahMenu }}</span></li>
            <li class="flex justify-between">Total meja: <span class="font-semibold text-white">{{ $jumlahMeja }}</span></li>
            <li class="flex justify-between">Draft berjalan: <span class="font-semibold text-white">{{ $drafts->count() }}</span></li>
          </ul>
        </div>
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5 lg:col-span-2">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Top Menu</h2>
            <a href="{{ route('menu.index') }}" class="text-sm text-blue-400 hover:underline">Kelola Menu</a>
          </div>
          {{-- Tabel Top Menu dengan gaya baru --}}
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="border-b border-neutral-700"><tr class="text-neutral-400">
                <th class="p-2 text-left font-semibold">Menu</th>
                <th class="p-2 text-right font-semibold">Qty</th>
                <th class="p-2 text-right font-semibold">Omzet</th>
              </tr></thead>
              <tbody class="text-neutral-300">
                @forelse($topMenus as $t)
                <tr class="border-b border-neutral-800"><td class="p-2 font-medium text-white">{{ $t->nama }}</td><td class="p-2 text-right">{{ $t->jml }}</td><td class="p-2 text-right">Rp {{ number_format($t->omzet,0,',','.') }}</td></tr>
                @empty
                <tr><td colspan="3" class="p-3 text-center text-neutral-500">Belum ada data penjualan.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5 lg:col-span-3">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm text-blue-400 hover:underline">Lihat rekap</a>
          </div>
          @include('dashboard._table_transaksi', ['items' => $recentPaid, 'empty_message' => 'Belum ada transaksi selesai hari ini.'])
        </div>
      </div>
      @break

    @case('owner')
      <div class="mt-6">
        <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-white">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm text-blue-400 hover:underline">Lihat rekap</a>
          </div>
          @include('dashboard._table_transaksi', ['items' => $recentPaid, 'empty_message' => 'Belum ada transaksi selesai hari ini.'])
        </div>
      </div>
      @break
  @endswitch
@endsection