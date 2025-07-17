@extends('layouts.client')

@section('content')
    <section class="py-12 bg-gradient-to-b from-white to-blue-100 min-h-screen">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-6">
            @if ($cart)
                <form action="{{ route('checkout.multiple') }}" method="POST" id="checkout-form"
                    class="flex flex-col lg:flex-row gap-6 w-full">
                    @csrf
                    <!-- Cart Items -->
                    <div class="bg-white rounded-xl shadow-md w-full lg:w-3/4 p-6" data-aos="fade-right"
                        data-aos-duration="500">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Keranjang Belanja</h2>
                        <div class="overflow-x-auto border-b border-gray-200 dark:border-gray-700">
                            <table class="min-w-full text-sm text-left text-gray-700 dark:text-white">
                                <thead class="uppercase text-xs text-gray-500">
                                    <tr>
                                        <th class="py-3">Pilih</th>
                                        <th class="py-3">Produk</th>
                                        <th class="py-3">Ukuran</th>
                                        <th class="py-3">Qty</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    @foreach ($cart as $item)
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="p-3 align-middle">
                                                <input type="checkbox" class="select-item" data-id="{{ $item->produk->id }}"
                                                    data-harga="{{ $item->produk->harga }}" data-qty="{{ $item->qty }}"
                                                    data-cart-id="{{ $item->id }}">
                                            </td>
                                            <td class="p-3 align-middle">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ asset('storage/' . $item->produk->foto_produk) }}"
                                                        alt="{{ $item->produk->nama }}"
                                                        class="w-12 h-12 object-cover rounded" />
                                                    <div>
                                                        <p class="font-semibold">{{ $item->produk->nama }}</p>
                                                        <p class="text-xs text-gray-500">IDR
                                                            {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 align-middle">
                                                <ul>
                                                    @foreach ($item->sizes as $size)
                                                        <li class="mb-1">
                                                            <span class="font-medium">Ukuran:</span> {{ $size->size }},
                                                            <span class="font-medium">Qty:</span> {{ $size->qty }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="p-3 align-middle">{{ $item->qty }}x</td>
                                            <td class="p-3 align-middle">IDR
                                                {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</td>
                                            <td class="p-3 align-middle">
                                                <a href="{{ route('cart.delete', $item->id) }}"
                                                    class="text-red-600 hover:underline delete-link">Hapus</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-white rounded-xl shadow-md w-full lg:w-1/4 p-6" data-aos="fade-left"
                        data-aos-duration="500">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Total Item</span>
                                <span id="total-qty">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Harga</span>
                                <span id="total-harga">IDR 0</span>
                            </div>
                        </div>

                        <input type="hidden" name="total_qty_item" id="input-total-qty">
                        <input type="hidden" name="total_bayar" id="input-total-harga">
                        <input type="hidden" name="selected_products" id="selected-products">

                        <div class="mt-6 flex flex-col gap-3">
                            <button type="submit" onclick="return confirmCheckout(event)"
                                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Checkout</button>
                            <a href="{{ route('products') }}"
                                class="text-center text-sm border border-gray-300 py-2 rounded hover:bg-gray-100">Kembali
                                Belanja</a>
                        </div>
                    </div>
                </form>

                <!-- Scripts -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const checkboxes = document.querySelectorAll('.select-item');
                        const totalHargaElem = document.getElementById('total-harga');
                        const totalQtyElem = document.getElementById('total-qty');
                        const selectedProductsInput = document.getElementById('selected-products');
                        const inputTotalHarga = document.getElementById('input-total-harga');
                        const inputTotalQty = document.getElementById('input-total-qty');

                        function updateTotals() {
                            let totalHarga = 0;
                            let totalQty = 0;
                            let selectedItems = [];

                            checkboxes.forEach(cb => {
                                if (cb.checked) {
                                    const harga = parseInt(cb.dataset.harga);
                                    const qty = parseInt(cb.dataset.qty);
                                    const id = cb.dataset.id;
                                    const cartId = cb.dataset.cartId;

                                    // Collect more detailed info for selected products
                                    selectedItems.push({
                                        cart_id: cartId,
                                        produk_id: id,
                                        qty: qty,
                                        harga: harga,
                                        total: harga * qty
                                    });

                                    totalHarga += harga * qty;
                                    totalQty += qty;
                                }
                            });

                            totalHargaElem.textContent = 'IDR ' + totalHarga.toLocaleString('id-ID');
                            totalQtyElem.textContent = totalQty;
                            inputTotalHarga.value = totalHarga;
                            inputTotalQty.value = totalQty;
                            selectedProductsInput.value = JSON.stringify(selectedItems);
                        }

                        checkboxes.forEach(cb => cb.addEventListener('change', updateTotals));
                        updateTotals();

                        const deleteLinks = document.querySelectorAll('.delete-link');
                        deleteLinks.forEach(link => {
                            link.addEventListener('click', function(event) {
                                event.preventDefault();
                                const url = this.href;

                                Swal.fire({
                                    title: 'Yakin ingin menghapus?',
                                    text: 'Item ini akan dihapus dari keranjang.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya, hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = url;
                                    }
                                });
                            });
                        });
                    });

                    function confirmCheckout(event) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Lanjut Checkout?',
                            text: 'Apakah Anda yakin ingin melanjutkan proses checkout?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, lanjutkan!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                event.target.closest('form').submit();
                            }
                        });
                    }
                </script>
            @else
                <div class="text-center w-full text-gray-500 text-lg py-12">Keranjang Anda kosong.</div>
            @endif
        </div>
    </section>
@endsection
