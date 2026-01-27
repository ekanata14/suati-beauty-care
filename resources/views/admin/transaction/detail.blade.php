@extends('layouts.admin')

@section('content')
    <section class="min-h-screen">f
        @php
    // Pastikan relasi pengiriman ada
    $pengiriman = $transaction->pengiriman;
@endphp

@if ($pengiriman)
    @php
        // --- LOGIKA STEPPER ---
        $statuses = ['pending', 'diproses', 'dikirim', 'diterima'];
        $currentStatus = $pengiriman->status;
        $currentIndex = array_search($currentStatus, $statuses);
        $isReturned = $currentStatus === 'dikembalikan';

        if ($isReturned) {
            $currentIndex = 2; // Stop di 'dikirim', step selanjutnya jadi 'dikembalikan'
        }
    @endphp

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-100 dark:border-gray-700 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- HEADER: Judul & Status Badge --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    Informasi Pengiriman
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Update terakhir: {{ $pengiriman->updated_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="mt-3 sm:mt-0">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $currentStatus == 'diterima' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                    {{ $currentStatus == 'dikembalikan' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                    {{ in_array($currentStatus, ['pending', 'diproses', 'dikirim']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}">
                    {{ $currentStatus }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-8">
            {{-- BAGIAN 1: STEPPER TRACKING --}}
            <div class="w-full">
                <ol class="flex items-center w-full text-xs sm:text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base">
                    @foreach ($statuses as $index => $step)
                        @php
                            $isCompleted = $index <= $currentIndex;
                            $isLast = $loop->last;

                            $colorClass = $isCompleted ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400';
                            $borderColor = $isCompleted ? 'border-blue-600 dark:border-blue-500' : 'border-gray-200 dark:border-gray-700';

                            if ($isReturned && $isLast) {
                                $step = 'dikembalikan';
                                $colorClass = 'text-red-600 dark:text-red-500';
                                $borderColor = 'border-red-600 dark:border-red-500';
                            }
                        @endphp
                        <li class="flex {{ $isLast ? '' : 'w-full' }} items-center {{ $colorClass }} {{ !$isLast ? "after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block " . $borderColor : '' }}">
                            <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                                <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full shrink-0 {{ ($index <= $currentIndex || ($isReturned && $isLast)) ? ($isReturned && $isLast ? 'bg-red-100 dark:bg-red-900' : 'bg-blue-100 dark:bg-blue-900') : 'bg-gray-100 dark:bg-gray-700' }}">
                                    @if ($isReturned && $isLast)
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @elseif($index < $currentIndex || ($index == $currentIndex && $currentStatus == 'diterima'))
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span class="text-xs sm:text-sm font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </span>
                                <span class="hidden sm:inline-flex sm:ml-2 text-xs sm:text-sm font-semibold whitespace-nowrap">{{ ucfirst($step) }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- BAGIAN 2: DETAIL DATA (Grid Layout) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Kurir & Resi --}}
                <div class="md:col-span-1 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Kurir & Layanan</h4>
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $pengiriman->kurir ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $pengiriman->layanan_kurir ?? 'Regular' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">No. Resi</h4>
                        <div class="flex items-center gap-2">
                            <span id="text_resi" class="text-base font-mono font-semibold text-gray-900 dark:text-white select-all">
                                {{ $pengiriman->no_resi ?? 'Belum tersedia' }}
                            </span>
                            @if($pengiriman->no_resi)
                                <button onclick="copyResi('{{ $pengiriman->no_resi }}')" class="text-gray-400 hover:text-blue-600 transition" title="Copy Resi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Biaya Ongkir</h4>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($pengiriman->biaya_ongkir, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Kolom Tengah: Alamat & Catatan --}}
                <div class="md:col-span-1 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Alamat Penerima</h4>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-100 dark:border-gray-600">
                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                                <svg class="w-4 h-4 text-gray-400 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $pengiriman->alamat_tujuan }}
                            </p>
                        </div>
                    </div>

                    @if($pengiriman->catatan)
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Catatan Pengiriman</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ $pengiriman->catatan }}"</p>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Tanggal & Bukti --}}
                <div class="md:col-span-1 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Timeline</h4>
                        <ul class="text-sm space-y-2">
                            <li class="flex justify-between">
                                <span class="text-gray-500">Dikirim:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $pengiriman->tgl_dikirim ? \Carbon\Carbon::parse($pengiriman->tgl_dikirim)->format('d M Y, H:i') : '-' }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-500">Diterima:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $pengiriman->tgl_diterima ? \Carbon\Carbon::parse($pengiriman->tgl_diterima)->format('d M Y, H:i') : '-' }}</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Bukti Pengiriman</h4>
                        @if($pengiriman->foto_bukti)
                            <a href="{{ Storage::url($pengiriman->foto_bukti) }}" target="_blank" class="block group relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600 w-full h-32 bg-gray-100">
                                <img src="{{ Storage::url($pengiriman->foto_bukti) }}" alt="Bukti Resi" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                                    <span class="text-white opacity-0 group-hover:opacity-100 text-xs font-bold bg-black/50 px-2 py-1 rounded">Lihat Foto</span>
                                </div>
                            </a>
                        @else
                            <div class="w-full h-24 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex flex-col items-center justify-center text-gray-400 text-xs">
                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Belum ada bukti
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Kecil untuk Copy Resi --}}
    <script>
        function copyResi(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Resi berhasil disalin: ' + text);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
@else
    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
        <span class="font-medium">Info!</span> Data pengiriman belum tersedia untuk transaksi ini. Silakan buat pengiriman baru.
    </div>
@endif
        <form action="{{ route('upload.payment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Order Details -->
                <div class="md:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            Detail Order
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
                            <span>Tanggal: {{ $transaction->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @php
                            $totalPrice = 0;
                            $totalItem = 0;
                        @endphp
                        <table class="w-full text-sm text-left text-gray-900 dark:text-white">
                            <thead
                                class="text-xs uppercase text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-4">Nama</th>
                                    <th scope="col" class="py-3 px-4">Ukuran</th>
                                    <th scope="col" class="py-3 px-4">Qty</th>
                                    <th scope="col" class="py-3 px-4">Harga</th>
                                    <th scope="col" class="py-3 px-4">Total Harga</th>
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
                                        <td class="py-4 px-4">
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
                <!-- Summary & Actions -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Detail</h4>
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
                            <span class="text-gray-600 dark:text-gray-300">Transport Fee</span>
                            <span class="font-medium text-gray-900 dark:text-white"> IDR
                                {{ number_format($transaction->pengiriman->biaya_ongkir, 0, ',', '.') }}</span>
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
                                {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if ($transaction->status_pembayaran == 'pending')
                        {{-- <div>
                            <label for="file_input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Payment
                                Proof</label>
                            <input type="file" name="bukti_pembayaran" accept="image/*" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PNG, JPG, JPEG (MAX. 2MB)</p>
                            <input type="hidden" name="id" value="{{ $transaction->id }}">
                            <input type="hidden" name="total_qty_item" value="{{ $transaction->total_qty_item }}">
                            <input type="hidden" name="total_bayar" value="{{ $transaction->total_bayar }}">
                            <button type="submit" class="w-full mt-4 btn-primary">Upload Payment</button>
                        </div> --}}
                    @elseif ($transaction->status_pembayaran == 'waiting')
                        <div class="mt-4 btn-yellow w-full text-center">Waiting for confirmation</div>
                        <!-- Use a button (not a nested form) to avoid submitting the outer form; send PUT via fetch -->
                        <button type="button"
                            class="px-4 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 confirm-button w-full"
                            data-url="{{ route('admin.transaction.update.status') }}" data-id="{{ $transaction->id }}"
                            data-status="paid" data-message="Are you sure you want to confirm this payment?">
                            Confirm
                        </button>
                        <script>
                            document.querySelectorAll('.confirm-button').forEach(button => {
                                button.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const url = this.getAttribute('data-url');
                                    const id = this.getAttribute('data-id');
                                    const status = this.getAttribute('data-status');
                                    const message = this.getAttribute('data-message');
                                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                                    const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : null;

                                    Swal.fire({
                                        title: 'Confirmation',
                                        text: message,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes',
                                        cancelButtonText: 'No'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const formData = new FormData();
                                            if (csrfToken) formData.append('_token', csrfToken);
                                            formData.append('_method', 'PUT');
                                            formData.append('id', id);
                                            formData.append('status_pembayaran', status);

                                            fetch(url, {
                                                method: 'POST',
                                                body: formData,
                                                credentials: 'same-origin',
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                }
                                            }).then(response => {
                                                if (!response.ok) throw response;
                                                return response.json().catch(() => ({}));
                                            }).then(data => {
                                                // success: reload or show feedback
                                                Swal.fire({
                                                    title: 'Success',
                                                    text: 'Payment status updated.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    window.location.reload();
                                                });
                                            }).catch(err => {
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: 'Failed to update status.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            });
                                        }
                                    });
                                });
                            });
                        </script>
                    @elseif ($transaction->status_pembayaran == 'denied')
                        <div class="mt-4 btn-red w-full text-center">Denied</div>
                    @elseif ($transaction->status_pembayaran == 'paid')
                        <div class="mt-4 btn-green w-full text-center">Payment confirmed</div>
                    @endif
                    @if ($transaction->bukti_pembayaran)
                        <button type="button" class="btn-primary w-full view-proof-btn"
                            data-url="{{ route('admin.transaction.proof', $transaction->id) }}">
                            Bukti Pembayaran
                        </button>
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
                            <h3 class="text-lg font-semibold mb-2">Bukti Pembayaran</h3>
                            <div class="flex-1 flex items-center justify-center">
                                <img src="" alt="Payment Proof" class="max-w-full max-h-[70vh] rounded proof-img"
                                    style="object-fit: contain;">
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.view-proof-btn').forEach(function(btn) {
                                btn.addEventListener('click', function() {
                                    const url = btn.getAttribute('data-url');
                                    const modalId = 'proof-modal-' + url.split('/').pop();
                                    const modal = document.getElementById(modalId) || document.querySelector(
                                        '[id^="proof-modal-"]');
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
                    <a href="{{ route('admin.transaction.index') }}"
                        class="block text-center w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        Back
                    </a>
                </div>
            </div>
        </form>
    </section>
@endsection
