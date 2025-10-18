@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Daftar Transaksi Menunggu Pembayaran</h1>
  {{-- Tombol Kembali bisa diarahkan ke Dashboard --}}
  <a href="{{ route('dashboard') }}" class="text-sm underline">Kembali ke Dashboard</a>
</div>

{{-- Menampilkan notifikasi sukses setelah pembayaran --}}

<div class="overflow-x-auto bg-white rounded-2xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">ID Transaksi</th>
        <th class="p-3 text-left">Meja</th>
        <th class="p-3 text-right">Total Tagihan</th>
        <th class="p-3 text-left">Waktu Order</th>
        <th class="p-3"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($drafts as $d)
      <tr class="border-t">
        <td class="p-3 font-medium">{{ $d->id }}</td>
        <td class="p-3">{{ $d->meja }}</td>
        <td class="p-3 text-right">Rp {{ number_format($d->total, 0, ',', '.') }}</td>
        <td class="p-3">{{ \Carbon\Carbon::parse($d->created_at)->diffForHumans() }}</td>
        <td class="p-3 text-right">
          {{-- Tombol ini akan mengarahkan ke halaman pembayaran detail --}}
          <a href="{{ route('kasir.bayar', $d->id) }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold">
            Proses Bayar
          </a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="p-4 text-center text-gray-500">
          Tidak ada transaksi yang menunggu pembayaran.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Navigasi Paginasi --}}
<div class="mt-4">
  {{ $drafts->links() }}
</div>
@endsection