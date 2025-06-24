<footer class="bg-gray-900 text-gray-300 py-12 mb-20 md:mb-0">
    <div class="container mx-auto px-6 md:px-12 grid grid-cols-1 md:grid-cols-4 gap-10">
        {{-- Logo & Deskripsi --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-4">Suati Beauty Care</h2>
            <p class="text-sm text-gray-400">
                Merawat kulit dan kecantikan Anda dengan produk berkualitas tinggi. Temukan kenyamanan dan kepercayaan diri bersama kami.
            </p>
        </div>

        {{-- Navigasi --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-3">Navigasi</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white">Tentang Kami</a></li>
                <li><a href="{{ route('products') }}" class="hover:text-white">Produk</a></li>
            </ul>
        </div>

        {{-- Kontak --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-3">Kontak Kami</h3>
            <ul class="text-sm space-y-2">
                <li>📍 Dawan Kaler, Klungkung Regency, Bali</li>
                <li>📞 +62 812-3456-7890</li>
                <li>✉️ info@suatibeauty.com</li>
            </ul>
        </div>

    </div>

    {{-- Bottom Footer --}}
    <div class="mt-12 border-t border-gray-700 pt-6 text-center text-sm text-gray-500">
        © 2025 Suati Beauty Care. All rights reserved.
    </div>
</footer>
