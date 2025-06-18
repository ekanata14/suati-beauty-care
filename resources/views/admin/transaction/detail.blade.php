@extends('layouts.admin')
@section('content')
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <form action="{{ route('upload.payment.store') }}" method="POST"
            class="mx-auto max-w-screen-xl px-4 2xl:px-0 flex flex-col md:flex-row gap-4" enctype="multipart/form-data">
            @csrf
            <div class="bg-white h-full w-full md:w-3/4 p-6">
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
                                <th>Price</th>
                                <th>Total Price</th>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse ($transaction->order->detailOrder as $item)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 md:w-[384px]">
                                            <div class="flex items-center gap-4">
                                                <a href="#"
                                                    class="flex items-center aspect-square w-10 h-10 shrink-0">
                                                    <img class="h-auto w-full max-h-full dark:hidden"
                                                        src="{{ asset('storage/' . $item->produk->foto_produk) }}"
                                                        alt="imac " />
                                                </a>
                                                <a href="#" class="hover:underline">{{ $item->produk->nama }}</a>
                                            </div>
                                        </td>
                                        <td class="p-4 text-base font-normal text-gray-900 dark:text-white">
                                            {{ $item->qty }}x</td>
                                        @php
                                            $totalItem += $item->qty;
                                        @endphp
                                        <td class="p-4 text-start text-base font-bold text-gray-900 dark:text-white">
                                            IDR {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 text-start text-base font-bold text-gray-900 dark:text-white">
                                            @php
                                                $totalPrice += $item->harga * $item->qty;
                                            @endphp
                                            IDR {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/4 space-y-6 bg-white p-6">
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</h4>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Total Price</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">IDR
                                {{ number_format($totalPrice, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Total Item</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $totalItem }}</dd>
                        </dl>
                    </div>

                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                        <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">IDR
                            {{ number_format($totalPrice, 0, ',', '.') }}</dd>
                    </dl>
                </div>

                <div class="flex flex-col gap-2">
                    @if ($transaction->status_pembayaran == 'pending')
                        <div class="mt-4 btn-yellow w-full text-center">Pending</div>
                    @elseif($transaction->status_pembayaran == 'waiting')
                        <div class="mt-4 btn-yellow w-full text-center">Waiting for confirmation</div>
                    @elseif($transaction->status_pembayaran == 'denied')
                        <div class="mt-4 btn-red w-full text-center">Denied</div>
                    @elseif($transaction->status_pembayaran == 'paid')
                        <div class="mt-4 btn-green w-full text-center">Payment confirmed</div>
                    @endif
                    <a href="{{ route('admin.transaction.index') }}"
                        class="w-full text-center rounded-lg  border border-gray-200 bg-white px-5  py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Back</a>
                </div>
            </div>
        </form>
    </section>
@endsection
