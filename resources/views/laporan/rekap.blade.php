@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Rekap Harian</h1>
<div class="overflow-x-auto bg-white rounded-2xl shadow">
<table class="min-w-full text-sm">
  <thead class="bg-gray-100"><tr><th class="p-3 text-left">Tanggal</th><th class="p-3 text-left">Transaksi</th><th class="p-3 text-left">Omset</th></tr></thead>
  <tbody>
    @foreach($rows as $r)
      <tr class="border-t"><td class="p-3">{{ $r->tgl }}</td><td class="p-3">{{ $r->trx }}</td><td class="p-3">Rp {{ number_format($r->omset,0,',','.') }}</td></tr>
    @endforeach
  </tbody>
</table>
</div>
<div class="mt-4">{{ $rows->links() }}</div>
@endsection
