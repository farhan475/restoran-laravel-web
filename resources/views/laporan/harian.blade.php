@extends('layouts.app')

@section('content')
{{-- Di laporan/harian.blade.php --}}
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-semibold">Laporan Harian {{ $tanggal }}</h1>
    <a href="{{ route('laporan.harian.export', ['tanggal' => $tanggal]) }}" class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm">
        Export ke Excel
    </a>
     <a href="{{ route('laporan.harian.pdf', ['tanggal' => $tanggal]) }}" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm">
            Export ke PDF
        </a>  
</div>
<div class="overflow-x-auto bg-white rounded-2xl shadow">
<table class="min-w-full text-sm">
  <thead class="bg-gray-100">
    <tr>
      <th class="p-3 text-left">ID</th>
      <th class="p-3 text-left">Total</th>
      <th class="p-3 text-left">Dibayar</th>
      <th class="p-3"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($rows as $r)
      <tr class="border-t">
        <td class="p-3">{{ $r->id }}</td>
        <td class="p-3">Rp {{ number_format($r->total,0,',','.') }}</td>
        <td class="p-3">Rp {{ number_format($r->dibayar,0,',','.') }}</td>
        <td class="p-3 text-right">
            <a href="{{ route('kasir.struk.print', $r->id) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                Print Struk
            </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
</div>
@endsection
