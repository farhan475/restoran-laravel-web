@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Kelola Menu</h1>
    <p class="text-sm text-neutral-400">Tambah, edit, atau hapus item menu yang dijual.</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('menu.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
      <span>Tambah Menu</span>
    </a>
  </div>
</div>

<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b border-neutral-700">
        <tr class="text-neutral-400">
          <th class="px-4 py-3 text-left font-semibold">Nama</th>
          <th class="px-4 py-3 text-right font-semibold">Harga</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="text-neutral-300">
        @forelse($menus as $m)
        <tr class="border-b border-neutral-800 last:border-b-0">
          <td class="px-4 py-3 font-semibold text-white">{{ $m->nama }}</td>
          <td class="px-4 py-3 text-right">Rp {{ number_format($m->harga,0,',','.') }}</td>
          <td class="px-4 py-3 text-center">
            @if($m->aktif)
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border bg-green-500/10 text-green-400 border-green-500/20">Aktif</span>
            @else
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border bg-neutral-500/10 text-neutral-400 border-neutral-500/20">Nonaktif</span>
            @endif
          </td>
          <td class="px-4 py-3 text-right whitespace-nowrap">
            <a href="{{ route('menu.edit',$m) }}" class="font-semibold text-blue-400 hover:underline">Edit</a>
            <form method="post" action="{{ route('menu.destroy',$m) }}" class="inline ml-4" onsubmit="return confirm('Hapus menu \'{{ $m->nama }}\'?')">
              @csrf @method('delete')
              <button class="font-semibold text-red-400 hover:underline">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="p-6 text-center text-neutral-500">Belum ada data menu.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if ($menus->hasPages())
  <div class="mt-6">
    {{ $menus->links() }}
  </div>
@endif
@endsection