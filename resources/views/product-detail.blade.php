@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start">
        <div class="container mx-auto px-4 py-8">
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 grid grid-cols-2 gap-8">
                <img class="rounded-t-lg" src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama }}" />
                <div class="flex flex-col justify-between">
                    <div class="flex flex-col gap-4">
                        <h1 class="text-3xl font-bold">{{ $product->nama }}</h1>
                        <h2 class="text-xl">{{ $product->kategori->nama }}</h2>
                        <h2 class="text-md">IDR. {{ number_format($product->harga, 0, ',', '.') }}</h2>
                        <p>{{ $product->deskripsi }}</p>
                    </div>
                    <form action="{{ route('addToOrder') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $product->id }}">
                        <input type="hidden" name="harga" value="{{ $product->harga }}">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="decrementQty()"
                                class="px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">-</button>
                            <input type="number" id="qty" name="qty" value="1" min="1"
                                class="w-16 text-center border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <button type="button" onclick="incrementQty()"
                                class="px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">+</button>
                        </div>
                        <button type="submit"
                            class="w-full mt-4 justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Add to Order
                        </button>
                    </form>

                    <script>
                        function incrementQty() {
                            const qtyInput = document.getElementById('qty');
                            qtyInput.value = parseInt(qtyInput.value) + 1;
                        }

                        function decrementQty() {
                            const qtyInput = document.getElementById('qty');
                            if (parseInt(qtyInput.value) > 1) {
                                qtyInput.value = parseInt(qtyInput.value) - 1;
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </section>
@endsection
