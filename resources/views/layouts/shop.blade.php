<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4f46e5">

    <title>{{ config('app.name', 'TechZone') }} - @yield('title')</title>
    <meta name="description" content="Shop the latest tech gadgets and electronics at TechZone. Free shipping on orders over ₦50,000. Best prices on smartphones, laptops, and more.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('styles')
    
    <!-- Smooth Scroll Behavior -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        /* Better image rendering */
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
        }
        
        /* Product image container */
        .product-image-container {
            position: relative;
            padding-top: 100%;
            background-color: #f9fafb;
            overflow: hidden;
        }
        
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
            padding: 1rem;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
    </style>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "TechZone",
        "url": "{{ config('app.url') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('shop') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm">
        <div class="container mx-auto px-4 py-2 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-4 mb-2 md:mb-0">
                <a href="tel:+12345678900" class="flex items-center hover:text-indigo-200 transition-colors">
                    <i class="fas fa-phone-alt mr-2 text-xs"></i>
                    <span>+1 234 567 8900</span>
                </a>
                <span class="hidden md:inline-block h-4 w-px bg-white/30"></span>
                <a href="mailto:support@techzone.com" class="flex items-center hover:text-indigo-200 transition-colors">
                    <i class="fas fa-envelope mr-2 text-xs"></i>
                    <span>support@techzone.com</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="hidden md:inline-block text-xs">Follow us:</span>
                <div class="flex space-x-3">
                    <a href="#" aria-label="Facebook" class="hover:text-indigo-200 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Twitter" class="hover:text-indigo-200 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="hover:text-indigo-200 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="hover:text-indigo-200 transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50 transform transition-transform duration-300" id="main-header">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen"></i>
                    </button>
                </div>

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        TechZone
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex md:items-center md:space-x-1 lg:space-x-4 flex-1 justify-center">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }} transition-colors duration-200">
                        Home
                    </a>
                    <a href="{{ route('shop') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('shop*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }} transition-colors duration-200">
                        Shop
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                        Categories
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                        New Arrivals
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                        Deals
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                        About Us
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                        Contact
                    </a>
                </nav>

                <!-- Right side icons -->
                <div class="flex items-center space-x-4">
                    <!-- Search Button (Mobile) -->
                    <button @click="searchOpen = !searchOpen" class="md:hidden text-gray-500 hover:text-indigo-600 focus:outline-none">
                        <span class="sr-only">Search</span>
                        <i class="fas fa-search text-xl"></i>
                    </button>

                    <!-- Search (Desktop) -->
                    <div class="hidden md:block relative w-64">
                        <form action="{{ route('shop') }}" method="GET" class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search products..." 
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none text-sm transition-all duration-200"
                                value="{{ request('search') }}"
                            >
                            <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-indigo-600 focus:outline-none">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Cart Counter -->
                    <livewire:cart-counter />

                    <!-- User Account -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                                <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu">
                                <div class="py-1" role="none">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            {{ __('Log Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-indigo-600 text-sm font-medium">
                            Sign In
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <form action="{{ route('shop') }}" method="GET" class="px-2 py-3">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search products..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600">
                    Home
                </a>
                <a href="{{ route('shop') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600">
                    Shop
                </a>
                <a href="{{ route('shop') }}#categories" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600">
                    Categories
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600">
                    About Us
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600">
                    Contact
                </a>
                @guest
                    <div class="border-t border-gray-200 pt-4">
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            Sign in
                        </a>
                        <div class="mt-3">
                            <p class="text-center text-sm text-gray-600">
                                New customer?
                                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Start here.
                                </a>
                            </p>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </header>
    
    <!-- Main Navigation will be handled by the page content -->

    <!-- Page Content -->
    <main wire:navigate>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-xl font-bold mb-4">About TechZone</h3>
                    <p class="text-gray-400">Your one-stop shop for the latest tech gadgets and electronics. We offer high-quality products at competitive prices.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('shop') }}" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="#categories" class="text-gray-400 hover:text-white">Categories</a></li>
                        <li><a href="#new-arrivals" class="text-gray-400 hover:text-white">New Arrivals</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white">Contact Us</a></li>
                    </ul>
                </div>
                
                <!-- Customer Service -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Returns & Refunds</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <span>123 Tech Street, Silicon Valley, CA 94025, USA</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2"></i>
                            <span>+1 234 567 8900</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>support@techzone.com</span>
                        </li>
                    </ul>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in text-xl"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} TechZone. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Alpine.js for mobile menu
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                mobileMenuOpen: false,
                cartOpen: false,
                cartItems: [],
                cartTotal: 0,
                
                init() {
                    // Initialize cart from session
                    this.updateCartFromSession();
                    
                    // Listen for cart updates
                    window.addEventListener('cart-updated', () => {
                        this.updateCartFromSession();
                    });
                },
                
                updateCartFromSession() {
                    // This would be replaced with an actual fetch to get cart data
                    // For now, we'll just update the UI based on the cart count
                    const cartCount = {{ Cart::count() }};
                    this.cartItems = Array(cartCount).fill({});
                    this.cartTotal = 0; // This would be calculated from actual cart data
                },
                
                removeFromCart(index) {
                    // This would be an API call to remove the item
                    this.cartItems.splice(index, 1);
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                }
            }));
        });
    </script>
</body>
</html>
