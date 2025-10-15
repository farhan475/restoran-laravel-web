{{-- resources/views/components/status-badge.blade.php --}}
@props(['status' => 'kosong'])

@php
  $map = [
    'kosong'   => 'bg-green-100 text-green-700',
    'terpakai' => 'bg-yellow-100 text-yellow-700',
    'reserved' => 'bg-blue-100 text-blue-700',
  ];
@endphp

<span class="px-2 py-1 rounded text-xs {{ $map[$status] ?? 'bg-gray-100 text-gray-700' }}">
  {{ $status }}
</span>
