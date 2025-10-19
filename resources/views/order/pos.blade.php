@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-3xl font-bold text-white">Order <span class="text-neutral-400">—</span> Meja {{ $meja->kode }}</h1>
    <p class="text-sm text-neutral-400">Pilih item menu untuk ditambahkan ke keranjang.</p>
  </div>
  <a href="{{ route('order.index') }}" class="text-sm text-blue-400 hover:underline">Ganti Meja</a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg p-5">
    <h2 class="font-semibold text-white mb-4">Pilih Menu</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
      @foreach($menus as $mn)
        <button data-id="{{ $mn->id }}" data-nama="{{ $mn->nama }}" data-harga="{{ $mn->harga }}"
          class="add-item-js rounded-xl border border-neutral-800 bg-neutral-900 p-3 text-left hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          <p class="font-medium text-white">{{ $mn->nama }}</p>
          <p class="text-xs text-neutral-400 mt-1">Rp {{ number_format($mn->harga,0,',','.') }}</p>
        </button>
      @endforeach
    </div>
  </div>

  <div class="border border-neutral-800 bg-neutral-900/50 rounded-xl shadow-lg p-5 flex flex-col">
    <h2 class="font-semibold text-white mb-4">Keranjang</h2>
    <div class="overflow-y-auto flex-grow -mx-5 px-5">
      <table class="min-w-full text-sm">
        <thead class="sticky top-0 bg-neutral-900/50 backdrop-blur-sm">
          <tr class="text-neutral-400">
            <th class="px-2 py-2 text-left font-semibold">Item</th>
            <th class="px-2 py-2 text-center font-semibold" style="width: 120px;">Qty</th>
            <th class="px-2 py-2 text-right font-semibold">Subtotal</th>
          </tr>
        </thead>
        <tbody id="cart-body-js" class="text-neutral-300">
        </tbody>
      </table>
    </div>
    <div class="mt-4 border-t-2 border-neutral-800 pt-4 flex items-center justify-between">
      <div class="font-bold text-white">
        <span>TOTAL</span>
      </div>
      <div class="text-right text-lg font-bold text-white" id="total-js">
        Rp 0
      </div>
    </div>
    <div class="mt-4">
      <button id="save-order-js" class="w-full px-5 py-3 rounded-lg bg-white hover:bg-neutral-200 text-black text-sm font-semibold transition disabled:bg-neutral-800 disabled:text-neutral-500 disabled:cursor-not-allowed" disabled>
        Simpan Pesanan
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const transaksiId = {{ $transaksiId }};
    const menuButtons = document.querySelectorAll('.add-item-js');
    const cartBody = document.getElementById('cart-body-js');
    const totalElement = document.getElementById('total-js');
    const saveButton = document.getElementById('save-order-js');

    let cart = []; 

    function renderCart() {
        cartBody.innerHTML = '';
        let grandTotal = 0;

        if (cart.length === 0) {
            cartBody.innerHTML = '<tr><td colspan="3" class="p-6 text-center text-neutral-500">Keranjang masih kosong</td></tr>';
            saveButton.disabled = true;
        } else {
            cart.forEach((item, index) => {
                const subtotal = item.harga * item.qty;
                grandTotal += subtotal;

                const row = `
                    <tr class="border-t border-neutral-800">
                        <td class="p-2 text-white font-medium">${item.nama}</td>
                        <td class="p-2 text-center">
                            <div class="flex items-center justify-center">
                                <button class="qty-change-js h-7 w-7 rounded-full border border-neutral-700 text-neutral-300 hover:bg-neutral-700 transition" data-index="${index}" data-amount="-1">-</button>
                                <span class="mx-3 font-semibold text-white">${item.qty}</span>
                                <button class="qty-change-js h-7 w-7 rounded-full border border-neutral-700 text-neutral-300 hover:bg-neutral-700 transition" data-index="${index}" data-amount="1">+</button>
                            </div>
                        </td>
                        <td class="p-2 text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>
                `;
                cartBody.innerHTML += row;
            });
            saveButton.disabled = false;
        }
        totalElement.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    }

    // ...
    menuButtons.forEach(btn => { /* ... */ });
    cartBody.addEventListener('click', function(event) { /* ... */ });
    saveButton.addEventListener('click', async () => { /* ... */ });
    
    menuButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const menuItem = {
                menu_id: parseInt(btn.dataset.id),
                nama: btn.dataset.nama,
                harga: parseInt(btn.dataset.harga),
            };
            const existingItem = cart.find(item => item.menu_id === menuItem.menu_id);
            if (existingItem) {
                existingItem.qty++;
            } else {
                cart.push({ ...menuItem, qty: 1 });
            }
            renderCart();
        });
    });

    cartBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('qty-change-js')) {
            const index = parseInt(event.target.dataset.index);
            const amount = parseInt(event.target.dataset.amount);
            cart[index].qty += amount;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }
            renderCart();
        }
    });

    saveButton.addEventListener('click', async () => {
        saveButton.disabled = true;
        saveButton.textContent = 'Menyimpan...';
        try {
            const response = await fetch("{{ route('transaksi.addItemsBulk') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    transaksi_id: transaksiId,
                    items: cart.map(item => ({ menu_id: item.menu_id, qty: item.qty }))
                })
            });
            if (response.ok) {
                alert('Pesanan berhasil disimpan!');
                window.location.href = "{{ route('order.index') }}";
            } else {
                alert('Gagal menyimpan pesanan. Silakan coba lagi.');
                saveButton.disabled = false;
                saveButton.textContent = 'Simpan Pesanan';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan.');
            saveButton.disabled = false;
            saveButton.textContent = 'Simpan Pesanan';
        }
    });

    renderCart();
});
</script>
@endpush