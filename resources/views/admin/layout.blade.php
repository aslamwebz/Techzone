<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles -->
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            margin-left: -16rem;
        }
        .content {
            transition: all 0.3s;
            width: 100%;
        }
        .content.expanded {
            width: 100%;
            margin-left: 0;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -16rem;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .content {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: window.innerWidth > 768, isMobile: window.innerWidth <= 768 }">
        <!-- Mobile sidebar -->
        <div x-show="isMobile && sidebarOpen" 
             class="fixed inset-0 z-40 bg-black bg-opacity-50"
             @click="sidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 text-white transition-all duration-300 ease-in-out"
             :class="{ 'ml-0': sidebarOpen, '-ml-64': !sidebarOpen }"
             x-show="!isMobile || (isMobile && sidebarOpen)"
             @click.away="if(isMobile) sidebarOpen = false">
            <div class="flex items-center justify-between h-16 px-4 bg-gray-900">
                <span class="text-xl font-semibold">{{ config('app.name') }}</span>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-4">
                <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-icons.dashboard class="w-5 h-5" />
                    <span>Dashboard</span>
                </x-admin.nav-link>

                <div class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Management
                </div>
                
                <x-admin.nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products*')">
                    <x-icons.package class="w-5 h-5" />
                    <span>Products</span>
                </x-admin.nav-link>
                
                <x-admin.nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories*')">
                    <x-icons.category class="w-5 h-5" />
                    <span>Categories</span>
                </x-admin.nav-link>
                
                <x-admin.nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders*')">
                    <x-icons.shopping-cart class="w-5 h-5" />
                    <span>Orders</span>
                    @if(class_exists(\App\Models\Order::class))
                    <span class="px-2 py-0.5 ml-auto text-xs font-medium bg-red-500 rounded-full">
                        {{ \App\Models\Order::where('status', 'pending')->count() }}
                    </span>
                    @endif
                </x-admin.nav-link>
                
                <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users*')">
                    <x-icons.users class="w-5 h-5" />
                    <span>Users</span>
                </x-admin.nav-link>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex flex-col h-screen" :class="{ 'md:ml-64': sidebarOpen }">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 rounded-md hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                        <h1 class="ml-4 text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-2 text-gray-500 rounded-full hover:bg-gray-100">
                                <x-icons.bell class="w-5 h-5" />
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 w-64 mt-2 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                <div class="p-2 border-b">
                                    <p class="text-sm font-medium text-gray-700">Notifications</p>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New order received</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New user registered</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2">
                                <div class="w-8 h-8 overflow-hidden bg-gray-300 rounded-full">
                                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="object-cover w-full h-full">
                                </div>
                                <span class="hidden md:inline-block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 w-48 mt-2 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ __('Log Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 overflow-y-auto">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-icons.check-circle class="w-5 h-5 text-green-500" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-icons.x-circle class="w-5 h-5 text-red-500" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @livewireScripts
    <script>
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('[aria-label="Toggle sidebar"]');
            
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && event.target !== sidebarToggle) {
                sidebar.classList.add('hidden');
            }
        });

        // Update sidebar state on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar').classList.remove('hidden');
            } else {
                document.querySelector('.sidebar').classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
