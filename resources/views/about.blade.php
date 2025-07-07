@extends('layouts.client')

@section('content')
    @foreach ($homeContents as $content)
        <section
            class="h-screen flex flex-col md:flex-row items-center justify-center bg-gradient-to-br from-white via-blue-100 to-blue-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 py-16 px-4">
            <div class="w-full md:w-1/2 flex justify-center" data-aos="fade-right">
                <img src="{{ asset($content['logo']) }}" alt="logo"
                    class="w-3/4 max-w-xs md:max-w-md rounded-xl shadow-xl">
            </div>
            <div class="max-w-xl w-full md:w-1/2 mt-10 md:mt-0 px-4" data-aos="fade-left">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6 leading-snug">{{ $content['title'] }}
                </h1>
                @foreach (explode("\n", $content['description']) as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mb-4">{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>
        </section>
    @endforeach

    {{-- LOCATION SECTION --}}
    <section id="location" class="py-20 bg-blue-50 dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-6 md:px-12">
            <h2 class="text-4xl font-bold text-center text-gray-800 dark:text-white mb-4">Lokasi Kami</h2>
            <p class="text-center text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                Kunjungi langsung toko kami di lokasi berikut. Temukan berbagai produk berkualitas dengan pelayanan terbaik.
            </p>

            <div class="w-full h-[450px] overflow-hidden rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700"
                data-aos="zoom-in">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3945.710228784297!2d115.44496!3d-8.527488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zOMKwMzEnMzkuMCJTIDExNcKwMjYnNDEuOSJF!5e0!3m2!1sen!2sid!4v1750738109074!5m2!1sen!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" class="w-full h-full"></iframe>
            </div>
        </div>
    </section>
@endsection
