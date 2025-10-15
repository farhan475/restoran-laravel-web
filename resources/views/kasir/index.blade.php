@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Daftar Draft Transaksi</h1>
<div class="overflow-x-auto bg-white rounded-2xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100"><tr>
      <th class="p-3 text-left">ID</th>
      <th class="p-3 text-left">Meja</th>
      <th class="p-3 text-right">Total</th>
      <th class="p-3 text-left">Waktu</th>
      <th class="p-3"></th>
    </tr></thead>
    <tbody>
      @foreach($drafts as $d)
      <tr class="border-t">
        <td class="p-3">{{ $d->id }}</td>
        <td class="p-3">{{ $d->meja }}</td>
        <td class="p-3 text-right">Rp {{ number_format($d->total,0,',','.') }}</td>
        <td class="p-3">{{ $d->created_at }}</td>
        <td class="p-3 text-right">
          <a href="{{ route('kasir.bayar',$d->id) }}" class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-sm">Bayar</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $drafts->links() }}</div>
@endsection
