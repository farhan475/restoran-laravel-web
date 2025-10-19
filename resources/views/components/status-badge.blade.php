{{-- resources/views/components/status-badge.blade.php --}}
@props(['status' => 'kosong'])

@php
  // Palet warna baru yang cocok untuk tema gelap (gaya Vercel)
  $map = [
    'kosong'    => 'bg-green-500/10 text-green-400 border-green-500/20',
    'tersedia'  => 'bg-green-500/10 text-green-400 border-green-500/20',
    'terpakai'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
    'reserved'  => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    'draft'     => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
    'bayar'     => 'bg-green-500/10 text-green-400 border-green-500/20',
    'batal'     => 'bg-red-500/10 text-red-400 border-red-500/20',
  ];
  
  // Kelas fallback jika status tidak ditemukan di map
  $fallbackClasses = 'bg-neutral-500/10 text-neutral-400 border-neutral-500/20';
@endphp

{{-- Badge dengan bentuk pil, padding yang disesuaikan, dan border --}}
<span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $map[$status] ?? $fallbackClasses }}">
  {{-- Teks dengan huruf kapital di awal agar lebih rapi --}}
  {{ ucfirst($status) }}
</span>