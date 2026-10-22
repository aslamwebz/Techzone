<nav class="bg-white shadow-sm sticky top-0 z-50">
    <!-- Top Bar -->
    <div class="border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Mobile Menu -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <label for="mobile-menu-toggle" class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-50 cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                    <input type="checkbox" id="mobile-menu-toggle" class="hidden peer" />
                    
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center ml-2 md:ml-0">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                            <span class="ml-2 text-xl font-bold text-gray-900 hidden sm:block">{{ config('app.name') }}</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('shop') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('shop') || request()->routeIs('shop.category') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('Shop') }}
                        </a>
                        <a href="{{ route('categories') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('categories') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('Categories') }}
                        </a>
                        <a href="{{ route('new-arrivals') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('new-arrivals') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('New Arrivals') }}
                        </a>
                        <a href="{{ route('deals') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('deals') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('Deals') }}
                        </a>
                        <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('about') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('About Us') }}
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('contact') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            {{ __('Contact') }}
                        </a>
                    </div>
                </div>

                <!-- Search and User Menu -->
                <div class="flex items-center">
                    <!-- Search Button (Mobile) -->
                    <label for="mobile-search-toggle" class="md:hidden p-2 text-gray-500 hover:text-gray-600 cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </label>
                    <input type="checkbox" id="mobile-search-toggle" class="hidden peer/mobile-search" />
                    
                    <!-- Mobile Search Bar (Hidden by default) -->
                    <div class="hidden peer-checked/mobile-search:block absolute left-0 right-0 top-16 bg-white p-4 shadow-md z-50">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input 
                                type="text" 
                                name="q"
                                placeholder="Search products..." 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </form>
                    </div>

                    <!-- Desktop Search Bar -->
                    <div class="hidden md:flex items-center relative">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input 
                                type="text" 
                                name="q"
                                placeholder="Search products..." 
                                class="w-64 px-4 py-2 border border-gray-300 rounded-l-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </form>
                    </div>

                    <!-- Cart -->
                    <div class="ml-4 relative">
                        <label for="cart-toggle" class="p-1 rounded-full text-gray-400 hover:text-gray-500 relative cursor-pointer">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                use App\Models\Cart;
                                $cartCount = Cart::countItems(auth()->id() ?? session()->getId());
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                            @endif
                            <span class="sr-only">Cart</span>
                        </label>
                        <input type="checkbox" id="cart-toggle" class="hidden peer/cart" />

                        <!-- Cart Dropdown -->
                        <div class="hidden peer-checked/cart:block origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            @livewire('cart.dropdown')
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="ml-4 relative">
                        <label for="user-menu-toggle" class="p-1 rounded-full text-gray-400 hover:text-gray-500 cursor-pointer">
                            <span class="sr-only">User menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </label>
                        <input type="checkbox" id="user-menu-toggle" class="hidden peer/user-menu" />

                        <!-- Dropdown menu -->
                        <div class="hidden peer-checked/user-menu:block origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            @auth
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                    @if(Route::has('orders.index'))
                                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Orders</a>
                                    @endif
                                    @if(Route::has('wishlist.index'))
                                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Wishlist</a>
                                    @endif
                                    @if(auth()->user() && auth()->user()->is_admin)
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Admin Panel</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ __('Sign out') }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="py-1">
                                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in</a>
                                    <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Create account</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Search -->
        <div x-show="searchOpen" class="md:hidden px-4 py-3 bg-gray-50 border-t border-gray-200" style="display: none;">
            <div class="relative">
                <input 
                    type="text" 
                    x-model="query"
                    @input.debounce.300ms="searchProducts"
                    placeholder="Search products..." 
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <!-- Mobile Search Results -->
                <div 
                    x-show="query.length > 2 && searchResults.length > 0" 
                    @click.away="query = ''"
                    class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    style="display: none;"
                >
                    <div class="max-h-96 overflow-y-auto">
                        <template x-for="product in searchResults" :key="product.id">
                            <a :href="'/products/' + product.slug" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <img :src="product.image_url || 'https://via.placeholder.com/50'" :alt="product.name" class="h-10 w-10 rounded-full object-cover">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900" x-text="product.name"></p>
                                    <p class="text-sm text-gray-500" x-text="'$' + (product.price / 100).toFixed(2)"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('home') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('Home') }}
            </a>
            <a href="{{ route('shop') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('shop') || request()->routeIs('shop.category') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('Shop') }}
            </a>
            <a href="{{ route('categories') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('categories') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('Categories') }}
            </a>
            <a href="{{ route('new-arrivals') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('new-arrivals') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('New Arrivals') }}
            </a>
            <a href="{{ route('deals') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('deals') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('Deals') }}
            </a>
            <a href="{{ route('about') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('about') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('About Us') }}
            </a>
            <a href="{{ route('contact') }}" class="block px-4 py-2 text-base font-medium {{ request()->routeIs('contact') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                {{ __('Contact') }}
            </a>
        </div>

        @auth
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Your Profile') }}
                    </a>
                    @if(Route::has('orders.index'))
                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Orders') }}
                    </a>
                    @endif
                    @if(Route::has('wishlist.index'))
                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Wishlist') }}
                    </a>
                    @endif
                    @if(auth()->user() && auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 border-t border-gray-200">
                            {{ __('Admin Panel') }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Sign in') }}
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Create account') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>
    
    <style>
        /* Hide mobile menu by default */
        #mobile-menu {
            display: none;
        }
        /* Show mobile menu when checkbox is checked */
        #mobile-menu-toggle:checked ~ #mobile-menu {
            display: block;
        }
    </style>
</nav>
