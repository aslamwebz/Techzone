<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        $user = Auth::user();

        // Super admin has all permissions
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Check if user has any of the required permissions (if specified)
        $permission = $request->route()->getAction('permission');
        if ($permission && $user->can($permission)) {
            return $next($request);
        }

        // User doesn't have required role or permission
        return $this->handleUnauthorized($request);
    }

    /**
     * Handle unauthenticated users.
     *
     * @param  Request  $request
     * @return Response|JsonResponse
     */
    protected function handleUnauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * Handle unauthorized access.
     *
     * @param  Request  $request
     * @return Response|JsonResponse
     */
    protected function handleUnauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        return redirect()
            ->route('home')
            ->with('error', 'You do not have permission to access this page.');
    }
}
