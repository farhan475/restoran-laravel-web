@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Menu</h1>
  <a href="{{ route('menu.create') }}" class="px-3 py-2 rounded-lg bg-gray-900 text-white text-sm">Tambah</a>
</div>
<div class="overflow-x-auto bg-white rounded-2xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="text-left p-3">Nama</th>
        <th class="text-left p-3">Harga</th>
        <th class="text-left p-3">Aktif</th>
        <th class="p-3"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($menus as $m)
      <tr class="border-t">
        <td class="p-3">{{ $m->nama }}</td>
        <td class="p-3">Rp {{ number_format($m->harga,0,',','.') }}</td>
        <td class="p-3">
          <span class="px-2 py-1 rounded {{ $m->aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $m->aktif ? 'aktif' : 'nonaktif' }}</span>
        </td>
        <td class="p-3 text-right">
          <a href="{{ route('menu.edit',$m) }}" class="text-blue-600">Edit</a>
          <form method="post" action="{{ route('menu.destroy',$m) }}" class="inline">
            @csrf @method('delete')
            <button class="text-red-600 ml-2">Hapus</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $menus->links() }}</div>
@endsection
