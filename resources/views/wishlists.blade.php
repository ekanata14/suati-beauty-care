@extends('layouts.client')

@section('content')
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mx-auto max-w-5xl">
                <div class="gap-4 lg:flex lg:items-center lg:justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Wishlist</h2>
                </div>

                <div class="mt-6 flow-root sm:mt-8">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($wishlists as $wishlist)
                            <div class="relative grid grid-cols-2 gap-4 py-6 sm:grid-cols-4 lg:grid-cols-5">
                                <div class="col-span-2 content-center sm:col-span-4 lg:col-span-1">
                                    <a href="#"
                                        class="text-base font-semibold text-gray-900 hover:underline dark:text-white">
                                        {{ $wishlist->produk->nama }}
                                    </a>
                                    <img src="{{ asset('storage/' . $wishlist->produk->foto_produk) }}"
                                        alt="{{ $wishlist->produk->nama }}" class="mt-2 w-full h-auto rounded-lg">
                                </div>

                                <div class="content-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Added on: {{ $wishlist->created_at }}
                                        </p>
                                    </div>
                                </div>

                                <div class="content-center">
                                    <div class="flex items-start justify-end gap-2 sm:justify-start flex-col">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-900 dark:text-white">Price:</span>
                                            IDR. {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-900 dark:text-white">Category:</span>
                                            {{ $wishlist->produk->kategori->nama }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-span-2 content-center sm:col-span-1 sm:justify-self-end w-full text-center">
                                    <a href="{{ route('products.detail', $wishlist->produk->id) }}"
                                        class="block w-full col-span-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:inline-block sm:w-auto">View
                                        details</a>
                                </div>
                                <div class="col-span-2 content-center sm:col-span-1 sm:justify-self-end">
                                    <form method="POST" action="{{ route('removeFromWishlist') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="wishlist_id" value="{{ $wishlist->id }}">
                                        <button type="button" onclick="confirmDelete(this)"
                                            class="w-full rounded-lg border border-red-200 bg-red-500 px-3 py-2 text-sm font-medium text-white hover:bg-red-600 focus:z-10 focus:outline-none focus:ring-4 focus:ring-red-300 dark:border-red-600 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-900 sm:w-auto">
                                            Remove
                                        </button>
                                    </form>

                                    <script>
                                        function confirmDelete(button) {
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "You won't be able to revert this!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    button.closest('form').submit();
                                                }
                                            });
                                        }
                                    </script>
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <p class="text-gray-500">No items in your wishlist</p>
                            </div>
                        @endforelse

                        <div class="mt-6">
                            {{ $wishlists->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
    </section>
@endsection
