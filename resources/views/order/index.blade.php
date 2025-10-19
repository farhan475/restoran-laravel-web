@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Pilih Meja</h1>
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
  @foreach($mejas as $m)
    {{-- Jika statusnya 'reserved', buat elemennya tidak bisa diklik --}}
    @if ($m->status === 'reserved')
      <div class="rounded-2xl p-4 text-center shadow bg-gray-200 border cursor-not-allowed" title="Meja ini sedang di-reserve">
        <p class="font-semibold text-gray-500">{{ $m->kode }}</p>
        <p class="text-xs mt-1">
          <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700">Reserved</span>
        </p>
      </div>
    @else
      {{-- Jika status lain, biarkan sebagai link yang bisa diklik --}}
      <a href="{{ route('order.pos', $m) }}"
         class="rounded-2xl p-4 text-center shadow bg-white border hover:border-gray-900 transition">
        <p class="font-semibold">{{ $m->kode }}</p>
        <p class="text-xs mt-1">
          @php
            $statusClasses = [
              'kosong'    => 'bg-green-100 text-green-700',
              'tersedia'  => 'bg-green-100 text-green-700',
              'terpakai'  => 'bg-yellow-100 text-yellow-700',
            ][$m->status] ?? 'bg-gray-100 text-gray-700';
          @endphp
          <span class="px-2 py-0.5 rounded {{ $statusClasses }}">{{ ucfirst($m->status) }}</span>
        </p>
      </a>
    @endif
  @endforeach
</div>
@endsection