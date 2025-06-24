<!-- Fancy Navbar with Modern Style -->
<nav x-data="{ open: false }"
    class="sticky top-0 left-0 right-0 w-full z-50 bg-white dark:bg-gray-800 h-[150px] flex transition-all duration-300 ease-in-out border-b-2 border-b-transparent"
    style="box-shadow: none; border-bottom: 2px solid transparent; transition: border-color 0.3s cubic-bezier(0.4,0,0.2,1);">
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center w-full mx-auto gap-6">
        <!-- Logo -->
        <div id="navbar-logo" class="h-16 flex items-center justify-center transition-all duration-300 ease-in-out">
            <a href="{{ route('home') }}">
                <img src="{{ asset($logo->logo) }}" alt="logo" class="w-32">
            </a>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden sm:flex w-full justify-center">
            <ul id="navbar-list" class="flex justify-center items-center gap-12 py-2 transition-all duration-300">
                <li><a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about') }}"
                        class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ route('products') }}"
                        class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}">Products</a></li>
                @guest
                    <li><a href="{{ route('login') }}"
                            class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                    <li><a href="{{ route('register') }}"
                            class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                @endguest
                @auth
                    <li><a href="{{ route('cart') }}"
                            class="nav-link {{ request()->routeIs('cart*') ? 'active' : '' }}">Carts</a></li>
                    <li><a href="{{ route('history') }}"
                            class="nav-link {{ request()->routeIs('history*') ? 'active' : '' }}">History</a></li>
                    <li><a href="{{ route('wishlists') }}"
                            class="nav-link {{ request()->routeIs('wishlists*') ? 'active' : '' }}">Wishlist</a></li>
                @endauth
            </ul>
        </div>
    </div>

    <!-- Scroll Effect Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.getElementById('navbar-logo');
            const nav = document.querySelector('nav');
            window.addEventListener('scroll', function() {
                // Only apply effect if not on mobile (sm breakpoint: 640px)
                if (window.innerWidth >= 640) {
                    if (window.scrollY > 10) {
                        logo.style.opacity = '0';
                        logo.style.pointerEvents = 'none';
                        logo.style.height = '0px';
                        nav.classList.add('backdrop-blur-md', 'shadow-lg');
                    } else {
                        logo.style.opacity = '1';
                        logo.style.pointerEvents = '';
                        logo.style.height = '';
                        nav.classList.remove('backdrop-blur-md', 'shadow-lg');
                    }
                } else {
                    // Always show logo on mobile
                    logo.style.opacity = '1';
                    logo.style.pointerEvents = '';
                    logo.style.height = '';
                    nav.classList.remove('backdrop-blur-md', 'shadow-lg');
                }
            });

            // Also handle window resize to reset logo if switching between mobile/desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth < 640) {
                    logo.style.opacity = '1';
                    logo.style.pointerEvents = '';
                    logo.style.height = '';
                    nav.classList.remove('backdrop-blur-md', 'shadow-lg');
                }
            });
        });
    </script>

    <!-- Fancy Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        #navbar-list a {
            position: relative;
            transition: all 0.3s ease-in-out;
        }

        #navbar-list a:hover {
            color: #2563eb;
            transform: scale(1.05);
        }

        #navbar-list a::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, #3b82f6, #6366f1);
            opacity: 0;
            transform: scaleX(0);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #navbar-list a.active::after,
        #navbar-list a:hover::after {
            opacity: 1;
            transform: scaleX(1);
        }

        @media (max-width: 640px) {
            #navbar-list {
                flex-direction: column;
                gap: 8px;
                padding: 1rem 0;
            }
        }

        @media (max-width: 1024px) {
            #navbar-list {
                gap: 2rem;
            }
        }
    </style>
