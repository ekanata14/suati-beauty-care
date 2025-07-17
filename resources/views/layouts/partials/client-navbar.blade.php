<nav x-data="{ open: false }"
    class="fixed top-0 left-0 right-0 w-full z-50 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 h-[100px] flex items-center justify-between shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between w-full mx-auto">
        <div class="h-16 flex items-center justify-center">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset($logo->logo) }}" alt="logo" class="h-12">
                    </a>
                </div>
            </div>
        </div>
        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <ul class="flex justcify-center items-center gap-12">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                </li>
                <li><a href="{{ route('products') }}"
                        class="{{ request()->routeIs('products*') ? 'active' : '' }}">Products</a></li>
                @if (Auth::user() && Auth::user()->role != 'admin')
                    <li><a href="{{ route('cart') }}"
                            class="{{ request()->routeIs('cart*') ? 'active' : '' }}">Carts</a></li>
                    <li><a href="{{ route('history') }}"
                            class="{{ request()->routeIs('history*') ? 'active' : '' }}">History</a></li>
                    <li><a href="{{ route('wishlists') }}"
                            class="{{ request()->routeIs('wishlists*') ? 'active' : '' }}">Wishlist</a></li>
                @endif
            </ul>
        </div>
        <div class="flex items-center">

            @if (Auth::user())
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn-alternative">Register</a>
                </div>
            @endif
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar -->
            <div :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
                class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 transform -translate-x-full transition-transform duration-300 ease-in-out z-50">
                <div class="flex flex-col h-full">
                    <div
                        class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('home') }}"
                            class="text-lg font-semibold text-gray-800 dark:text-gray-200">Menu</a>
                        <button @click="open = false"
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex-1 px-4 py-6 space-y-4">
                        <ul class="space-y-4">
                            <li><a href="{{ route('home') }}"
                                    class="block text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('Home') }}</a>
                            </li>
                            <li><a href="{{ route('about') }}"
                                    class="block text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('About') }}</a>
                            </li>
                            <li><a href="{{ route('products') }}"
                                    class="block text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('Products') }}</a>
                            </li>
                            @if (Auth::user())
                                <li><a href="{{ route('cart') }}"
                                        class="block text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('Carts') }}</a>
                                </li>
                                <li><a href="{{ route('history') }}"
                                        class="block text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('History') }}</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    @if (Auth::user())
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">
                            <div class="text-gray-800 dark:text-gray-200 font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400">{{ __('Log Out') }}</button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">
                            <a href="{{ route('login') }}" class="btn-primary">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="btn-alternative">{{ __('Register') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Bottom Navbar (Mobile Only) -->
        <div
            class="fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:hidden">
            <ul class="flex justify-around items-center h-16">
                <li>
                    <a href="{{ route('home') }}"
                        class="flex flex-col items-center {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m0 0h4m-4 0a2 2 0 01-2-2v-4a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="text-xs">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}"
                        class="flex flex-col items-center {{ request()->routeIs('about') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="text-xs">About</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products') }}"
                        class="flex flex-col items-center {{ request()->routeIs('products*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M3 3h18v2H3V3zm0 4h18v13a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm5 6h2v5H8v-5zm4 0h2v5h-2v-5z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="text-xs">Products</span>
                    </a>
                </li>
                @if (Auth::user())
                    <li>
                        <button id="quickAccessBtn"
                            class="flex flex-col items-center focus:outline-none {{ request()->routeIs('cart*') || request()->routeIs('history*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L19 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="text-xs">Carts & History</span>
                        </button>
                        <!-- Modal -->
                        <div id="quickAccessModal" style="display: none;"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-80 p-6 relative">
                                <button id="closeQuickAccessModal"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Quick Access
                                </h3>
                                <ul class="space-y-4">
                                    <li>
                                        <a href="{{ route('cart') }}"
                                            class="flex items-center gap-2 {{ request()->routeIs('cart*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L19 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Carts</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('history') }}"
                                            class="flex items-center gap-2 {{ request()->routeIs('history*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>History</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const btn = document.getElementById('quickAccessBtn');
                                const modal = document.getElementById('quickAccessModal');
                                const closeBtn = document.getElementById('closeQuickAccessModal');

                                btn.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    modal.style.display = 'flex';
                                });

                                closeBtn.addEventListener('click', function() {
                                    modal.style.display = 'none';
                                });

                                window.addEventListener('click', function(e) {
                                    if (e.target === modal) {
                                        modal.style.display = 'none';
                                    }
                                });
                            });
                        </script>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="flex flex-col items-center {{ request()->routeIs('profile.edit') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M5.121 17.804A9 9 0 1112 21a8.963 8.963 0 01-6.879-3.196z"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="text-xs">Profile</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        {{-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div> --}}

        @if (Auth::user())
            {{-- <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div> --}}
        @else
            {{-- <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                </div>
            </div> --}}
        @endif
    </div>
</nav>
