@extends('layouts.app')

@section('content')
<div class="mb-6">
  <h1 class="text-3xl font-bold text-white">Buat Order Baru</h1>
  <p class="text-sm text-neutral-400">Pilih meja untuk memulai atau melanjutkan transaksi.</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
  @foreach($mejas as $m)
    @php
      $isReserved = $m->status === 'reserved';
      $cardClasses = $isReserved
          ? 'border-neutral-700 bg-neutral-900 cursor-not-allowed'
          : 'border-neutral-800 bg-neutral-900 hover:border-blue-500 transition';
      $textClasses = $isReserved ? 'text-neutral-500' : 'text-white';
    @endphp
    
    <{{ $isReserved ? 'div' : 'a' }}
        @if(!$isReserved) href="{{ route('order.pos', $m) }}" @endif
        class="rounded-xl p-4 text-center border {{ $cardClasses }}"
        @if($isReserved) title="Meja ini sedang di-reserve" @endif
    >
      <p class="font-semibold text-lg {{ $textClasses }}">{{ $m->kode }}</p>
      <div class="text-xs mt-2">
        <x-status-badge :status="$m->status" />
      </div>
    </{{ $isReserved ? 'div' : 'a' }}>
  @endforeach
</div>
@endsection