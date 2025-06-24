@extends('layouts.client')

@section('content')
    {{-- HERO SECTION --}}
    @foreach ($homeContents as $content)
        <section class="h-full md:h-[85vh] bg-gradient-to-br from-white to-blue-100 flex items-center relative overflow-hidden pt-20 md:pt-0"
            data-aos="fade-up">
            <div class="container mx-auto px-6 md:px-12 flex flex-col-reverse md:flex-row items-center justify-between gap-6 md:gap-0 mb-12 md:mb-0">
                <div class="w-full md:w-1/2 z-10" data-aos="fade-right" data-aos-delay="200">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 leading-tight mb-6 animate-fade-in-down">
                        Tampil Keren, Tampil Percaya Diri
                    </h1>
                    <p class="text-gray-600 mb-6 text-lg animate-fade-in-down delay-200">
                        Merawat kulit dan kecantikan Anda dengan produk berkualitas tinggi. Temukan kenyamanan dan
                        kepercayaan diri bersama kami.
                    </p>
                    <a href="#products"
                        class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 animate-fade-in-up"
                        data-aos="fade-up" data-aos-delay="400">
                        Lihat Produk
                    </a>
                </div>
                <div class="w-full md:w-1/2" data-aos="fade-left" data-aos-delay="300">
                    <img src="{{ asset('assets/images/model.jpeg') }}" alt="model"
                        class="w-full max-w-md mx-auto animate-fade-in-up rounded-[2rem] shadow-xl clip-custom">
                </div>
            </div>

            {{-- Optional Shape SVG Decoration --}}
            <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1"
                    d="M0,160L48,176C96,192,192,224,288,224C384,224,480,192,576,186.7C672,181,768,203,864,208C960,213,1056,203,1152,208C1248,213,1344,235,1392,245.3L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </section>
    @endforeach

    {{-- PRODUCT SECTION --}}
    <section id="products" class="bg-white py-20" data-aos="fade-up">
        <div class="container mx-auto px-6 md:px-12">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12" data-aos="fade-down" data-aos-delay="100">Produk</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse ($products as $index => $product)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300"
                        data-aos="zoom-in" data-aos-delay="{{ 100 + $index * 100 }}">
                        <a href="{{ route('products.detail', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama }}"
                                class="w-full h-52 object-cover hover:scale-105 transition duration-300">
                        </a>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->nama }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->deskripsi, 50) }}</p>
                            <p class="font-bold text-gray-900 mb-4">
                                IDR {{ number_format($product->harga, 0, ',', '.') }} <br>
                                <span class="text-green-600 text-sm">{{ $product->stok }} in stock</span>
                            </p>
                            <a href="{{ route('products.detail', $product->id) }}"
                                class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Detail Produk
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center text-gray-500" data-aos="fade-in" data-aos-delay="200">
                        Tidak ada produk tersedia saat ini.
                    </div>
                @endforelse
            </div>

            {{-- BUTTON LIHAT LEBIH BANYAK --}}
            <div class="flex justify-center mt-12" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('products') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>
    {{-- LOCATION SECTION --}}
    <section id="location" class="py-20 bg-blue-50 dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-6 md:px-12">
            <h2 class="text-4xl font-bold text-center text-gray-800 dark:text-white mb-4" data-aos="fade-down"
                data-aos-delay="100">Lokasi Kami</h2>
            <p class="text-center text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto" data-aos="fade-up"
                data-aos-delay="200">
                Kunjungi langsung toko kami di lokasi berikut. Temukan berbagai produk berkualitas dengan pelayanan terbaik.
            </p>

            <div class="w-full h-[450px] overflow-hidden rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700"
                data-aos="zoom-in-up" data-aos-delay="300">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3945.710228784297!2d115.44496!3d-8.527488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zOMKwMzEnMzkuMCJTIDExNcKwMjYnNDEuOSJF!5e0!3m2!1sen!2sid!4v1750738109074!5m2!1sen!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" class="w-full h-full"></iframe>
            </div>
        </div>
    </section>
@endsection
