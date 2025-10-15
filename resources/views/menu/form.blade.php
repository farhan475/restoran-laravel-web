@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">{{ $menu->exists ? 'Edit Menu' : 'Tambah Menu' }}</h1>
<form method="post" action="{{ $menu->exists ? route('menu.update',$menu) : route('menu.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-lg">
  @csrf
  @if($menu->exists) @method('put') @endif

  <label class="block text-sm">Nama</label>
  <input name="nama" value="{{ old('nama',$menu->nama) }}" class="mt-1 w-full rounded-lg border p-2.5">
  @error('nama') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

  <label class="block text-sm mt-4">Harga</label>
  <input name="harga" type="number" value="{{ old('harga',$menu->harga) }}" class="mt-1 w-full rounded-lg border p-2.5">
  @error('harga') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

  <label class="block text-sm mt-4">Aktif</label>
  <select name="aktif" class="mt-1 w-full rounded-lg border p-2.5">
    <option value="1" {{ old('aktif',$menu->aktif) ? 'selected':'' }}>Aktif</option>
    <option value="0" {{ !old('aktif',$menu->aktif) ? 'selected':'' }}>Nonaktif</option>
  </select>
  @error('aktif') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

  <button class="mt-6 px-4 py-2 rounded-lg bg-gray-900 text-white">{{ $menu->exists ? 'Simpan' : 'Buat' }}</button>
</form>
@endsection
