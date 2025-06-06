@extends('layouts.client')

@section('content')
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <div class="mx-auto px-4 2xl:px-0 flex flex-row gap-4">
            @if ($cart)
                <form action="{{ route('checkout.multiple') }}" method="POST" id="checkout-form"
                    class="flex flex-col md:flex-row gap-4 w-full">
                    @csrf
                    <div class="bg-white w-full md:w-3/4 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Cart</h2>
                        <div class="mt-6 sm:mt-8 relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                            <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    @foreach ($cart as $item)
                                        <tr>
                                            <td class="p-4">
                                                <input type="checkbox" class="select-item" data-id="{{ $item->produk->id }}"
                                                    data-harga="{{ $item->produk->harga }}" data-qty="{{ $item->qty }}">
                                            </td>
                                            <td class="whitespace-nowrap py-4 md:w-[384px]">
                                                <div class="flex items-center gap-4">
                                                    <img class="w-10 h-10"
                                                        src="{{ asset('storage/' . $item->produk->foto_produk) }}"
                                                        alt="{{ $item->produk->nama }}">
                                                    <div class="flex flex-col">
                                                        <span class="font-medium">{{ $item->produk->nama }}</span>
                                                        <small>Price: IDR. {{ number_format($item->produk->harga, 0, ',', '.') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4">{{ $item->qty }}x</td>
                                            <td class="p-4">IDR. {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</td>
                                            <td class="p-4">
                                                <a href="{{ route('cart.delete', $item->id) }}"
                                                    class="text-red-600 hover:text-red-800 delete-link">
                                                    Remove
                                                </a>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', () => {
                                                        const deleteLinks = document.querySelectorAll('.delete-link');

                                                        deleteLinks.forEach(link => {
                                                            link.addEventListener('click', (event) => {
                                                                event.preventDefault();
                                                                const url = link.href;

                                                                Swal.fire({
                                                                    title: 'Are you sure?',
                                                                    text: "You won't be able to revert this!",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Yes, delete it!'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        window.location.href = url;
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    });
                                                </script>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="w-full md:w-1/4 bg-white p-6 space-y-4">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Total Items</span>
                                <span id="total-qty">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Price</span>
                                <span id="total-harga">IDR. 0</span>
                            </div>
                        </div>

                        {{-- Hidden Inputs --}}
                        <input type="hidden" name="total_qty_item" id="input-total-qty">
                        <input type="hidden" name="total_bayar" id="input-total-harga">
                        <input type="hidden" name="selected_products" id="selected-products">

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="btn-primary w-full"
                                onclick="return confirmCheckout(event)">Checkout</button>
                            <a href="{{ route('products') }}"
                                class="text-center border border-gray-200 py-2 rounded text-gray-900 hover:bg-gray-100">
                                Return to Shopping
                            </a>
                        </div>
                    </div>
                </form>

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

                                    totalHarga += harga * qty;
                                    totalQty += qty;
                                    selectedItems.push({
                                        id,
                                        qty,
                                        harga: harga * qty
                                    });
                                }
                            });
                            totalHargaElem.textContent = 'IDR. ' + totalHarga.toLocaleString('id-ID');
                            totalQtyElem.textContent = totalQty;

                            inputTotalHarga.value = totalHarga;
                            inputTotalQty.value = totalQty;
                            selectedProductsInput.value = JSON.stringify(selectedItems);
                        }

                        checkboxes.forEach(cb => cb.addEventListener('change', updateTotals));
                    });

                    function confirmCheckout(event) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Checkout Now?',
                            text: "Are you sure you want to checkout selected items?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Checkout!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                event.target.closest('form').submit();
                            }
                        });
                    }
                </script>
            @else
                <div class="text-center w-full">No items in the cart</div>
            @endif
        </div>
    </section>
@endsection
