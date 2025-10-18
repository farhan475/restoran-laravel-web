@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Pilih Meja</h1>
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
@foreach($mejas as $m)
  <a href="{{ route('order.pos',$m) }}"
     class="rounded-2xl p-4 text-center shadow bg-white border hover:border-gray-900 transition">
    <p class="font-semibold">{{ $m->kode }}</p>
    <p class="text-xs mt-1">
      {{-- PERBAIKAN: Logika badge dibuat lebih eksplisit untuk setiap status --}}
      @php
        $statusClasses = [
          'kosong'    => 'bg-green-100 text-green-700',
          'tersedia'  => 'bg-green-100 text-green-700', // Tersedia dan kosong bisa dianggap sama (siap pakai)
          'terpakai'  => 'bg-yellow-100 text-yellow-700',
          'reserved'  => 'bg-blue-100 text-blue-700',
        ][$m->status] ?? 'bg-gray-100 text-gray-700'; // Default jika ada status tak terduga
      @endphp
      <span class="px-2 py-0.5 rounded {{ $statusClasses }}">
        {{ ucfirst($m->status) }}
      </span>
    </p>
  </a>
@endforeach
</div>
@endsection