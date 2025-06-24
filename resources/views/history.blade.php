@extends('layouts.client')

@section('content')
<section class="py-12 bg-gradient-to-b from-white to-blue-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8 flex justify-between items-center" data-aos="fade-left" data-aos-duration="500">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h2>
        </div>

        <div class="space-y-6">
            @forelse ($transactions as $item)
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow flex flex-col md:flex-row md:items-center md:justify-between" data-aos="fade-up" data-aos-duration="500" data-aos-delay="300">
                    <div class="flex flex-col gap-1">
                        <a href="#" class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline">{{ $item->invoice_id }}</a>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item->created_at }}</span>
                    </div>

                    <div class="mt-4 md:mt-0 md:text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $item->total_qty_item }}</span> items
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-900 dark:text-white">IDR {{ number_format($item->total_bayar, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0">
                        @if ($item->status_pembayaran === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium">
                                Pending
                            </span>
                        @elseif ($item->status_pembayaran === 'denied')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium">
                                Denied
                            </span>
                        @elseif ($item->status_pembayaran === 'waiting')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-medium">
                                Waiting
                            </span>
                        @elseif ($item->status_pembayaran === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium">
                                Paid
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('history.detail', $item->id) }}"
                            class="inline-block px-4 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-white">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-12 text-lg">
                    Tidak ada transaksi yang tersedia.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    </div>
</section>
@endsection
