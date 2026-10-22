@extends('shop.layout')

@section('shop-content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">
            About Us
        </h1>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
            Your trusted partner for the latest tech products and accessories.
        </p>
    </div>

    <div class="mt-16">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-16">
            <div class="relative h-64 overflow-hidden rounded-lg sm:h-80 lg:h-full">
                <img
                    src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                    alt="Our team"
                    class="absolute inset-0 h-full w-full object-cover"
                />
            </div>

            <div class="lg:py-16">
                <article class="space-y-4 text-gray-600">
                    <p>
                        Welcome to {{ config('app.name') }}, your number one source for all things tech. We're dedicated to providing you the very best products, with an emphasis on quality, customer service, and uniqueness.
                    </p>

                    <p>
                        Founded in 2023, {{ config('app.name') }} has come a long way from its beginnings. When we first started out, our passion for technology drove us to start our own business.
                    </p>

                    <p>
                        We now serve customers all over the world, and are thrilled that we're able to turn our passion into our own website.
                    </p>

                    <p>
                        We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us.
                    </p>

                    <p class="mt-8">
                        Sincerely,<br>
                        The {{ config('app.name') }} Team
                    </p>
                </article>
            </div>
        </div>
    </div>
</div>

<!-- Our Values -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Our Values
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                What makes us different
            </p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Quality -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Quality Products</dt>
                                    <dd class="mt-1 text-gray-600">We source only the highest quality products from trusted manufacturers.</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Service -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Customer First</dt>
                                    <dd class="mt-1 text-gray-600">Your satisfaction is our top priority. We're here to help with any questions.</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Shipping -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Fast Shipping</dt>
                                    <dd class="mt-1 text-gray-600">We ship worldwide with fast and reliable delivery options.</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
