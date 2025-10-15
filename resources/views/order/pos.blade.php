@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Order — Meja {{ $meja->kode }}</h1>
  <a href="{{ route('order.index') }}" class="text-sm underline">Ganti Meja</a>
</div>

<div class="grid lg:grid-cols-2 gap-4">
  <!-- Daftar menu -->
  <div class="bg-white rounded-2xl shadow p-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="font-semibold">Menu</h2>
      <input id="qty" type="number" value="1" min="1" class="w-20 rounded-lg border p-2 text-sm">
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
      @foreach($menus as $mn)
        <button data-id="{{ $mn->id }}" data-harga="{{ $mn->harga }}"
          class="add-item rounded-xl border p-3 text-left hover:border-gray-900 transition">
          <p class="font-medium">{{ $mn->nama }}</p>
          <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($mn->harga,0,',','.') }}</p>
        </button>
      @endforeach
    </div>
  </div>

  <!-- Keranjang -->
  <div class="bg-white rounded-2xl shadow p-4">
    <h2 class="font-semibold mb-3">Keranjang</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100"><tr>
          <th class="p-2 text-left">Item</th>
          <th class="p-2 text-right">Qty</th>
          <th class="p-2 text-right">Harga</th>
          <th class="p-2 text-right">Subtotal</th>
        </tr></thead>
        <tbody id="cart-body">
          @foreach($items as $it)
          <tr class="border-t">
            <td class="p-2">{{ $it->nama }}</td>
            <td class="p-2 text-right">{{ $it->jumlah }}</td>
            <td class="p-2 text-right">{{ number_format($it->harga_satuan,0,',','.') }}</td>
            <td class="p-2 text-right">{{ number_format($it->subtotal,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="border-t font-semibold">
            <td class="p-2" colspan="3">Total</td>
            <td class="p-2 text-right" id="total">{{ number_format($total,0,',','.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="mt-4 text-right">
      <a href="{{ route('kasir.index') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm">Ke Kasir</a>
    </div>
  </div>
</div>

<form id="addForm" class="hidden" method="post" action="{{ url('transaksi/add-item') }}">
  @csrf
  <input type="hidden" name="transaksi_id" value="{{ $transaksiId }}">
  <input type="hidden" name="menu_id" id="menu_id">
  <input type="hidden" name="qty" id="qty_hidden">
</form>

<script>
document.querySelectorAll('.add-item').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    const menuId = btn.dataset.id;
    const qty = Math.max(1, parseInt(document.getElementById('qty').value||'1'));
    document.getElementById('menu_id').value = menuId;
    document.getElementById('qty_hidden').value = qty;

    const form = document.getElementById('addForm');
    const fd = new FormData(form);
    const res = await fetch(form.action, {method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'}});
    if(res.ok){
      location.reload(); // simple refresh agar tabel sinkron
    } else {
      alert('Gagal menambah item');
    }
  });
});
</script>
@endsection
