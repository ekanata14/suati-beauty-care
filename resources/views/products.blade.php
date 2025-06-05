@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4 text-center">Products</h1>
            <form class="max-w-md mx-auto mb-4" method="GET" action="{{ route('products.search') }}">
                <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" name="nama" value="{{ request('nama') }}"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search Products..." required />
                    <button type="submit"
                        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                </div>
            </form>
            <div class="flex justify-center items-center gap-4">
                @if ($categories)
                    @if (route('products') == request()->url())
                        <a href="{{ route('products') }}" class="btn-primary">All</a>
                    @else
                        <a href="{{ route('products') }}" class="btn-alternative">All</a>
                    @endif
                @endif
                @forelse ($categories as $item)
                    @if ($item->id == $id)
                        <a href="{{ route('products.category', $item->id) }}" class="btn-primary">{{ $item->nama }}</a>
                    @else
                        <a href="{{ route('products.category', $item->id) }}"
                            class="btn-alternative">{{ $item->nama }}</a>
                    @endif
                @empty
                    <div class="text-center">
                        <p class="text-gray-500">No categories available</p>
                    </div>
                @endforelse
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @forelse ($products as $product)
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <a href="{{ route('products.detail', $product->id) }}">
                            <img class="rounded-t-lg" src="{{ asset('storage/' . $product->foto_produk) }}"
                                alt="{{ $product->nama }}" />
                        </a>
                        <div class="p-5">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $product->nama }}
                                </h5>
                            </a>
                            {{-- Average Rating --}}
                            @php
                                $averageRating = $product->reviews->avg('rating');
                                $reviewCount = $product->reviews->count();
                            @endphp
                            <div class="flex items-center mb-2">
                                @if($reviewCount > 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating))
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($averageRating, 1) }} / 5 ({{ $reviewCount }} review{{ $reviewCount > 1 ? 's' : '' }})
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">No reviews yet</span>
                                @endif
                            </div>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                {{ $product->deskripsi }}
                            </p>
                            <p class="mb-3 font-bold text-gray-900 dark:text-white">
                                IDR. {{ number_format($product->harga, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('products.detail', $product->id) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                View Details
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        <p class="text-gray-500 text-center">No products available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
