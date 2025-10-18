@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Order — Meja {{ $meja->kode }}</h1>
  {{-- Tombol ini akan mengarahkan kembali jika tidak ada perubahan --}}
  <a href="{{ route('order.index') }}" class="text-sm underline">Ganti Meja</a>
</div>

<div class="grid lg:grid-cols-2 gap-4">
  <!-- Daftar menu -->
  <div class="bg-white rounded-2xl shadow p-4">
    <h2 class="font-semibold mb-3">Pilih Menu</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
      @foreach($menus as $mn)
        <button data-id="{{ $mn->id }}" data-nama="{{ $mn->nama }}" data-harga="{{ $mn->harga }}"
          class="add-item-js rounded-xl border p-3 text-left hover:border-gray-900 transition focus:outline-none focus:ring-2 focus:ring-gray-900">
          <p class="font-medium">{{ $mn->nama }}</p>
          <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($mn->harga,0,',','.') }}</p>
        </button>
      @endforeach
    </div>
  </div>

  <!-- Keranjang -->
  <div class="bg-white rounded-2xl shadow p-4 flex flex-col">
    <h2 class="font-semibold mb-3">Keranjang</h2>
    <div class="overflow-y-auto flex-grow">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100"><tr>
          <th class="p-2 text-left">Item</th>
          <th class="p-2 text-center" style="width: 100px;">Qty</th>
          <th class="p-2 text-right">Subtotal</th>
        </tr></thead>
        <tbody id="cart-body-js">
            {{-- Konten keranjang akan diisi oleh JavaScript --}}
        </tbody>
        <tfoot>
          <tr class="border-t font-semibold">
            <td class="p-2" colspan="2">Total</td>
            <td class="p-2 text-right" id="total-js">Rp 0</td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="mt-4 text-right">
      <button id="save-order-js" class="px-5 py-2.5 rounded-lg bg-gray-900 text-white text-sm font-semibold w-full sm:w-auto" disabled>
        Simpan Pesanan
      </button>
    </div>
  </div>
</div>

{{-- Kita tidak lagi memerlukan form tersembunyi --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const transaksiId = {{ $transaksiId }};
    const menuButtons = document.querySelectorAll('.add-item-js');
    const cartBody = document.getElementById('cart-body-js');
    const totalElement = document.getElementById('total-js');
    const saveButton = document.getElementById('save-order-js');

    let cart = []; // Ini adalah keranjang lokal kita

    // Fungsi untuk merender ulang tampilan keranjang
    function renderCart() {
        cartBody.innerHTML = ''; // Kosongkan keranjang
        let grandTotal = 0;

        if (cart.length === 0) {
            cartBody.innerHTML = '<tr><td colspan="3" class="p-3 text-center text-gray-500">Keranjang masih kosong</td></tr>';
            saveButton.disabled = true; // Nonaktifkan tombol simpan jika keranjang kosong
        } else {
            cart.forEach((item, index) => {
                const subtotal = item.harga * item.qty;
                grandTotal += subtotal;

                const row = `
                    <tr class="border-t">
                        <td class="p-2">${item.nama}</td>
                        <td class="p-2 text-center">
                            <div class="flex items-center justify-center">
                                <button class="qty-change-js px-2" data-index="${index}" data-amount="-1">-</button>
                                <span class="mx-2">${item.qty}</span>
                                <button class="qty-change-js px-2" data-index="${index}" data-amount="1">+</button>
                            </div>
                        </td>
                        <td class="p-2 text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>
                `;
                cartBody.innerHTML += row;
            });
            saveButton.disabled = false; // Aktifkan tombol simpan
        }
        totalElement.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    }

    // Event listener untuk setiap tombol menu
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

    // Event listener untuk tombol +/- di keranjang
    cartBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('qty-change-js')) {
            const index = parseInt(event.target.dataset.index);
            const amount = parseInt(event.target.dataset.amount);

            cart[index].qty += amount;

            if (cart[index].qty <= 0) {
                cart.splice(index, 1); // Hapus item jika kuantitasnya 0 atau kurang
            }
            renderCart();
        }
    });

    // Event listener untuk tombol simpan pesanan
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
                window.location.href = "{{ route('order.index') }}"; // Arahkan kembali ke daftar meja
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

    // Render keranjang awal (kosong)
    renderCart();
});
</script>
@endpush