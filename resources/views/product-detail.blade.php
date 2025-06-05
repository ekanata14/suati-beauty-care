@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 grid grid-cols-1 md:grid-cols-2 gap-8 w-full md:w-3/4">
                    <!-- Left: Image -->
                    <div>
                        <img class="rounded-t-lg h-80" src="{{ asset('storage/' . $product->foto_produk) }}"
                            alt="{{ $product->nama }}" />
                    </div>

                    <!-- Center: Product Description -->
                    <div class="flex flex-col justify-between">
                        <div class="flex flex-col gap-4">
                            <h1 class="text-3xl font-bold">{{ $product->nama }}</h1>
                            <h2 class="text-xl">{{ $product->kategori->nama }}</h2>
                            <div class="flex gap-2">
                                <h2 class="text-md">IDR. {{ number_format($product->harga, 0, ',', '.') }}</h2>
                            |
                                <h3 class="text-md text-green-700 font-bold">Stock: {{ $product->stok }}</h3>
                            </div>
                            <p>{{ $product->deskripsi }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 w-full md:w-1/4">
                    <h2 class="text-xl font-bold text-center mb-3">Jumlah</h2>
                    <!-- Right: Transactional Buttons -->
                    <div class="flex flex-col justify-center items-center">
                        <!-- Quantity Input -->
                        <div class="flex flex-col items-center gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="decrementQty('qty')"
                                    class="px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    -
                                </button>
                                <input type="number" id="qty" name="qty" value="1" min="1"
                                    class="w-16 text-center border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    oninput="updateTotalPrice({{ $product->harga }})">
                                <button type="button" onclick="incrementQty('qty')"
                                    class="px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    +
                                </button>
                            </div>
                            <!-- Total Price -->
                            <div class="text-lg font-bold">
                                Total: IDR <span id="total-price">{{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <script>
                            function updateTotalPrice(price) {
                                const qty = document.getElementById('qty').value;
                                const totalPrice = price * qty;

                                // Update harga total yang ditampilkan
                                document.getElementById('total-price').textContent = new Intl.NumberFormat('id-ID').format(totalPrice);

                                // Update hidden input qty di form
                                document.getElementById('qty-tambah-keranjang').value = qty;
                                document.getElementById('qty-beli-langsung').value = qty;
                            }

                            function incrementQty(inputId) {
                                const qtyInput = document.getElementById(inputId);
                                qtyInput.value = parseInt(qtyInput.value) + 1;
                                updateTotalPrice({{ $product->harga }});
                            }

                            function decrementQty(inputId) {
                                const qtyInput = document.getElementById(inputId);
                                if (parseInt(qtyInput.value) > 1) {
                                    qtyInput.value = parseInt(qtyInput.value) - 1;
                                    updateTotalPrice({{ $product->harga }});
                                }
                            }
                        </script>

                        <div class="flex justify-between gap-4">
                            <!-- Tambah Keranjang Form -->
                            <form action="{{ route('addToCart') }}" method="POST" class="w-full mb-4"
                                id="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="harga" value="{{ $product->harga }}">
                                <input type="hidden" name="qty" id="qty-tambah-keranjang" value="1">
                                <button type="button" onclick="confirmAddToCart()"
                                    class="w-full justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                                    </svg>
                                </button>
                            </form>

                            <!-- Beli Langsung Form -->
                            <form action="{{ route('addToOrder') }}" method="POST" class="w-full mb-4" id="buy-now-form">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="harga" value="{{ $product->harga }}">
                                <input type="hidden" name="qty" id="qty-beli-langsung" value="1">
                                <button type="button" onclick="confirmBuyNow()"
                                    class="w-full justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778" />
                                    </svg>
                                </button>
                            </form>

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                function confirmAddToCart() {
                                    Swal.fire({
                                        title: 'Tambah ke Keranjang?',
                                        text: "Apakah Anda yakin ingin menambahkan produk ini ke keranjang?",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Tambahkan!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('add-to-cart-form').submit();
                                        }
                                    });
                                }

                                function confirmBuyNow() {
                                    Swal.fire({
                                        title: 'Beli Sekarang?',
                                        text: "Apakah Anda yakin ingin membeli produk ini sekarang?",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Beli!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('buy-now-form').submit();
                                        }
                                    });
                                }
                            </script>
                            <!-- Wishlist Button -->
                            <form action="{{ route('addToWishlist') }}" method="POST" class="w-full"
                                id="add-to-wishlist-form">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <button type="button" onclick="confirmAddToWishlist()"
                                    class="w-full justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                                    </svg>
                                </button>
                            </form>

                            <script>
                                function confirmAddToWishlist() {
                                    Swal.fire({
                                        title: 'Tambah ke Wishlist?',
                                        text: "Apakah Anda yakin ingin menambahkan produk ini ke wishlist?",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Tambahkan!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('add-to-wishlist-form').submit();
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Review Section -->
            <div
                class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold mb-4">Review
                    @php
                        $productReviews = $reviews->where('id_product', $product->id);
                        $reviewCount = $productReviews->count();
                        $averageRating = $reviewCount > 0 ? round($productReviews->avg('rating'), 1) : 0;
                    @endphp

                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-yellow-400 flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @elseif ($i - $averageRating < 1 && $i - $averageRating > 0)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-{{ $i }}">
                                                <stop offset="{{ ($averageRating - floor($averageRating)) * 100 }}%"
                                                    stop-color="currentColor" />
                                                <stop offset="{{ ($averageRating - floor($averageRating)) * 100 }}%"
                                                    stop-color="transparent" />
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-{{ $i }})"
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @endif
                            @endfor
                        </span>
                        <span
                            class="text-gray-700 dark:text-gray-300 text-base font-semibold">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-gray-500 text-sm">({{ $reviewCount }}
                            review{{ $reviewCount == 1 ? '' : 's' }})</span>
                    </div>
                </h2>
                @if (Auth::check())
                    @if ($isReview)
                        <p class="text-gray-500 mb-4">You have already reviewed this product.</p>
                    @else
                        <a href="{{ route('review', $product->id) }}" class="btn-primary">Add Review</a>
                    @endif
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    @php
                        $userReviews = [];
                        $otherReviews = [];
                        foreach ($reviews as $review) {
                            if (Auth::check() && $review->user->id === Auth::id()) {
                                $userReviews[] = $review;
                            } else {
                                $otherReviews[] = $review;
                            }
                        }
                        $sortedReviews = array_merge($userReviews, $otherReviews);
                    @endphp

                    @foreach ($sortedReviews as $review)
                        <figure class="max-w-screen-md">
                            <div class="flex items-center mb-4 text-yellow-300">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-5 h-5 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                @endfor
                            </div>
                            <blockquote>
                                <p class="text-md text-gray-900 dark:text-white">"{{ $review->review }}"</p>
                            </blockquote>
                            <figcaption class="flex items-center mt-6 space-x-3 rtl:space-x-reverse">
                                <div
                                    class="flex items-center divide-x-2 rtl:divide-x-reverse divide-gray-300 dark:divide-gray-700">
                                    <cite
                                        class="pe-3 font-medium text-gray-900 dark:text-white">{{ $review->user->name }}</cite>
                                    @if (Auth::check() && Auth::id() === $review->user->id)
                                        <a href="{{ route('review.edit', $review->id) }}"
                                            class="ml-3 px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                    @endif
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
