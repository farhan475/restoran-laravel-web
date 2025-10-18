@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">{{ $meja->exists ? 'Edit Meja' : 'Tambah Meja' }}</h1>
  <a href="{{ route('meja.index') }}" class="text-sm underline">Kembali</a>
</div>

<form method="post"
      action="{{ $meja->exists ? route('meja.update',$meja) : route('meja.store') }}"
      class="bg-white rounded-2xl shadow p-6 max-w-xl space-y-4">
  @csrf
  @if($meja->exists) @method('put') @endif

  <div>
    <label class="block text-sm">Kode</label>
    <input name="kode" value="{{ old('kode',$meja->kode) }}" class="mt-1 w-full rounded-lg border p-2.5">
    @error('kode') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm">Status</label>
    <select name="status" class="mt-1 w-full rounded-lg border p-2.5">
      {{-- Menambahkan 'terpakai' ke dalam daftar pilihan status --}}
      @foreach(['kosong','tersedia','reserved', 'terpakai'] as $s)
        {{-- Menggunakan default 'tersedia' untuk meja baru agar sesuai skema DB --}}
        <option value="{{ $s }}" {{ old('status',$meja->status ?? 'tersedia') === $s ? 'selected' : '' }}>
          {{ ucfirst($s) }} {{-- Menggunakan ucfirst agar tampilan lebih rapi --}}
        </option>
      @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="pt-2">
    <button class="px-4 py-2 rounded-lg bg-gray-900 text-white">{{ $meja->exists ? 'Simpan' : 'Buat' }}</button>
  </div>
</form>
@endsection