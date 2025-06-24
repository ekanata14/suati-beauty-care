@extends('layouts.client')

@section('content')
    <section
        class="py-8 antialiased dark:bg-gray-900 md:py-16 min-h-screen bg-gradient-to-br from-white to-blue-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-8 flex justify-between items-center" data-aos="fade-left" data-aos-duration="500"
                data-aos-delay="300">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">💙 Your Wishlist</h2>
            </div>

            <div class="space-y-6">
                @forelse ($wishlists as $wishlist)
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-6"
                        data-aos="fade-up" data-aos-duration="500" data-aos-delay="300">
                        <div class="flex flex-col gap-2 w-full md:w-1/3">
                            <a href="#"
                                class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $wishlist->produk->nama }}
                            </a>
                            <img src="{{ asset('storage/' . $wishlist->produk->foto_produk) }}"
                                alt="{{ $wishlist->produk->nama }}" class="w-full h-auto rounded-xl shadow">
                        </div>

                        <div class="w-full md:w-1/3 space-y-2">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">💰 Price:</span>
                                IDR. {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">🏷️ Category:</span>
                                {{ $wishlist->produk->kategori->nama }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">🕒 Added on:</span>
                                {{ $wishlist->created_at->format('d M Y') }}
                            </p>
                        </div>

                        <div class="w-full md:w-1/3 flex flex-col gap-3">
                            <a href="{{ route('products.detail', $wishlist->produk->id) }}"
                                class="w-full text-center rounded-xl border border-blue-300 bg-white px-5 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700 transition shadow">
                                🔍 View Details
                            </a>

                            <form method="POST" action="{{ route('removeFromWishlist') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="wishlist_id" value="{{ $wishlist->id }}">
                                <button type="button" onclick="confirmDelete(this)"
                                    class="w-full rounded-xl border border-red-300 bg-red-500 px-5 py-3 text-sm font-semibold text-white hover:bg-red-600 dark:border-red-600 dark:bg-red-700 dark:hover:bg-red-800 transition shadow">
                                    🗑️ Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400 py-12 text-lg">
                        📭 Tidak ada wishlist yang tersedia.
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $wishlists->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

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
    </section>
@endsection
