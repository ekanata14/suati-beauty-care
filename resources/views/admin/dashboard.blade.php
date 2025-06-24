@extends('layouts.admin')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            <div class="grid grid-cols-3 mt-4">
                @foreach ($dashboardData as $key => $item)
                    @if (in_array($key, ['users', 'products', 'orders']))
                        <div
                            class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 m-2 block hover:shadow-lg transition">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                @switch($key)
                                    @case('users')
                                        Total Pelanggan
                                    @break

                                    @case('products')
                                        Total Produk
                                    @break

                                    @case('orders')
                                        Total Pesanan
                                    @break

                                    @default
                                        {{ ucfirst($key) }}
                                @endswitch
                            </h5>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-3xl">
                                {{ $item['count'] }}
                            </p>
                            <a href="{{ $item['link'] }}"
                                class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Detail
                            </a>
                        </div>
                    @elseif ($key === 'recentOrders')
                        <div
                            class="col-span-3 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 m-2">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    Pesanan Terbaru
                                </h5>
                                <a href="{{ $item['link'] }}" class="text-blue-600 hover:underline">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">ID</th>
                                            <th class="px-4 py-2">Pelanggan</th>
                                            <th class="px-4 py-2">Total Payment</th>
                                            <th class="px-4 py-2">Tanggal</th>
                                            <th class="px-4 py-2">Status</th>
                                            <th class="px-4 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($item['data'] as $data)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $data->id }}</td>
                                                <td class="px-4 py-2">{{ $data->order->user->name ?? '-' }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $data->total_bayar ? 'Rp ' . number_format($data->total_bayar, 0, ',', '.') : '-' }}
                                                </td>
                                                <td class="px-4 py-2">{{ $data->created_at->format('d M Y') }}</td>
                                                <td class="px-4 py-2">
                                                    @php
                                                        $status = strtolower($data->status_pembayaran);
                                                        $badgeClasses = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'waiting' => 'bg-blue-100 text-blue-800',
                                                            'paid' => 'bg-green-100 text-green-800',
                                                            'denied' => 'bg-red-100 text-red-800',
                                                        ];
                                                        $class = $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $class }}">
                                                        {{ ucfirst($data->status_pembayaran) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('admin.transaction.detail', $data->id) }}"
                                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-2 text-center">Tidak ada pesanan terbaru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
