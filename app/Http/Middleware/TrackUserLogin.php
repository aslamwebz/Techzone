<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track if user is logged in
        if (Auth::check() && $this->shouldTrack($request)) {
            $user = Auth::user();

            // Update last login info
            if (method_exists($user, 'updateLastLogin')) {
                $user->updateLastLogin();
            }
        }

        return $response;
    }

    /**
     * Determine if the request should be tracked.
     *
     * @return bool
     */
    protected function shouldTrack(Request $request)
    {
        // Don't track AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Don't track these routes
        $except = [
            'admin/*',
            'api/*',
            'broadcasting/*',
            'horizon/*',
            'telescope/*',
            'vendor/*',
        ];

        foreach ($except as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }
}
