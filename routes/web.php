<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/search', [ShopController::class, 'search'])->name('search');
Route::get('/shop', \App\Livewire\Shop::class)->name('shop');
Route::get('/shop/category/{category:slug}', \App\Livewire\Shop::class)->name('shop.category');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('product.show');

// Additional pages
Route::get('/categories', [ShopController::class, 'categories'])->name('categories');
Route::get('/new-arrivals', [ShopController::class, 'newArrivals'])->name('new-arrivals');
Route::get('/deals', [ShopController::class, 'deals'])->name('deals');
Route::get('/about', [ShopController::class, 'about'])->name('about');
Route::get('/contact', [ShopController::class, 'contact'])->name('contact');

// Alias for category route to maintain backward compatibility
Route::get('/category/{category:slug}', function ($category) {
    return redirect()->route('shop.category', ['category' => $category]);
})->name('categories.show');

// Test cart route
Route::get('/test-cart', function () {
    // Test if cart is working
    try {
        $cart = app('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart is working!',
            'cart_count' => $cart->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Cart error: '.$e->getMessage(),
        ], 500);
    }
});

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/count', [CartController::class, 'cartCount'])->name('count');
    Route::post('/add/{id}', [CartController::class, 'addToCart'])->name('add');
    Route::post('/update', [CartController::class, 'updateCart'])->name('update');
    Route::delete('/remove/{rowId}', [CartController::class, 'removeFromCart'])->name('remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Roles & Permissions
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->except(['show']);

        // Products Management
        Route::resource('products', ProductController::class);

        // Categories Management
        Route::resource('categories', CategoryController::class);

        // Orders Management
        Route::get('/orders', [\App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\AdminController::class, 'showOrder'])->name('orders.show');
        Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'updateSettings'])->name('settings.update');

        // Activity Logs
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
            Route::get('/dashboard', [\App\Http\Controllers\Admin\ActivityLogController::class, 'dashboard'])->name('dashboard');
            Route::get('/user-activity', [\App\Http\Controllers\Admin\ActivityLogController::class, 'userActivity'])->name('user-activity');
            Route::get('/user-activity/{user}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'userActivity'])->name('user-activity.show');
            Route::get('/{log}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('show');
        });
    });

    // Regular User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Checkout routes
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'password'])->name('password.update');
});

require __DIR__.'/auth.php';
