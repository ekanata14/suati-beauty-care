@extends('layouts.client')

@section('content')
    <section class="h-full flex justify-center items-start min-h-screen bg-gradient-to-br from-white to-blue-100">
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-4xl font-bold mb-8 text-center text-gray-800" data-aos="fade-left" data-aos-duration="500">Produk Kami</h1>

            {{-- Search Bar --}}
            <form class="max-w-xl mx-auto mb-8" method="GET" action="{{ route('products.search') }}" data-aos="fade-up" data-aos-duration="500">
                <label for="default-search" class="sr-only">Cari Produk</label>
                <div class="relative" data-aos="fade-up" data-aos-duration="500">
                    {{-- Search Icon --}}
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                    </div>
                    <input type="search" id="default-search" name="nama" value="{{ request('nama') }}"
                        class="w-full p-4 pl-10 text-sm border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
                        placeholder="Cari Produk..." required />
                    <button type="submit"
                        class="absolute right-2.5 bottom-2.5 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Cari
                    </button>
                </div>
            </form>

            {{-- Filter Kategori --}}
            <div class="flex flex-wrap justify-center items-center gap-3 mb-10" data-aos="fade-up" data-aos-duration="500">
                {{-- Jika ada kategori --}}
                @if ($categories)
                    <a href="{{ route('products') }}" class="{{ request()->url() == route('products') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} px-4 py-2 rounded-full hover:bg-blue-500 hover:text-white transition">
                        Semua
                    </a>
                @endif
                @foreach ($categories as $item)
                    <a href="{{ route('products.category', $item->id) }}" class="{{ $item->id == $id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} px-4 py-2 rounded-full hover:bg-blue-500 hover:text-white transition">
                        {{ $item->nama }}
                    </a>
                @endforeach
            </div>

            {{-- Produk Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse ($products as $product)
                    <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden" data-aos="fade-up" data-aos-duration="500">
                        <a href="{{ route('products.detail', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->produkPhotos->first()?->url) }}" alt="{{ $product->nama }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $product->nama }}</h3>
                            @php
                                $averageRating = $product->reviews->avg('rating');
                                $reviewCount = $product->reviews->count();
                            @endphp
                            {{-- <div class="flex items-center mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-500">
                                    {{ $reviewCount > 0 ? number_format($averageRating, 1) . ' / 5 (' . $reviewCount . ' review' . ($reviewCount > 1 ? 's' : '') . ')' : 'Belum ada review' }}
                                </span>
                            </div> --}}
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($product->deskripsi, 50) }}</p>
                            <p class="font-bold text-blue-700 mb-4">IDR {{ number_format($product->harga, 0, ',', '.') }} <span class="text-sm text-green-600">({{ $product->stok }} stok)</span></p>
                            <a href="{{ route('products.detail', $product->id) }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Detail Produk
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center text-gray-500">
                        Tidak ada produk ditemukan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
