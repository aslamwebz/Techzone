<?php

namespace App\Listeners;

use App\Utils\SecurityUtil;
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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SecurityEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 60; // 1 minute delay

    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        $ip = SecurityUtil::getClientIp();
        $userAgent = Request::userAgent();

        Log::channel('security')->info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Update user's last login info if the user model has these fields
        if (isset($user->last_login_at) && isset($user->last_login_ip) && isset($user->last_login_user_agent)) {
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
                'last_login_user_agent' => $userAgent,
            ]);
        }
    }

    /**
     * Handle failed login attempts.
     */
    public function handleFailedLogin(Failed $event): void
    {
        $ip = SecurityUtil::getClientIp();
        $userAgent = Request::userAgent();
        $email = $event->credentials['email'] ?? ($event->user ? $event->user->email : 'unknown');

        Log::channel('security')->warning('Failed login attempt', [
            'email' => $email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;

        if ($user) {
            Log::channel('security')->info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => SecurityUtil::getClientIp(),
                'user_agent' => Request::userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }
    }

    /**
     * Handle password reset events.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        $user = $event->user;

        Log::channel('security')->info('Password reset', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => SecurityUtil::getClientIp(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle email verification events.
     */
    public function handleEmailVerified(Verified $event): void
    {
        $user = $event->user;

        Log::channel('security')->info('Email verified', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => SecurityUtil::getClientIp(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle registered user events.
     */
    public function handleRegistered(Registered $event): void
    {
        $user = $event->user;

        Log::channel('security')->info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle password reset link sent events.
     */
    public function handlePasswordResetLinkSent(PasswordResetLinkSent $event): void
    {
        $email = is_object($event->user ?? null) ? ($event->user->email ?? '') : ($event->email ?? (string) ($event->user ?? ''));

        Log::channel('security')->info('Password reset link sent', [
            'email' => $email,
            'ip' => SecurityUtil::getClientIp(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle password reset link failed events.
     */
    public function handleFailedPasswordResetLink(FailedPasswordResetLink $event): void
    {
        $email = is_object($event->user ?? null) ? ($event->user->email ?? '') : ($event->email ?? (string) ($event->user ?? ''));

        Log::channel('security')->warning('Failed password reset link attempt', [
            'email' => $email,
            'ip' => SecurityUtil::getClientIp(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle suspicious login attempts.
     */
    public function handleSuspiciousLoginAttempt(SuspiciousLoginAttempt $event): void
    {
        $user = $event->user;
        $ip = $event->ip ?? SecurityUtil::getClientIp();
        $userAgent = $event->userAgent ?? Request::userAgent();

        $logData = [
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toDateTimeString(),
        ];

        if ($user) {
            $logData['user_id'] = $user->id;
            $logData['email'] = $user->email;
        }

        Log::channel('security')->warning('Suspicious login attempt', $logData);
    }

    /**
     * Handle login attempt events.
     */
    public function handleAttempting(Attempting $event): void
    {
        Log::channel('security')->debug('Login attempt', [
            'email' => $event->credentials['email'] ?? 'unknown',
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle authentication failed events.
     */
    public function handleAuthenticationFailed(AuthenticationFailed $event): void
    {
        Log::channel('security')->warning('Authentication failed', [
            'guard' => $event->guard,
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle current device logout events.
     */
    public function handleCurrentDeviceLogout(CurrentDeviceLogout $event): void
    {
        Log::channel('security')->info('Current device logout', [
            'user_id' => $event->user->id,
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle other device logout events.
     */
    public function handleOtherDeviceLogout(OtherDeviceLogout $event): void
    {
        Log::channel('security')->info('Other device logout', [
            'user_id' => $event->user->id,
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle validated events.
     */
    public function handleValidated(Validated $event): void
    {
        Log::channel('security')->debug('User validated', [
            'user_id' => $event->user->id,
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events): array
    {
        return [
            Login::class => 'handleLogin',
            Failed::class => 'handleFailedLogin',
            Logout::class => 'handleLogout',
            PasswordReset::class => 'handlePasswordReset',
            Verified::class => 'handleEmailVerified',
            Registered::class => 'handleRegistered',
            PasswordResetLinkSent::class => 'handlePasswordResetLinkSent',
            FailedPasswordResetLink::class => 'handleFailedPasswordResetLink',
            SuspiciousLoginAttempt::class => 'handleSuspiciousLoginAttempt',
            Attempting::class => 'handleAttempting',
            AuthenticationFailed::class => 'handleAuthenticationFailed',
            CurrentDeviceLogout::class => 'handleCurrentDeviceLogout',
            OtherDeviceLogout::class => 'handleOtherDeviceLogout',
            Validated::class => 'handleValidated',
        ];
    }
}
