@extends('layouts.client')

@section('content')
    <section class="py-12 bg-gradient-to-b from-white to-blue-100 min-h-screen">
        <form action="{{ route('upload.payment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            Order Summary
                            <span
                                class="ml-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 text-sm font-mono tracking-wide">
                                #{{ $transaction->invoice_id }}
                            </span>
                        </h2>
                        <div class="flex items-center gap-2 mt-2 text-gray-600 dark:text-gray-300 text-sm">
                            <svg class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Date: {{ $transaction->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2 text-gray-600 dark:text-gray-300 text-sm">
                            <span>Address: {{ $transaction->alamat ?? "-" }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        @php
                            $totalPrice = 0;
                            $totalItem = 0;
                        @endphp
                        <table class="w-full text-sm text-left text-gray-900 dark:text-white">
                            <thead class="text-xs uppercase text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-4">Name</th>
                                    <th scope="col" class="py-3 px-4">Ukuran</th>
                                    <th scope="col" class="py-3 px-4">Qty</th>
                                    <th scope="col" class="py-3 px-4">Price</th>
                                    <th scope="col" class="py-3 px-4">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($transaction->order->detailOrder as $item)
                                    <tr>
                                        <td class="py-4 px-4 flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $item->produk->foto_produk) }}"
                                                alt="{{ $item->produk->nama }}" class="w-10 h-10 rounded object-cover">
                                            <span>{{ $item->produk->nama }}</span>
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
                                        <td class="py-4 px-4">{{ $item->qty }}x</td>
                                        @php $totalItem += $item->qty; @endphp
                                        <td class="py-4 px-4 font-semibold">IDR
                                            {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="py-4 px-4 font-semibold">
                                            @php $totalPrice += $item->harga * $item->qty; @endphp
                                            IDR {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">No items</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Summary</h4>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-gray-600 dark:text-gray-300">Status:</span>
                        @php
                            $status = $transaction->status_pembayaran;
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'waiting' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'denied' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$status] ?? 'bg-gray-200 text-gray-800' }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Total Price</span>
                            <span class="font-medium text-gray-900 dark:text-white"> IDR
                                {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Total Item</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $totalItem }}</span>
                        </div>
                    </div>
                    <div class="border-t pt-4 dark:border-gray-700">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">IDR
                                {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if ($transaction->status_pembayaran == 'pending')
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <span class="block text-gray-700 dark:text-gray-200 text-xs mb-1">Bank</span>
                                    <span class="text-base font-semibold text-blue-700 dark:text-blue-200">{{ config('bank.name', 'BCA') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="block text-gray-700 dark:text-gray-200 text-xs mb-1">Nama</span>
                                    <span class="text-base font-semibold text-blue-700 dark:text-blue-200">{{ config('bank.holder', 'John Doe') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col flex-1">
                                    <span class="block text-gray-700 dark:text-gray-200 text-xs mb-1">Nomor Rekening</span>
                                    <span id="account-number" class="text-lg font-mono font-semibold text-blue-700 dark:text-blue-200 select-all">
                                        {{ config('bank.number', '1234567890') }}
                                    </span>
                                </div>
                                <button type="button" onclick="copyAccountNumber()"
                                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition flex items-center gap
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16h8M8 12h8m-7 8h6a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Copy
                            </button>
                        </div>
                    @endif
                    <script>
                        function copyAccountNumber() {
                            const accNum = document.getElementById('account-number').textContent;
                            navigator.clipboard.writeText(accNum).then(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Copied!',
                                    text: 'Nomor rekening berhasil disalin.',
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                            });
                        }
                    </script>
                    @if ($transaction->status_pembayaran == 'pending')
                        <div>
                            <label for="file_input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload file</label>
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
                        <div class="mt-4 btn-green w-full text-center">Payment Confirmed</div>
                    @endif

                    @if ($transaction->bukti_pembayaran)
                        {{-- <button type="button" class="btn-primary w-full view-proof-btn"
                            data-url="{{ route('user.transaction.proof', $transaction->id) }}">
                            View Proof
                        </button> --}}
                    @else
                        <span class="text-gray-400">No Proof</span>
                    @endif

                    <!-- Modal for Payment Proof -->
                    <div id="proof-modal-{{ $transaction->id }}"
                        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded shadow-lg max-w-md mx-auto p-4 relative flex flex-col"
                            style="max-height: 90vh;">
                            <button type="button"
                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 close-proof-modal">&times;</button>
                            <h3 class="text-lg font-semibold mb-2">Payment Proof</h3>
                            <div class="flex-1 flex items-center justify-center">
                                <img src="" alt="Payment Proof" class="max-w-full max-h-[70vh] rounded proof-img" style="object-fit: contain;">
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.view-proof-btn').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    const url = btn.getAttribute('data-url');
                                    const modal = document.getElementById('proof-modal-{{ $transaction->id }}');
                                    if (modal) {
                                        const img = modal.querySelector('.proof-img');
                                        img.src = url;
                                        modal.classList.remove('hidden');
                                        modal.classList.add('flex');
                                    }
                                });
                            });
                            document.querySelectorAll('.close-proof-modal').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    const modal = btn.closest('[id^="proof-modal-"]');
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                });
                            });
                        });
                    </script>

                    <a href="{{ route('history') }}"
                        class="block text-center w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        Back
                    </a>
                </div>
            </div>
        </form>
    </section>
@endsection
