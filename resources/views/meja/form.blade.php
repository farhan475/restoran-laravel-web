@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
  {{-- Header Form --}}
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-white">{{ $meja->exists ? 'Edit Meja' : 'Tambah Meja Baru' }}</h1>
    <p class="text-sm text-neutral-400">Isi detail meja di bawah ini.</p>
  </div>

  {{-- Card Form --}}
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg">
    <form method="post" action="{{ $meja->exists ? route('meja.update',$meja) : route('meja.store') }}">
      @csrf
      @if($meja->exists) @method('put') @endif

      <div class="p-6 space-y-5">
        {{-- Input Kode Meja --}}
        <div>
          <label for="kode" class="block text-sm font-medium text-neutral-300">Kode Meja</label>
          <div class="mt-2">
            <input id="kode" name="kode" value="{{ old('kode',$meja->kode) }}" 
                   class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-inset focus:ring-blue-500 transition"
                   placeholder="Contoh: M001" required>
          </div>
          @error('kode') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Input Status --}}
        <div>
          <label for="status" class="block text-sm font-medium text-neutral-300">Status Awal</label>
          <div class="mt-2">
            <select id="status" name="status" class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white focus:ring-2 focus:ring-inset focus:ring-blue-500 transition">
              @foreach(['tersedia', 'kosong', 'reserved', 'terpakai'] as $s)
                <option value="{{ $s }}" {{ old('status',$meja->status ?? 'tersedia') === $s ? 'selected' : '' }}>
                  {{ ucfirst($s) }}
                </option>
              @endforeach
            </select>
          </div>
          @error('status') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
        </div>
      </div>
      
      {{-- Footer Form dengan Tombol Aksi --}}
      <div class="px-6 py-4 bg-neutral-900 border-t border-neutral-800 rounded-b-xl text-right">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">
          {{ $meja->exists ? 'Simpan Perubahan' : 'Buat Meja' }}
        </button>
      </div>
    </form>
  </div>
</div>
@endsection