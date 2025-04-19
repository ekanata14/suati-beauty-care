@extends('layouts.client')

@section('content')
    <section class="h-[70vh] flex flex-col md:flex-row justify-center items-center">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4">LOGOOOOOOOOOOOO</h1>
        </div>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4">SUATI BEAUTY CARE</h1>
            <p class="text-gray-700 mb-6">We are glad to have you here. Explore our products and services.</p>
        </div>
    </section>
    <section class="h-full flex justify-center items-start">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-12">Products</h1>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @forelse ($products as $product)
                    <div
                        class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-t-lg" src="{{ asset('storage/' . $product->foto_produk) }}"
                                alt="{{ $product->nama }}" />
                        </a>
                        <div class="p-5">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $product->nama }}
                                </h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                {{ $product->deskripsi }}
                            </p>
                            <p class="mb-3 font-bold text-gray-900 dark:text-white">
                                ${{ $product->harga }}
                            </p>
                            <a href="#"
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
                        <p class="text-gray-500">No products available</p>
                    </div>
                @endforelse
            </div>
            <div class="flex justify-center">
                <a href="{{ route('products') }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-8">
                    View More
                </a>
            </div>
        </div>
    </section>
@endsection
