@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start min-h-screen bg-gradient-to-br from-white to-blue-100">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Left: Produk -->
                <div class="bg-white rounded-2xl shadow-md p-8 w-full md:w-3/4" data-aos="fade-right" data-aos-duration="500" data-aos-delay="300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <img class="rounded-xl w-full h-80 object-cover" src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama }}" />
                        </div>
                        <div class="flex flex-col gap-4">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $product->nama }}</h1>
                            <h2 class="text-xl text-blue-700">Kategori: {{ $product->kategori->nama }}</h2>
                            <div class="flex gap-3 items-center">
                                <p class="text-xl font-semibold text-gray-700">IDR {{ number_format($product->harga, 0, ',', '.') }}</p>
                                <p class="text-green-600 font-semibold">Stok: {{ $product->stok }}</p>
                            </div>
                            <p class="text-gray-600 leading-relaxed">{{ $product->deskripsi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Transaksi -->
                <div class="bg-white rounded-2xl shadow-md p-8 w-full md:w-1/4" data-aos="fade-left" data-aos-duration="500" data-aos-delay="300">
                    <h2 class="text-xl font-bold text-center mb-4 text-gray-800">Jumlah</h2>
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex items-center gap-2">
                            <button onclick="decrementQty('qty')" class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800">-</button>
                            <input type="number" id="qty" name="qty" value="1" min="1" class="w-16 text-center border rounded focus:ring focus:ring-blue-300" oninput="updateTotalPrice({{ $product->harga }})">
                            <button onclick="incrementQty('qty')" class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800">+</button>
                        </div>
                        <p class="font-bold text-lg">Total: IDR <span id="total-price">{{ number_format($product->harga, 0, ',', '.') }}</span></p>

                        <script>
                            function updateTotalPrice(price) {
                                const qty = document.getElementById('qty').value;
                                const total = price * qty;
                                document.getElementById('total-price').innerText = new Intl.NumberFormat('id-ID').format(total);
                                document.getElementById('qty-tambah-keranjang').value = qty;
                                document.getElementById('qty-beli-langsung').value = qty;
                            }
                            function incrementQty(id) {
                                const input = document.getElementById(id);
                                input.value = parseInt(input.value) + 1;
                                updateTotalPrice({{ $product->harga }});
                            }
                            function decrementQty(id) {
                                const input = document.getElementById(id);
                                if (parseInt(input.value) > 1) {
                                    input.value = parseInt(input.value) - 1;
                                    updateTotalPrice({{ $product->harga }});
                                }
                            }
                        </script>

                        <div class="w-full space-y-2">
                            <form id="add-to-cart-form" action="{{ route('addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="harga" value="{{ $product->harga }}">
                                <input type="hidden" name="qty" id="qty-tambah-keranjang" value="1">
                                <button type="button" onclick="confirmAddToCart()" class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah ke Keranjang</button>
                            </form>
                            <form id="buy-now-form" action="{{ route('addToOrder') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="harga" value="{{ $product->harga }}">
                                <input type="hidden" name="qty" id="qty-beli-langsung" value="1">
                                <button type="button" onclick="confirmBuyNow()" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Beli Sekarang</button>
                            </form>
                            <form id="add-to-wishlist-form" action="{{ route('addToWishlist') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <button type="button" onclick="confirmAddToWishlist()" class="w-full py-2 bg-red-600 text-white rounded hover:bg-red-700">Wishlist</button>
                            </form>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            function confirmAddToCart() {
                                Swal.fire({ title: 'Tambah ke Keranjang?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then(r => r.isConfirmed && document.getElementById('add-to-cart-form').submit());
                            }
                            function confirmBuyNow() {
                                Swal.fire({ title: 'Beli Sekarang?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then(r => r.isConfirmed && document.getElementById('buy-now-form').submit());
                            }
                            function confirmAddToWishlist() {
                                Swal.fire({ title: 'Tambah ke Wishlist?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal' }).then(r => r.isConfirmed && document.getElementById('add-to-wishlist-form').submit());
                            }
                        </script>
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <div class="mt-12 bg-white rounded-2xl shadow-md p-8" data-aos="fade-up" data-aos-duration="500" data-aos-delay="500">
                @php
                    $productReviews = $reviews->where('id_product', $product->id);
                    $reviewCount = $productReviews->count();
                    $averageRating = $reviewCount > 0 ? round($productReviews->avg('rating'), 1) : 0;
                @endphp

                <h2 class="text-2xl font-bold mb-4 text-gray-800">Ulasan ({{ $reviewCount }})</h2>

                <div class="flex items-center gap-2 mb-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" />
                        </svg>
                    @endfor
                    <span class="text-gray-700 text-base font-medium">{{ number_format($averageRating, 1) }}</span>
                </div>

                @if (Auth::check())
                    @if ($isReview)
                        <p class="text-gray-500 mb-4">Anda sudah memberikan ulasan untuk produk ini.</p>
                    @else
                        <a href="{{ route('review', $product->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tulis Ulasan</a>
                    @endif
                @endif

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($reviews as $review)
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center mb-2">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-700">"{{ $review->review }}"</p>
                            <div class="mt-2 text-sm text-gray-500">- {{ $review->user->name }}</div>
                            @if (Auth::check() && Auth::id() === $review->user->id)
                                <a href="{{ route('review.edit', $review->id) }}" class="inline-block mt-2 text-xs text-blue-600 hover:underline">Edit</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection