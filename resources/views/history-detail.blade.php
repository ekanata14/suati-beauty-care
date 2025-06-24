@extends('layouts.client')

@section('content')
<section class="py-12 bg-gradient-to-b from-white to-blue-100 min-h-screen">
    <form action="{{ route('upload.payment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>

                <div class="overflow-x-auto">
                    @php $totalPrice = 0; $totalItem = 0; @endphp
                    <table class="w-full text-sm text-left text-gray-900 dark:text-white">
                        <thead class="text-xs uppercase text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">
                            <tr>
                                <th scope="col" class="py-3 px-4">Name</th>
                                <th scope="col" class="py-3 px-4">Qty</th>
                                <th scope="col" class="py-3 px-4">Price</th>
                                <th scope="col" class="py-3 px-4">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($transaction->order->detailOrder as $item)
                                <tr>
                                    <td class="py-4 px-4 flex items-center gap-4">
                                        <img src="{{ asset('storage/' . $item->produk->foto_produk) }}" alt="{{ $item->produk->nama }}" class="w-10 h-10 rounded object-cover">
                                        <span>{{ $item->produk->nama }}</span>
                                    </td>
                                    <td class="py-4 px-4">{{ $item->qty }}x</td>
                                    @php $totalItem += $item->qty; @endphp
                                    <td class="py-4 px-4 font-semibold">IDR {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 font-semibold">
                                        @php $totalPrice += $item->harga * $item->qty; @endphp
                                        IDR {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 px-4 text-center text-gray-500">No items</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Summary</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Total Price</span>
                        <span class="font-medium text-gray-900 dark:text-white">IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Total Item</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $totalItem }}</span>
                    </div>
                </div>
                <div class="border-t pt-4 dark:border-gray-700">
                    <div class="flex justify-between">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($transaction->status_pembayaran == 'pending')
                    <div>
                        <label for="file_input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload file</label>
                        <input type="file" name="bukti_pembayaran" accept="image/*" required
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PNG, JPG, JPEG (MAX. 2MB)</p>

                        <input type="hidden" name="id" value="{{ $transaction->id }}">
                        <input type="hidden" name="total_qty_item" value="{{ $transaction->total_qty_item }}">
                        <input type="hidden" name="total_bayar" value="{{ $transaction->total_bayar }}">

                        <button type="submit" class="w-full mt-4 btn-primary">Upload Payment</button>
                    </div>
                @elseif ($transaction->status_pembayaran == 'waiting')
                    <div class="mt-4 btn-yellow w-full text-center">Waiting for confirmation</div>
                @elseif ($transaction->status_pembayaran == 'denied')
                    <div class="mt-4 btn-red w-full text-center">Denied</div>
                @elseif ($transaction->status_pembayaran == 'paid')
                    <div class="mt-4 btn-green w-full text-center">Payment confirmed</div>
                @endif

                <a href="{{ route('history') }}"
                    class="block text-center w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Back
                </a>
            </div>
        </div>
    </form>
</section>
@endsection
