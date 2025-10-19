@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
  {{-- Header Form --}}
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-white">{{ $menu->exists ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
    <p class="text-sm text-neutral-400">Isi detail item menu di bawah ini.</p>
  </div>

  {{-- Card Form --}}
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
    <form method="post" action="{{ $menu->exists ? route('menu.update',$menu) : route('menu.store') }}">
      @csrf
      @if($menu->exists) @method('put') @endif

      <div class="p-6 space-y-5">
        {{-- Input Nama Menu --}}
        <div>
          <label for="nama" class="block text-sm font-medium text-neutral-300">Nama Menu</label>
          <div class="mt-2">
            <input id="nama" name="nama" value="{{ old('nama',$menu->nama) }}" 
                   class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-inset focus:ring-blue-500 transition"
                   placeholder="Contoh: Nasi Goreng Spesial" required>
          </div>
          @error('nama') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Input Harga --}}
        <div>
          <label for="harga" class="block text-sm font-medium text-neutral-300">Harga</label>
          <div class="mt-2">
            <input id="harga" name="harga" type="number" value="{{ old('harga',$menu->harga) }}" 
                   class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-inset focus:ring-blue-500 transition"
                   placeholder="Contoh: 25000" min="0" required>
          </div>
          @error('harga') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Input Status Aktif --}}
        <div>
          <label for="aktif" class="block text-sm font-medium text-neutral-300">Status</label>
          <div class="mt-2">
            <select id="aktif" name="aktif" class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white focus:ring-2 focus:ring-inset focus:ring-blue-500 transition">
              <option value="1" {{ old('aktif',$menu->aktif) == 1 ? 'selected':'' }}>Aktif (Bisa dijual)</option>
              <option value="0" {{ old('aktif',$menu->aktif) == 0 ? 'selected':'' }}>Nonaktif (Disembunyikan)</option>
            </select>
          </div>
          @error('aktif') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
        </div>
      </div>
      
      {{-- Footer Form dengan Tombol Aksi --}}
      <div class="px-6 py-4 bg-neutral-900 border-t border-neutral-800 rounded-b-xl text-right">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">
          {{ $menu->exists ? 'Simpan Perubahan' : 'Buat Menu' }}
        </button>
      </div>
    </form>
  </div>
</div>
@endsection