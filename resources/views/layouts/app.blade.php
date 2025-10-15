<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite('resources/css/app.css')
  <title>Restoran POS</title>
</head>
<body class="bg-gradient-to-b from-gray-50 to-white text-gray-800 min-h-screen">
  <header class="bg-white/80 backdrop-blur border-b">
    <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
      <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <span class="inline-block h-8 w-8 rounded-xl bg-gray-900"></span>
        <span class="font-semibold">Restoran POS</span>
      </a>
      @auth
      <div class="flex items-center gap-2">
        <span class="hidden sm:block text-sm px-2 py-1 rounded-lg bg-gray-100">{{ auth()->user()->name }} — {{ auth()->user()->role }}</span>
        <form action="{{ route('logout') }}" method="post" class="m-0">
          @csrf
          <button class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-sm">Logout</button>
        </form>
      </div>
      @endauth
    </div>
  </header>

  <main class="mx-auto max-w-7xl p-4">
    @if(!request()->routeIs('dashboard') && !request()->routeIs('login'))
  <div class="mb-3">
    {{-- bisa override via @section('back_href') di halaman tertentu --}}
    <x-back :href="trim($__env->yieldContent('back_href')) ?: null" />
  </div>
@endif

    @if(session('ok')) <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-2">{{ session('ok') }}</div> @endif
    @yield('content')
  </main>

  <footer class="mx-auto max-w-7xl px-4 pb-6 text-xs text-gray-500">
    Dibuat dengan Laravel 12 + Tailwind
  </footer>
</body>
</html>
