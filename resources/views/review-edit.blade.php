@extends('layouts.client')

@section('content')
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16 min-h-screen bg-gradient-to-br from-white to-blue-100 dark:from-gray-900 dark:to-gray-800">
        <form method="POST" action="{{ route('review.update') }}" class="mx-auto px-4 2xl:px-0 flex gap-4 justify-center">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $review->id }}">
            <input type="hidden" name="id_product" value="{{ $product->id }}">
            <div class="space-y-6 bg-white dark:bg-gray-800 p-8 w-full md:w-3/4 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700" x-data="{ selectedRating: {{ old('rating', $review->rating) }} }">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">✏️ Edit Your Review</h4>

                <div class="space-y-6">
                    <!-- Produk Info -->
                    <div class="flex items-start gap-6">
                        <img src="{{ asset("storage/{$product->foto_produk}") }}" alt="foto_produk"
                            class="w-32 h-32 rounded-xl shadow object-cover">
                        <div>
                            <h1 class="text-2xl font-extrabold text-blue-700 dark:text-blue-400">{{ $product->nama }}</h1>
                            <p class="text-lg text-gray-500 dark:text-gray-300">{{ $product->kategori->nama }}</p>
                        </div>
                    </div>

                    <!-- Rating Bintang -->
                    <div>
                        <label for="rating" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Rating</label>
                        <div class="flex items-center space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <label>
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                        x-model="selectedRating" {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                    <svg class="w-8 h-8 cursor-pointer transition-transform duration-200"
                                        :class="selectedRating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400'"
                                        fill="currentColor" viewBox="0 0 20 20"
                                        @click="selectedRating = {{ $i }}">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                                    </svg>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div>
                        <label for="review" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Review</label>
                        <textarea name="review" id="review" rows="4"
                            class="block w-full text-sm border border-gray-300 dark:border-gray-600 rounded-xl p-4 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="Write your review here..." required>{{ old('review', $review->review) }}</textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="grid gap-3 sm:grid-cols-2 mt-6">
                    <button type="submit"
                        class="w-full px-5 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-xl shadow-md hover:shadow-lg transition">
                        💾 Update Review
                    </button>
                    <a href="{{ route('products') }}"
                        class="w-full text-center px-5 py-3 text-sm font-semibold border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 rounded-xl dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                        🛍️ Return to Shopping
                    </a>
                </div>
            </div>
        </form>
    </section>
@endsection
