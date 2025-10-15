@props([
  'href' => null,
  'label' => 'Kembali',
  'class' => '',
])

@php
  $fallback = route('dashboard');
  $prev = url()->previous();
  $safePrev = $prev && $prev !== url()->current() ? $prev : $fallback;
  $target = $href ?: $safePrev;
@endphp

<a href="{{ $target }}"
   onclick="try{ if(document.referrer && new URL(document.referrer).origin===location.origin){ history.back(); return false; } }catch(e){}"
   class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm hover:border-gray-900 {{ $class }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
  </svg>
  <span>{{ $label }}</span>
</a>
