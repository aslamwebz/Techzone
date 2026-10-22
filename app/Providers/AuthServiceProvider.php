<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \App\Models\ActivityLog::class => \App\Policies\ActivityLogPolicy::class,
        User::class => \App\Policies\AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define security-related gates
        $this->defineSecurityGates();
    }

    /**
     * Define security-related gates.
     *
     * @return void
     */
    protected function defineSecurityGates()
    {
        // Super Admin can do anything
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // Define admin gate
        Gate::define('admin', function (User $user) {
            return $user->isAdmin()
                ? Response::allow()
                : Response::deny('You must be an administrator to perform this action.');
        });

        // Define view dashboard gate
        Gate::define('view-dashboard', function (User $user) {
            return $user->can('access admin dashboard');
        });

        // Define manage users gate
        Gate::define('manage-users', function (User $user) {
            return $user->can('manage users');
        });

        // Define manage roles gate
        Gate::define('manage-roles', function (User $user) {
            return $user->can('manage roles');
        });

        // Define view audit logs gate
        Gate::define('view-audit-logs', function (User $user) {
            return $user->can('view audit logs');
        });
    }
}
