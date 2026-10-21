<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        Gate::authorize('admin');
        $stats = [
            'users' => [
                'total' => User::count(),
                'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
            ],
            'categories' => [
                'total' => Category::count(),
            ],
            'orders' => [
                'total' => Order::count(),
                'revenue' => Order::sum('total_amount'),
            ],
        ];

        return view('admin.dashboard.index', [
            'stats' => $stats,
            'activities' => [],
        ]);
    }
}
