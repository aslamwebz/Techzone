<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Check if user is admin either by is_admin flag or role
        if ($user->isAdmin() || $user->role === 'admin') {
            return $next($request);
        }

        // Log the unauthorized access attempt
        \Log::warning('Unauthorized admin access attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        abort(403, 'Unauthorized access. You need admin privileges to access this page.');
    }
}
