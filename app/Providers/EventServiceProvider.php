<?php

namespace App\Providers;

use App\Listeners\SecurityEventListener;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\AuthenticationFailed;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\FailedPasswordResetLink;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\SuspiciousLoginAttempt;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SecurityEventListener::class.'@handleRegistered',
        ],
        Login::class => [
            SecurityEventListener::class.'@handleLogin',
        ],
        Failed::class => [
            SecurityEventListener::class.'@handleFailedLogin',
        ],
        Logout::class => [
            SecurityEventListener::class.'@handleLogout',
        ],
        PasswordReset::class => [
            SecurityEventListener::class.'@handlePasswordReset',
        ],
        Verified::class => [
            SecurityEventListener::class.'@handleEmailVerified',
        ],
        PasswordResetLinkSent::class => [
            SecurityEventListener::class.'@handlePasswordResetLinkSent',
        ],
        FailedPasswordResetLink::class => [
            SecurityEventListener::class.'@handleFailedPasswordResetLink',
        ],
        SuspiciousLoginAttempt::class => [
            SecurityEventListener::class.'@handleSuspiciousLoginAttempt',
        ],
        Attempting::class => [
            SecurityEventListener::class.'@handleAttempting',
        ],
        AuthenticationFailed::class => [
            SecurityEventListener::class.'@handleAuthenticationFailed',
        ],
        CurrentDeviceLogout::class => [
            SecurityEventListener::class.'@handleCurrentDeviceLogout',
        ],
        OtherDeviceLogout::class => [
            SecurityEventListener::class.'@handleOtherDeviceLogout',
        ],
        Validated::class => [
            SecurityEventListener::class.'@handleValidated',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
