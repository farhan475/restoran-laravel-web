<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite('resources/css/app.css')
  <title>Restoran POS</title>
</head>
{{-- PERUBAHAN: Body dengan latar belakang hitam dan teks terang --}}
<body class="bg-black text-neutral-200 min-h-screen">
  
  {{-- PERUBAHAN: Header dengan gaya Vercel --}}
  <header class="bg-black/80 backdrop-blur sticky top-0 z-10 border-b border-neutral-800">
    <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
        {{-- Logo segitiga seperti Vercel --}}
        <svg width="24" height="24" viewBox="0 0 76 65" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M37.5274 0L75.0548 65H0L37.5274 0Z" fill="white"/></svg>
        <span class="font-semibold text-white">Restoran POS</span>
      </a>
      @auth
      <div class="flex items-center gap-3">
        {{-- PERUBAHAN: Badge info pengguna --}}
        <span class="hidden sm:block text-sm px-2.5 py-1 rounded-lg bg-neutral-800 text-neutral-300">{{ auth()->user()->name }} — {{ auth()->user()->role }}</span>
        <form action="{{ route('logout') }}" method="post" class="m-0">
          @csrf
          {{-- PERUBAHAN: Tombol logout --}}
          <button class="px-3 py-1.5 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition">Logout</button>
        </form>
      </div>
      @endauth
    </div>
  </header>

  <main class="mx-auto max-w-7xl p-4">
    {{-- PERUBAHAN: Tombol kembali dengan gaya baru --}}
    @if(!request()->routeIs('dashboard') && !request()->routeIs('login'))
      <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3 py-1.5 border border-neutral-700 text-neutral-300 rounded-lg text-sm hover:border-neutral-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Dashboard
        </a>
      </div>
    @endif

    {{-- PERUBAHAN: Notifikasi sukses dengan gaya baru --}}
    @if(session('ok')) 
      <div class="mb-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3">{{ session('ok') }}</div> 
    @endif
    
    {{-- Menampilkan pesan error validasi atau error lainnya --}}
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
  </main>
  
  @stack('scripts')
</body>
</html>