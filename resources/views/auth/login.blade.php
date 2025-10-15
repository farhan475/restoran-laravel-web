@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-semibold mb-4">Masuk</h1>
    <form method="post" action="/login" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm">Username</label>
        <input name="username" value="{{ old('username') }}" class="mt-1 w-full rounded-lg border p-2.5" autocomplete="username">
        @error('username') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block text-sm">Password</label>
        <input type="password" name="password" class="mt-1 w-full rounded-lg border p-2.5" autocomplete="current-password">
        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <button class="w-full rounded-lg bg-gray-900 text-white py-2.5">Masuk</button>
    </form>
  </div>
</div>
@endsection
