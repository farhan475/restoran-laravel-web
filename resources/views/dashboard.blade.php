  @extends('layouts.app')

  @section('content')
  <div class="bg-white rounded-2xl shadow p-5 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <p class="text-sm text-gray-500">Ringkasan hari ini dan pintasan sesuai peran</p>
      </div>
      <div class="flex gap-2 flex-wrap">
        @if($role==='waiter')
          <a href="{{ route('order.index') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm">Buat Order</a>
        @elseif($role==='kasir')
          <a href="{{ route('kasir.index') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm">Ke Kasir</a>
          <a href="{{ route('laporan.harian') }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-900 text-sm">Laporan Harian</a>
        @elseif(in_array($role,['administrator']))
          <a href="{{ route('menu.index') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm">Kelola Menu</a>
          <a href="{{ route('meja.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-900 text-sm">Kelola Meja</a>
          <a href="{{ route('laporan.rekap') }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-900 text-sm">Rekap</a>
        @elseif($role==='owner') {{-- Tambahkan blok khusus untuk Owner --}}
          <a href="{{ route('laporan.harian') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm">Laporan Harian</a>
          <a href="{{ route('laporan.rekap') }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-900 text-sm">Rekap Keseluruhan</a>
        @endif
      </div>
    </div>
  </div>

  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl shadow p-5">
      <p class="text-sm text-gray-500">Omset Hari Ini</p>
      <p class="text-2xl font-semibold mt-2">Rp {{ number_format($omsetHariIni,0,',','.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
      <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
      <p class="text-2xl font-semibold mt-2">{{ $trxHariIni }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
      <p class="text-sm text-gray-500">Meja Kosong</p>
      <p class="text-2xl font-semibold mt-2">{{ $mejaKosong }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
      <p class="text-sm text-gray-500">Meja Terpakai</p>
      <p class="text-2xl font-semibold mt-2">{{ $mejaTerpakai }}</p>
    </div>
  </div>

  @switch($role)
    @case('waiter')
      <div class="mt-6 grid lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Status Meja</h2>
            <a href="{{ route('order.index') }}" class="text-sm underline">Lihat semua</a>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $mejas = \App\Models\Meja::orderBy('kode')->limit(12)->get(); @endphp
            @foreach($mejas as $m)
              <a href="{{ route('order.pos',$m) }}" class="rounded-xl p-3 text-center border bg-white hover:border-gray-900">
                <p class="font-medium">{{ $m->kode }}</p>
                <p class="text-xs mt-1">
                  <span class="px-2 py-0.5 rounded {{ $m->status==='kosong'?'bg-green-100 text-green-700':($m->status==='terpakai'?'bg-yellow-100 text-yellow-700':'bg-blue-100 text-blue-700') }}">{{ $m->status }}</span>
                </p>
              </a>
            @endforeach
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Draft Terakhir</h2>
            <a href="{{ route('kasir.index') }}" class="text-sm underline">Ke kasir</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Meja</th><th class="p-2 text-right">Total</th><th class="p-2 text-left">Waktu</th></tr>
              </thead>
              <tbody>
                @foreach($drafts as $d)
                <tr class="border-t">
                  <td class="p-2">{{ $d->id }}</td>
                  <td class="p-2">{{ $d->meja }}</td>
                  <td class="p-2 text-right">Rp {{ number_format($d->total,0,',','.') }}</td>
                  <td class="p-2">{{ $d->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @break

    @case('kasir')
      <div class="mt-6 grid lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Menunggu Pembayaran</h2>
            <a href="{{ route('kasir.index') }}" class="text-sm underline">Lihat semua</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Meja</th><th class="p-2 text-right">Total</th><th class="p-2 text-left">Waktu</th></tr>
              </thead>
              <tbody>
                @foreach($drafts as $d)
                <tr class="border-t">
                  <td class="p-2">{{ $d->id }}</td>
                  <td class="p-2">{{ $d->meja }}</td>
                  <td class="p-2 text-right">Rp {{ number_format($d->total,0,',','.') }}</td>
                  <td class="p-2">{{ $d->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.harian') }}" class="text-sm underline">Laporan Harian</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Meja</th><th class="p-2 text-right">Total</th><th class="p-2 text-left">Waktu</th></tr>
              </thead>
              <tbody>
                @foreach($recentPaid as $r)
                <tr class="border-t">
                  <td class="p-2">{{ $r->id }}</td>
                  <td class="p-2">{{ $r->meja }}</td>
                  <td class="p-2 text-right">Rp {{ number_format($r->total,0,',','.') }}</td>
                  <td class="p-2">{{ $r->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @break

    @case('administrator')
      <div class="mt-6 grid lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Ringkas Operasional</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm underline">Rekap</a>
          </div>
          <ul class="text-sm space-y-2">
            <li>Menu aktif: <span class="font-medium">{{ $jumlahMenu }}</span></li>
            <li>Total meja: <span class="font-medium">{{ $jumlahMeja }}</span></li>
            <li>Draft berjalan: <span class="font-medium">{{ $drafts->count() }}</span></li>
          </ul>
        </div>

        <div class="bg-white rounded-2xl shadow p-5 lg:col-span-2">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Top Menu</h2>
            <a href="{{ route('menu.index') }}" class="text-sm underline">Kelola Menu</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">Menu</th><th class="p-2 text-right">Qty</th><th class="p-2 text-right">Omzet</th></tr>
              </thead>
              <tbody>
                @foreach($topMenus as $t)
                <tr class="border-t">
                  <td class="p-2">{{ $t->nama }}</td>
                  <td class="p-2 text-right">{{ $t->jml }}</td>
                  <td class="p-2 text-right">Rp {{ number_format($t->omzet,0,',','.') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5 lg:col-span-3">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm underline">Lihat rekap</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Meja</th><th class="p-2 text-right">Total</th><th class="p-2 text-left">Waktu</th></tr>
              </thead>
              <tbody>
                @foreach($recentPaid as $r)
                <tr class="border-t">
                  <td class="p-2">{{ $r->id }}</td>
                  <td class="p-2">{{ $r->meja }}</td>
                  <td class="p-2 text-right">Rp {{ number_format($r->total,0,',','.') }}</td>
                  <td class="p-2">{{ $r->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @break
       @case('owner') {{-- Tambahkan case khusus untuk Owner --}}
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Transaksi Selesai Terakhir</h2>
            <a href="{{ route('laporan.rekap') }}" class="text-sm underline">Lihat rekap</a>
          </div>
          {{-- Panggil partial view yang sudah ada --}}
          @include('dashboard._table_transaksi', ['items' => $recentPaid, 'empty_message' => 'Belum ada transaksi selesai hari ini.'])
        </div>
    </div>
    @break
  @endswitch
  @endsection
