@props([
  'href' => null,
  'label' => 'Kembali',
  'class' => '',
])

@php
  // Logika yang disederhanakan:
  // 1. Jika 'href' diberikan secara eksplisit, gunakan itu.
  // 2. Jika tidak, selalu kembali ke Dashboard sebagai fallback yang aman.
  $targetUrl = $href ?: route('dashboard');
  
  // Jika label default dan href tidak ada, beri label yang lebih deskriptif.
  if ($label === 'Kembali' && !$href) {
      $label = 'Kembali ke Dashboard';
  }
@endphp

{{-- Komponen Tombol Kembali dengan Gaya Vercel --}}
<a href="{{ $targetUrl }}"
   class="inline-flex items-center gap-2 rounded-lg border border-neutral-700 bg-neutral-800/50 px-3 py-1.5 text-sm text-neutral-300 transition hover:border-neutral-500 hover:text-white {{ $class }}">
  
  {{-- Ikon panah kiri --}}
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
  </svg>  
  
  <span>{{ $label }}</span>
</a>