<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log activity for authenticated users and if we should log this request
        if (Auth::check() && $this->shouldLog($request)) {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Determine if the request should be logged.
     */
    protected function shouldLog(Request $request): bool
    {
        // Don't log AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Don't log these methods
        if (! in_array($request->method(), ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        // Don't log these routes
        $except = [
            'admin/logs*',
            'horizon*',
            'telescope*',
            'vendor/*',
            'telescope*',
            'api*',
            'broadcasting*',
            'livewire*',
            '_debugbar*',
            'storage*',
            'assets*',
        ];

        foreach ($except as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Log the user activity.
     */
    protected function logActivity(Request $request): void
    {
        $user = Auth::user();
        $method = $request->method();
        $path = $request->path();
        $fullUrl = $request->fullUrl();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $route = $request->route() ? $request->route()->getName() : null;

        // Skip logging for these methods by default
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

        // Create a description based on the request method and path
        $description = $this->getActivityDescription($method, $path, $route);

        // Log the activity using our ActivityLogger service
        activity($description)
            ->by($user)
            ->withProperties([
                'method' => $method,
                'path' => $path,
                'url' => $fullUrl,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'route' => $route,
                'input' => $this->getFilteredInput($request),
            ])
            ->event(strtoupper($method))
            ->useLog('http')
            ->log();
    }

    /**
     * Generate a human-readable description for the activity.
     */
    protected function getActivityDescription(string $method, string $path, ?string $route = null): string
    {
        $action = match ($method) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'accessed',
        };

        // If we have a named route, use that for better readability
        if ($route) {
            return "{$action} ".str_replace('.', ' ', $route);
        }

        // Otherwise, use the path
        $resource = trim(str_replace('/', ' ', $path));

        return "{$action} {$resource}";
    }

    /**
     * Get filtered input data (without sensitive information).
     */
    protected function getFilteredInput(Request $request): array
    {
        $input = $request->all();

        // Filter out sensitive data
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',
            'credit_card',
            'cvv',
            'ssn',
            'token',
            'api_token',
            'secret',
            'authorization',
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($input[$key])) {
                $input[$key] = '***FILTERED***';
            }
        }

        // Filter out file uploads (just show file names)
        if ($request->hasFile('*')) {
            $files = [];
            foreach ($request->allFiles() as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $f) {
                        $files[$key][] = $f->getClientOriginalName();
                    }
                } else {
                    $files[$key] = $file->getClientOriginalName();
                }
            }
            $input = array_merge($input, $files);
        }

        return $input;
    }
}
