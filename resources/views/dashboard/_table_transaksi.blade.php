{{-- File: resources/views/dashboard/_table_transaksi.blade.php --}}

<div class="overflow-x-auto">
  <table class="min-w-full text-sm">
    {{-- Header Tabel --}}
    <thead class="border-b border-gray-700">
      <tr class="text-gray-400">
        <th class="p-2 text-left font-semibold">ID</th>
        <th class="p-2 text-left font-semibold">Meja</th>
        <th class="p-2 text-right font-semibold">Total</th>
        <th class="p-2 text-left font-semibold">Waktu</th>
      </tr>
    </thead>

    {{-- Isi Tabel --}}
    <tbody class="text-gray-300">
      @forelse($items as $item)
      <tr class="border-b border-gray-800">
        <td class="p-2 font-medium text-white">{{ $item->id }}</td>
        {{-- Menggunakan null coalescing operator untuk keamanan jika properti 'meja' tidak ada --}}
        <td class="p-2">{{ $item->meja ?? 'N/A' }}</td>
        <td class="p-2 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
        <td class="p-2">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="p-3 text-center text-gray-500">
          {{ $empty_message ?? 'Belum ada data' }}
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>