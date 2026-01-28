@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start min-h-screen bg-gradient-to-br from-white to-blue-100">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Left: Produk -->
                <div class="bg-white rounded-2xl shadow-md p-8 w-full md:w-3/4" data-aos="fade-right" data-aos-duration="500"
                    data-aos-delay="300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-4">
                            <!-- Main Image -->
                            <div class="rounded-xl overflow-hidden bg-gray-100">
                                <img id="main-image" class="w-full h-full object-cover cursor-pointer"
                                    src="{{ asset('storage/' . $product->produkPhotos->first()?->url ?? $product->foto_produk) }}"
                                    alt="{{ $product->nama }}" />
                            </div>

                            <!-- Thumbnail Gallery -->
                            @if ($product->produkPhotos->count() > 1)
                                <div class="flex gap-2 overflow-x-auto">
                                    @foreach ($product->produkPhotos as $photo)
                                        <img class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 border-gray-200 hover:border-blue-500 transition"
                                            src="{{ asset('storage/' . $photo->url) }}"
                                            alt="{{ $product->nama }}"
                                            onclick="document.getElementById('main-image').src = this.src" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-4">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $product->nama }}</h1>
                            <h2 class="text-xl text-blue-700">Kategori: {{ $product->kategori->nama }}</h2>
                            <p class="text-black">Ukuran Tersedia:</p>
                            <ul class="list-disc pl-5">
                                @foreach ($product->sizes as $size)
                                    <li>{{ $size->size }} (Stok: {{ $size->stock }})</li>
                                @endforeach
                            </ul>
                            <div class="flex gap-3 items-center">
                                <p class="text-xl font-semibold text-gray-700">IDR
                                    {{ number_format($product->harga, 0, ',', '.') }}</p>
                                <p class="text-green-600 font-semibold">Stok: {{ $product->stok }}</p>
                            </div>
                            <p class="text-gray-600 leading-relaxed">{{ $product->deskripsi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Transaksi -->
                <div class="bg-white rounded-2xl shadow-md p-8 w-full md:w-1/4" data-aos="fade-left" data-aos-duration="500"
                    data-aos-delay="300">
                    {{-- <h2 class="text-xl font-bold text-center mb-4 text-gray-800">Jumlah</h2> --}}
                    <div class="flex flex-col items-center gap-4">
                        {{-- <div class="flex items-center gap-2">
                            <button onclick="decrementQty('qty')"
                                class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800">-</button>
                            <input type="number" id="qty" name="qty" value="1" min="1"
                                class="w-16 text-center border rounded focus:ring focus:ring-blue-300"
                                oninput="updateTotalPrice({{ $product->harga }})">
                            <button onclick="incrementQty('qty')"
                                class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800">+</button>
                        </div>
                        <p class="font-bold text-lg">Total: IDR <span
                                id="total-price">{{ number_format($product->harga, 0, ',', '.') }}</span></p> --}}

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
                            <!-- Add to Cart Button triggers modal -->
                            {{-- <button type="button" onclick="openSizeModal()"
                                class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah ke Keranjang</button> --}}

                            <!-- Size Selection Modal -->
                            <div id="size-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                                <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-2 sm:mx-4 md:mx-0"
                                    style="box-sizing: border-box; max-height:fit-content; overflow-y:auto;">
                                    <h3 class="text-lg font-bold mb-4 text-gray-800">Pilih Ukuran & Jumlah</h3>
                                    <form id="size-selection-form" onsubmit="event.preventDefault(); submitSizeSelection();">
                                        <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                        <div class="space-y-3" style="max-height:60vh; overflow-y:auto;">
                                            @foreach ($product->sizes as $size)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                    <span class="font-medium">{{ $size->size }} (Stok: {{ $size->stock }})</span>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button"
                                                            class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800"
                                                            onclick="decrementSizeQty({{ $loop->index }}, {{ $size->stock }})">-</button>
                                                        <input type="number" min="0" max="{{ $size->stock }}" value="0"
                                                            name="sizes[{{ $loop->index }}][qty]"
                                                            id="size-qty-{{ $loop->index }}"
                                                            class="w-16 border rounded text-center"
                                                            data-size="{{ $size->size }}"
                                                            data-price="{{ $size->price ?? $product->harga }}"
                                                            oninput="updateSizePriceDetail()">
                                                        <button type="button"
                                                            class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800"
                                                            onclick="incrementSizeQty({{ $loop->index }}, {{ $size->stock }})">+</button>
                                                    </div>
                                                    <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $size->size }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Price Detail -->
                                        <div class="mt-4 bg-gray-50 rounded p-3">
                                            <h4 class="font-semibold text-gray-700 mb-2">Detail Harga</h4>
                                            <div id="size-price-detail" class="text-sm text-gray-800"></div>
                                            <div class="mt-2 font-bold text-blue-700">Total: IDR <span id="size-total-price">0</span></div>
                                        </div>
                                        <div class="mt-6 flex flex-col sm:flex-row gap-2">
                                            <button type="submit"
                                                class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan & Tambah ke Keranjang</button>
                                            <button type="button" onclick="closeSizeModal()"
                                                class="w-full py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Hidden Add to Cart Form -->
                            <form id="add-to-cart-form" action="{{ route('addToCart') }}" method="POST" style="display:none;">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="qty" id="qty-tambah-keranjang" value="1">
                                <input type="hidden" name="sizes" id="sizes-json">
                            </form>

                            <script>
                                function openSizeModal() {
                                    document.getElementById('size-modal').classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                    updateSizePriceDetail();
                                }
                                function closeSizeModal() {
                                    document.getElementById('size-modal').classList.add('hidden');
                                    document.body.style.overflow = '';
                                }
                                function incrementSizeQty(index, maxStock) {
                                    const input = document.getElementById('size-qty-' + index);
                                    let val = parseInt(input.value) || 0;
                                    if (val < maxStock) {
                                        input.value = val + 1;
                                        updateSizePriceDetail();
                                    }
                                }
                                function decrementSizeQty(index, maxStock) {
                                    const input = document.getElementById('size-qty-' + index);
                                    let val = parseInt(input.value) || 0;
                                    if (val > 0) {
                                        input.value = val - 1;
                                        updateSizePriceDetail();
                                    }
                                }
                                function updateSizePriceDetail() {
                                    const inputs = document.querySelectorAll('#size-selection-form input[type="number"][name^="sizes"]');
                                    let html = '';
                                    let total = 0;
                                    inputs.forEach(input => {
                                        const qty = parseInt(input.value) || 0;
                                        const size = input.getAttribute('data-size');
                                        const price = parseInt(input.getAttribute('data-price')) || 0;
                                        if (qty > 0) {
                                            html += `<div>${size}: ${qty} x IDR ${new Intl.NumberFormat('id-ID').format(price)} = <span class="font-semibold">IDR ${new Intl.NumberFormat('id-ID').format(qty * price)}</span></div>`;
                                            total += qty * price;
                                        }
                                    });
                                    document.getElementById('size-price-detail').innerHTML = html || '<span class="text-gray-400">Belum ada ukuran dipilih.</span>';
                                    document.getElementById('size-total-price').innerText = new Intl.NumberFormat('id-ID').format(total);
                                }
                                function submitSizeSelection() {
                                    // Collect sizes and qtys
                                    const form = document.getElementById('size-selection-form');
                                    const inputs = form.querySelectorAll('input[type="number"][name^="sizes"]');
                                    let sizes = [];
                                    let totalQty = 0;
                                    inputs.forEach(input => {
                                        const qty = parseInt(input.value) || 0;
                                        const size = input.getAttribute('data-size');
                                        if (qty > 0) {
                                            sizes.push({ size: size, qty: qty });
                                            totalQty += qty;
                                        }
                                    });
                                    if (sizes.length === 0) {
                                        Swal.fire('Pilih minimal satu ukuran dengan jumlah lebih dari 0.', '', 'warning');
                                        return;
                                    }
                                    // Set hidden fields in add-to-cart-form
                                    document.getElementById('qty-tambah-keranjang').value = totalQty;
                                    document.getElementById('sizes-json').value = JSON.stringify(sizes);
                                    closeSizeModal();
                                    confirmAddToCart();
                                }
                                // Close modal on background click
                                document.addEventListener('click', function(e) {
                                    const modal = document.getElementById('size-modal');
                                    if (!modal.classList.contains('hidden') && e.target === modal) {
                                        closeSizeModal();
                                    }
                                });
                                // Close modal on ESC key
                                document.addEventListener('keydown', function(e) {
                                    if (e.key === 'Escape') {
                                        closeSizeModal();
                                    }
                                });
                            </script>
                            <!-- Buy Now Button triggers modal -->
                            <button type="button" onclick="openBuyNowSizeModal()"
                                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Beli Sekarang</button>

                            <!-- Buy Now Size Selection Modal -->
                            <div id="buy-now-size-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                                <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-2 sm:mx-4 md:mx-0"
                                    style="box-sizing: border-box; max-height:fit-content; overflow-y:auto;">
                                    <h3 class="text-lg font-bold mb-4 text-gray-800">Pilih Ukuran & Jumlah</h3>
                                    <form id="buy-now-size-selection-form" onsubmit="event.preventDefault(); submitBuyNowSizeSelection();">
                                        <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                        <input type="hidden" name="harga" value="{{ $product->harga }}">
                                        <div class="space-y-3" style="max-height:60vh; overflow-y:auto;">
                                            @foreach ($product->sizes as $size)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                    <span class="font-medium">{{ $size->size }} (Stok: {{ $size->stock }})</span>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button"
                                                            class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800"
                                                            onclick="decrementBuyNowSizeQty({{ $loop->index }}, {{ $size->stock }})">-</button>
                                                        <input type="number" min="0" max="{{ $size->stock }}" value="0"
                                                            name="sizes[{{ $loop->index }}][qty]"
                                                            id="buy-now-size-qty-{{ $loop->index }}"
                                                            class="w-16 border rounded text-center"
                                                            data-size="{{ $size->size }}"
                                                            data-price="{{ $size->price ?? $product->harga }}"
                                                            oninput="updateBuyNowSizePriceDetail()">
                                                        <button type="button"
                                                            class="w-8 h-8 bg-gray-700 text-white rounded hover:bg-gray-800"
                                                            onclick="incrementBuyNowSizeQty({{ $loop->index }}, {{ $size->stock }})">+</button>
                                                    </div>
                                                    <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $size->size }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Price Detail -->
                                        <div class="mt-4 bg-gray-50 rounded p-3">
                                            <h4 class="font-semibold text-gray-700 mb-2">Detail Harga</h4>
                                            <div id="buy-now-size-price-detail" class="text-sm text-gray-800"></div>
                                            <div class="mt-2 font-bold text-blue-700">Total: IDR <span id="buy-now-size-total-price">0</span></div>
                                        </div>
                                        <div class="mt-6 flex flex-col sm:flex-row gap-2">
                                            <button type="submit"
                                                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan & Beli Sekarang</button>
                                            <button type="button" onclick="closeBuyNowSizeModal()"
                                                class="w-full py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Hidden Buy Now Form -->
                            <form id="buy-now-form" action="{{ route('addToOrder') }}" method="POST" style="display:none;">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                <input type="hidden" name="harga" value="{{ $product->harga }}">
                                <input type="hidden" name="qty" id="qty-beli-langsung" value="1">
                                <input type="hidden" name="sizes" id="buy-now-sizes-json">
                            </form>

                            <script>
                                function openBuyNowSizeModal() {
                                    document.getElementById('buy-now-size-modal').classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                    updateBuyNowSizePriceDetail();
                                }
                                function closeBuyNowSizeModal() {
                                    document.getElementById('buy-now-size-modal').classList.add('hidden');
                                    document.body.style.overflow = '';
                                }
                                function incrementBuyNowSizeQty(index, maxStock) {
                                    const input = document.getElementById('buy-now-size-qty-' + index);
                                    let val = parseInt(input.value) || 0;
                                    if (val < maxStock) {
                                        input.value = val + 1;
                                        updateBuyNowSizePriceDetail();
                                    }
                                }
                                function decrementBuyNowSizeQty(index, maxStock) {
                                    const input = document.getElementById('buy-now-size-qty-' + index);
                                    let val = parseInt(input.value) || 0;
                                    if (val > 0) {
                                        input.value = val - 1;
                                        updateBuyNowSizePriceDetail();
                                    }
                                }
                                function updateBuyNowSizePriceDetail() {
                                    const inputs = document.querySelectorAll('#buy-now-size-selection-form input[type="number"][name^="sizes"]');
                                    let html = '';
                                    let total = 0;
                                    inputs.forEach(input => {
                                        const qty = parseInt(input.value) || 0;
                                        const size = input.getAttribute('data-size');
                                        const price = parseInt(input.getAttribute('data-price')) || 0;
                                        if (qty > 0) {
                                            html += `<div>${size}: ${qty} x IDR ${new Intl.NumberFormat('id-ID').format(price)} = <span class="font-semibold">IDR ${new Intl.NumberFormat('id-ID').format(qty * price)}</span></div>`;
                                            total += qty * price;
                                        }
                                    });
                                    document.getElementById('buy-now-size-price-detail').innerHTML = html || '<span class="text-gray-400">Belum ada ukuran dipilih.</span>';
                                    document.getElementById('buy-now-size-total-price').innerText = new Intl.NumberFormat('id-ID').format(total);
                                }
                                function submitBuyNowSizeSelection() {
                                    // Collect sizes and qtys
                                    const form = document.getElementById('buy-now-size-selection-form');
                                    const inputs = form.querySelectorAll('input[type="number"][name^="sizes"]');
                                    let sizes = [];
                                    let totalQty = 0;
                                    inputs.forEach(input => {
                                        const qty = parseInt(input.value) || 0;
                                        const size = input.getAttribute('data-size');
                                        if (qty > 0) {
                                            sizes.push({ size: size, qty: qty });
                                            totalQty += qty;
                                        }
                                    });
                                    if (sizes.length === 0) {
                                        Swal.fire('Pilih minimal satu ukuran dengan jumlah lebih dari 0.', '', 'warning');
                                        return;
                                    }
                                    // Set hidden fields in buy-now-form
                                    document.getElementById('qty-beli-langsung').value = totalQty;
                                    document.getElementById('buy-now-sizes-json').value = JSON.stringify(sizes);
                                    closeBuyNowSizeModal();
                                    confirmBuyNow();
                                }
                                // Close modal on background click
                                document.addEventListener('click', function(e) {
                                    const modal = document.getElementById('buy-now-size-modal');
                                    if (!modal.classList.contains('hidden') && e.target === modal) {
                                        closeBuyNowSizeModal();
                                    }
                                });
                                // Close modal on ESC key
                                document.addEventListener('keydown', function(e) {
                                    if (e.key === 'Escape') {
                                        closeBuyNowSizeModal();
                                    }
                                });
                            </script>
                            <form id="add-to-wishlist-form" action="{{ route('addToWishlist') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $product->id }}">
                                {{-- <button type="button" onclick="confirmAddToWishlist()"
                                    class="w-full py-2 bg-red-600 text-white rounded hover:bg-red-700">Wishlist</button> --}}
                            </form>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            function confirmAddToCart() {
                                Swal.fire({
                                    title: 'Tambah ke Keranjang?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya',
                                    cancelButtonText: 'Batal'
                                }).then(r => r.isConfirmed && document.getElementById('add-to-cart-form').submit());
                            }

                            function confirmBuyNow() {
                                Swal.fire({
                                    title: 'Beli Sekarang?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya',
                                    cancelButtonText: 'Batal'
                                }).then(r => r.isConfirmed && document.getElementById('buy-now-form').submit());
                            }

                            function confirmAddToWishlist() {
                                Swal.fire({
                                    title: 'Tambah ke Wishlist?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya',
                                    cancelButtonText: 'Batal'
                                }).then(r => r.isConfirmed && document.getElementById('add-to-wishlist-form').submit());
                            }
                        </script>
                    </div>
                </div>
            </div>

            {{-- <!-- Ulasan -->
            <div class="mt-12 bg-white rounded-2xl shadow-md p-8" data-aos="fade-up" data-aos-duration="500"
                data-aos-delay="500">
                @php
                    $productReviews = $reviews->where('id_product', $product->id);
                    $reviewCount = $productReviews->count();
                    $averageRating = $reviewCount > 0 ? round($productReviews->avg('rating'), 1) : 0;
                @endphp

                <h2 class="text-2xl font-bold mb-4 text-gray-800">Ulasan ({{ $reviewCount }})</h2>

                <div class="flex items-center gap-2 mb-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" />
                        </svg>
                    @endfor
                    <span class="text-gray-700 text-base font-medium">{{ number_format($averageRating, 1) }}</span>
                </div>

                @if (Auth::check())
                    @if ($isReview)
                        <p class="text-gray-500 mb-4">Anda sudah memberikan ulasan untuk produk ini.</p>
                    @else
                        <a href="{{ route('review', $product->id) }}"
                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tulis Ulasan</a>
                    @endif
                @endif

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($reviews as $review)
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center mb-2">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-700">"{{ $review->review }}"</p>
                            <div class="mt-2 text-sm text-gray-500">- {{ $review->user->name }}</div>
                            @if (Auth::check() && Auth::id() === $review->user->id)
                                <a href="{{ route('review.edit', $review->id) }}"
                                    class="inline-block mt-2 text-xs text-blue-600 hover:underline">Edit</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </section>
@endsection
