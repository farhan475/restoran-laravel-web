@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Laporan Harian {{ $tanggal }}</h1>
<div class="overflow-x-auto bg-white rounded-2xl shadow">
<table class="min-w-full text-sm">
  <thead class="bg-gray-100"><tr><th class="p-3 text-left">ID</th><th class="p-3 text-left">Total</th><th class="p-3 text-left">Dibayar</th></tr></thead>
  <tbody>
    @foreach($rows as $r)
      <tr class="border-t"><td class="p-3">{{ $r->id }}</td><td class="p-3">Rp {{ number_format($r->total,0,',','.') }}</td><td class="p-3">Rp {{ number_format($r->dibayar,0,',','.') }}</td></tr>
    @endforeach
  </tbody>
</table>
</div>
@endsection
