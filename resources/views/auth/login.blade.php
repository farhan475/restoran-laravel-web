@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12">
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg p-8">
    
    {{-- Header Form --}}
    <div class="text-center mb-8">
      {{-- Anda bisa menaruh logo di sini jika mau --}}
      <h1 class="text-2xl font-bold text-white">Selamat Datang Kembali</h1>
      <p class="text-sm text-neutral-400">Silakan masuk untuk melanjutkan ke Restoran POS</p>
    </div>

    {{-- Form Login --}}
    <form method="post" action="{{ route('login') }}" class="space-y-5">
      @csrf

      {{-- Input Username --}}
      <div>
        <label for="username" class="block text-sm font-medium text-neutral-300">Username</label>
        <div class="mt-2">
          <input id="username" name="username" value="{{ old('username') }}" 
                 class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white focus:ring-2 focus:ring-inset focus:ring-blue-500 transition" 
                 autocomplete="username" required>
        </div>
        @error('username') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
      </div>

      {{-- Input Password --}}
      <div>
        <label for="password" class="block text-sm font-medium text-neutral-300">Password</label>
        <div class="mt-2">
          <input id="password" type="password" name="password" 
                 class="w-full rounded-lg bg-neutral-800 border-neutral-700 p-3 text-white focus:ring-2 focus:ring-inset focus:ring-blue-500 transition" 
                 autocomplete="current-password" required>
        </div>
        @error('password') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
      </div>

      {{-- Menampilkan error login umum (misal: "These credentials do not match...") --}}
      @if ($errors->has('gagal'))
        <p class="text-red-400 text-sm">{{ $errors->first('gagal') }}</p>
      @endif

      {{-- Tombol Submit --}}
      <div class="pt-2">
        <button type="submit" class="w-full rounded-lg bg-white hover:bg-neutral-200 text-black py-3 text-sm font-semibold transition">
          Masuk
        </button>
      </div>
    </form>
  </div>
</div>
@endsection