</nav>
<!-- Mobile Bottom Navbar -->
<div class="md:hidden w-full">
    <nav
        class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg pb-4">
        <ul class="flex justify-around items-center h-16">
            <li>
            <a href="{{ route('home') }}"
                class="flex flex-col items-center {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('home') ? 'text-blue-600' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2z" />
                </svg>
                <span class="text-xs {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">Home</span>
            </a>
            </li>
            <li>
            <a href="{{ route('products') }}"
                class="flex flex-col items-center {{ request()->routeIs('products*') ? 'text-blue-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('products*') ? 'text-blue-600' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" />
                </svg>
                <span class="text-xs {{ request()->routeIs('products*') ? 'text-blue-600 font-semibold' : '' }}">Products</span>
            </a>
            </li>
            @auth
            <li>
            <a href="#" onclick="document.getElementById('transactionModal').classList.remove('hidden'); return false;"
                class="flex flex-col items-center {{ (request()->routeIs('cart*') || request()->routeIs('history*')) ? 'text-blue-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ (request()->routeIs('cart*') || request()->routeIs('history*')) ? 'text-blue-600' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L21 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7" />
                </svg>
                <span class="text-xs {{ (request()->routeIs('cart*') || request()->routeIs('history*')) ? 'text-blue-600 font-semibold' : '' }}">Transaction</span>
            </a>
            </li>
            <li>
            <a href="#" onclick="document.getElementById('moreModal').classList.remove('hidden'); return false;"
                class="flex flex-col items-center {{ (request()->routeIs('wishlists*') || request()->routeIs('profile*')) ? 'text-blue-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ (request()->routeIs('wishlists*') || request()->routeIs('profile*')) ? 'text-blue-600' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 6a4 4 0 018 0c0 2.21-1.79 4-4 4S4 8.21 4 6zm0 0v12a4 4 0 004 4h8a4 4 0 004-4V6" />
                </svg>
                <span class="text-xs {{ (request()->routeIs('wishlists*') || request()->routeIs('profile*')) ? 'text-blue-600 font-semibold' : '' }}">More</span>
            </a>
            </li>
            @endauth
            @guest
            <li>
            <a href="#" onclick="document.getElementById('guestModal').classList.remove('hidden'); return false;"
                class="flex flex-col items-center {{ (request()->routeIs('login') || request()->routeIs('register')) ? 'text-blue-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ (request()->routeIs('login') || request()->routeIs('register')) ? 'text-blue-600' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 12H3m0 0l4-4m-4 4l4 4m13-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs {{ (request()->routeIs('login') || request()->routeIs('register')) ? 'text-blue-600 font-semibold' : '' }}">Account</span>
            </a>
            </li>
            @endguest
        </ul>

        <!-- Guest Modal -->
        <div id="guestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-80 p-6 relative">
            <button onclick="document.getElementById('guestModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                &times;
            </button>
            <h3 class="text-lg font-semibold mb-4 text-center">Account Options</h3>
            <div class="flex flex-col gap-4">
                <a href="{{ route('login') }}" class="w-full py-2 px-4 rounded bg-blue-600 text-white text-center hover:bg-blue-700 transition">Login</a>
                <a href="{{ route('register') }}" class="w-full py-2 px-4 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">Register</a>
            </div>
            </div>
        </div>

        <!-- Transaction Modal (Cart & History) -->
        <div id="transactionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-80 p-6 relative">
            <button onclick="document.getElementById('transactionModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                &times;
            </button>
            <h3 class="text-lg font-semibold mb-4 text-center">Transaction</h3>
            <div class="flex flex-col gap-4">
                <a href="{{ route('cart') }}" class="w-full py-2 px-4 rounded bg-blue-600 text-white text-center hover:bg-blue-700 transition">Cart</a>
                <a href="{{ route('history') }}" class="w-full py-2 px-4 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">History</a>
            </div>
            </div>
        </div>

        <!-- More Modal (Wishlist & Profile) -->
        <div id="moreModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-80 p-6 relative">
            <button onclick="document.getElementById('moreModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                &times;
            </button>
            <h3 class="text-lg font-semibold mb-4 text-center">More Options</h3>
            <div class="flex flex-col gap-4">
                <a href="{{ route('wishlists') }}" class="w-full py-2 px-4 rounded bg-blue-600 text-white text-center hover:bg-blue-700 transition">Wishlist</a>
                {{-- <a href="{{ route('profile') }}" class="w-full py-2 px-4 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">Profile</a> --}}
            </div>
            </div>
        </div>
    </nav>
</div>
