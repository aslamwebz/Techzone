<?php

namespace App\Providers;

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ThrottleLoginAttempts;
use App\Policies\SecurityPolicy;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerMiddleware();
        $this->configureSecurity();
    }

    /**
     * Register the application's security policies.
     *
     * @return void
     */
    protected function registerPolicies()
    {
        Gate::define('viewSecurityDashboard', [SecurityPolicy::class, 'viewAny']);
        Gate::define('enableTwoFactorAuth', [SecurityPolicy::class, 'enableTwoFactorAuth']);
        Gate::define('manageRoles', [SecurityPolicy::class, 'manageRoles']);
        Gate::define('viewAuditLogs', [SecurityPolicy::class, 'viewAuditLogs']);
        Gate::define('manageIpRestrictions', [SecurityPolicy::class, 'manageIpRestrictions']);
        Gate::define('managePolicies', [SecurityPolicy::class, 'managePolicies']);
        Gate::define('performSecurityScans', [SecurityPolicy::class, 'performSecurityScans']);
        Gate::define('viewSecurityReports', [SecurityPolicy::class, 'viewSecurityReports']);
        Gate::define('manageApiTokens', [SecurityPolicy::class, 'manageApiTokens']);
    }

    /**
     * Register security middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);

        // Register global middleware
        $kernel->pushMiddleware(SecurityHeaders::class);

        // Register route middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('throttle.logins', ThrottleLoginAttempts::class);
        $router->aliasMiddleware('security.headers', SecurityHeaders::class);
    }

    /**
     * Configure security-related settings.
     *
     * @return void
     */
    protected function configureSecurity()
    {
        // Set secure cookie settings
        config([
            'session.secure' => config('app.env') === 'production',
            'session.http_only' => true,
            'session.same_site' => 'lax',
        ]);

        // Set secure cookie settings for the application
        if (config('app.env') === 'production') {
            config([
                'session.secure' => true,
                'session.same_site' => 'lax',
                'sanctum.stateful' => true,
            ]);
        }

        // Set secure password hashing options
        if (config('hashing.driver') === 'bcrypt') {
            config([
                'hashing.bcrypt.rounds' => env('BCRYPT_ROUNDS', 12),
            ]);
        } elseif (config('hashing.driver') === 'argon') {
            config([
                'hashing.argon.memory' => 1024,
                'hashing.argon.threads' => 2,
                'hashing.argon.time' => 2,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
