@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
  <h1 class="text-xl font-semibold">Meja</h1>
  <div class="flex gap-2">
    <a href="{{ route('meja.create') }}" class="px-3 py-2 rounded-lg bg-gray-900 text-white text-sm">Tambah Meja</a>
  </div>
</div>

<div class="bg-white rounded-2xl shadow p-4 mb-3">
  <form method="get" class="grid sm:grid-cols-3 gap-3">
    <div>
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari kode meja…"
             class="w-full rounded-lg border p-2.5 text-sm">
    </div>
    <div>
      <select name="status" class="w-full rounded-lg border p-2.5 text-sm">
        <option value="">Semua status</option>
        @foreach(['kosong','terpakai','reserved'] as $s)
          <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ $s }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <button class="w-full sm:w-auto px-4 py-2.5 rounded-lg bg-gray-900 text-white text-sm">Filter</button>
      @if(request()->hasAny(['q','status']))
        <a href="{{ route('meja.index') }}" class="ml-2 text-sm underline">Reset</a>
      @endif
    </div>
  </form>
</div>

<div class="overflow-x-auto bg-white rounded-2xl shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="text-left p-3">Kode</th>
        <th class="text-left p-3">Status</th>
        <th class="text-left p-3">Dibuat</th>
        <th class="p-3"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($mejas as $m)
        <tr class="border-t">
          <td class="p-3 font-medium">{{ $m->kode }}</td>
          <td class="p-3">
  <x-status-badge :status="$m->status" />
</td>

          <td class="p-3">{{ $m->created_at?->format('d M Y H:i') }}</td>
          <td class="p-3 text-right">
            <a href="{{ route('meja.edit',$m) }}" class="text-blue-600">Edit</a>
            <form method="post" action="{{ route('meja.destroy',$m) }}" class="inline" onsubmit="return confirm('Hapus meja {{ $m->kode }}?')">
              @csrf @method('delete')
              <button class="text-red-600 ml-3">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="p-3 text-center text-gray-500">Belum ada data</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $mejas->withQueryString()->links() }}</div>
@endsection
