@extends('layouts.admin')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.review.update') }}" class="mx-auto px-4 2xl:px-0 flex gap-4 justify-center">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $review->id }}">
                    <input type="hidden" name="id_product" value="{{ $review->product->id }}">
                    <div class="space-y-6 bg-white p-6 w-full md:w-3/4 rounded-lg shadow" x-data="{ selectedRating: {{ old('rating', $review->rating) }} }">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Review</h4>

                        <div class="space-y-4">
                            <!-- Produk Info -->
                            <div class="space-y-2">
                                <img src="{{ asset("storage/{$review->product->foto_produk}") }}" alt="foto_produk"
                                    class="w-full md:w-1/4 h-auto rounded-lg">
                                <h1 class="text-2xl font-bold">{{ $review->product->nama }}</h1>
                                <h2 class="text-xl text-gray-600">{{ $review->product->kategori->nama }}</h2>
                            </div>

                            <!-- Rating Bintang -->
                            <div class="space-y-2">
                                <label for="rating" class="block text-sm font-medium text-gray-900 dark:text-white">Rating</label>
                                <div class="flex items-center space-x-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label>
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                                x-model="selectedRating" {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                            <svg class="w-6 h-6 cursor-pointer transition"
                                                :class="selectedRating >= {{ $i }} ? 'text-yellow-500 dark:text-yellow-400' :
                                                    'text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400'"
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
                            <div class="space-y-2">
                                <label for="review" class="block text-sm font-medium text-gray-900 dark:text-white">Review</label>
                                <textarea name="review" id="review" rows="4"
                                    class="block w-full text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Write your review here..." required>{{ old('review', $review->review) }}</textarea>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex flex-col gap-2">
                            <button type="submit"
                                class="btn-yellow text-black">
                                Update Review
                            </button>
                            <a href="{{ route('products') }}"
                                class="w-full text-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                Return to Shopping
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
