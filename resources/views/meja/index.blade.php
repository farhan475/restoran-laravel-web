@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Kelola Meja</h1>
    <p class="text-sm text-neutral-400">Tambah, edit, atau hapus data meja restoran.</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('meja.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
      <span>Tambah Meja</span>
    </a>
  </div>
</div>

{{-- Card untuk Filter --}}
<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg p-4 mb-6">
  <form method="get" class="grid sm:grid-cols-3 gap-4">
    <div>
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari kode meja…"
             class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-2.5 text-sm text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-blue-500 transition">
    </div>
    <div>
      <select name="status" class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 transition">
        <option value="">Semua status</option>
        @foreach(['kosong','tersedia','reserved', 'terpakai'] as $s)
          <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
            {{ ucfirst($s) }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="flex items-center gap-2">
      <button class="w-full sm:w-auto px-4 py-2.5 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Filter</button>
      @if(request()->hasAny(['q','status']))
        <a href="{{ route('meja.index') }}" class="text-sm text-blue-400 hover:underline">Reset</a>
      @endif
    </div>
  </form>
</div>

{{-- Card untuk Tabel --}}
<div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b border-neutral-700">
        <tr class="text-neutral-400">
          <th class="px-4 py-3 text-left font-semibold">Kode</th>
          <th class="px-4 py-3 text-left font-semibold">Status</th>
          <th class="px-4 py-3 text-left font-semibold">Dibuat</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="text-neutral-300">
        @forelse($mejas as $m)
          <tr class="border-b border-neutral-800 last:border-b-0">
            <td class="px-4 py-3 font-semibold text-white">{{ $m->kode }}</td>
            <td class="px-4 py-3">
              <x-status-badge :status="$m->status" />
            </td>
            <td class="px-4 py-3">{{ $m->created_at?->diffForHumans() }}</td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
              <a href="{{ route('meja.edit',$m) }}" class="font-semibold text-blue-400 hover:underline">Edit</a>
              <form method="post" action="{{ route('meja.destroy',$m) }}" class="inline ml-4" onsubmit="return confirm('Hapus meja {{ $m->kode }}?')">
                @csrf @method('delete')
                <button class="font-semibold text-red-400 hover:underline">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-6 text-center text-neutral-500">Belum ada data meja.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Paginasi dengan gaya baru --}}
@if ($mejas->hasPages())
  <div class="mt-6">
    {{ $mejas->withQueryString()->links() }}
  </div>
@endif
@endsection