@extends('layouts.client')

@section('content')
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0 flex gap-4">
            @if ($order)
                <div class="bg-white h-full w-3/4 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Order summary</h2>
                    <div class="mt-6 sm:mt-8">
                        <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                            @php
                                $totalPrice = 0;
                                $totalItem = 0;
                            @endphp
                            <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                                <thead>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    @forelse ($order->detailOrder as $item)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 md:w-[384px]">
                                                <div class="flex items-center gap-4">
                                                    <a href="#"
                                                        class="flex items-center aspect-square w-10 h-10 shrink-0">
                                                        <img class="h-auto w-full max-h-full dark:hidden"
                                                            src="{{ asset('storage/' . $item->produk->foto_produk) }}"
                                                            alt="image " />
                                                    </a>
                                                    <div class="flex flex-col gap-2">
                                                        <a href="#"
                                                            class="hover:underline">{{ $item->produk->nama }}</a>
                                                        <p>
                                                            Price: IDR.
                                                            {{ $item->harga }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4 text-base font-normal text-gray-900 dark:text-white">
                                                {{ $item->qty }}x</td>
                                            @php
                                                $totalItem += $item->qty;
                                            @endphp
                                            <td class="p-4 text-start text-base font-bold text-gray-900 dark:text-white">
                                                IDR. {{ $item->harga * $item->qty }}
                                                @php
                                                    $totalPrice += $item->harga * $item->qty;
                                                @endphp
                                            </td>
                                            <td class="p-4 text-start text-base font-bold text-gray-900 dark:text-white">
                                                <form action="{{ route('delete.item') }}" method="POST"
                                                    onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="submit"
                                                        class="text-red-500 hover:underline">Remove</button>
                                                </form>

                                                <script>
                                                    function confirmDelete(event) {
                                                        event.preventDefault();
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
                                                                event.target.submit();
                                                            }
                                                        });
                                                    }
                                                </script>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="space-y-6 bg-white p-6">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Total Price</dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $totalPrice }}</dd>
                                </dl>
                            </div>
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Total Item</dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $totalItem }}</dd>
                                </dl>
                            </div>

                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalPrice }}</dd>
                            </dl>
                        </div>

                        <div class="flex items-start sm:items-center">
                            <input id="terms-checkbox-2" type="checkbox" value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                                required />
                            <label for="terms-checkbox-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                I agree with the <a href="#" title=""
                                    class="text-primary-700 underline hover:no-underline dark:text-primary-500">Terms and
                                    Conditions</a> of use of the Flowbite marketplace </label>
                        </div>

                        <div class="flex flex-col gap-2">
                            <input type="hidden" name="id_order" value="{{ $order->id }}">
                            <input type="hidden" name="total_qty_item" value="{{ $totalItem }}">
                            <input type="hidden" name="total_bayar" value="{{ $totalPrice }}">
                            <button type="submit" class="mt-4 btn-primary w-full" onclick="return confirmCheckout(event)">Checkout</button>

                            <script>
                                function confirmCheckout(event) {
                                    event.preventDefault();
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "Do you want to proceed with the checkout?",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, proceed!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            event.target.closest('form').submit();
                                        }
                                    });
                                }
                            </script>
                            <a href="{{ route('products') }}"
                                class="w-full text-center rounded-lg  border border-gray-200 bg-white px-5  py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Return
                                to Shopping</a>
                        </div>
                    @else
                        <div class="text-center">
                            No items in the cart
                        </div>
            @endif
            </form>
        </div>
    </section>
@endsection
