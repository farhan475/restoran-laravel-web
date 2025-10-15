@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Pilih Meja</h1>
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
@foreach($mejas as $m)
  <a href="{{ route('order.pos',$m) }}"
     class="rounded-2xl p-4 text-center shadow bg-white border hover:border-gray-900 transition">
    <p class="font-semibold">{{ $m->kode }}</p>
    <p class="text-xs mt-1">
      <span class="px-2 py-0.5 rounded {{ $m->status==='kosong'?'bg-green-100 text-green-700':($m->status==='terpakai'?'bg-yellow-100 text-yellow-700':'bg-blue-100 text-blue-700') }}">
        {{ $m->status }}
      </span>
    </p>
  </a>
@endforeach
</div>
@endsection
