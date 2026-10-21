<?php

namespace App\Http\Middleware;

use App\Utils\SecurityUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip logging for certain paths
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        // Log the request
        $this->logRequest($request);

        // Process the request
        $response = $next($request);

        // Log the response
        $this->logResponse($request, $response);

        return $response;
    }

    /**
     * Check if the request should be skipped from logging.
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        $skipPaths = [
            'horizon',
            'telescope',
            'livewire',
            '_debugbar',
            'api/documentation',
            'health',
            'ping',
        ];

        foreach ($skipPaths as $path) {
            if (str_starts_with($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the request details.
     */
    protected function logRequest(Request $request): void
    {
        $method = strtoupper($request->method());
        $uri = $request->path();
        $ip = SecurityUtil::getClientIp();
        $userAgent = $request->userAgent();
        $userId = optional($request->user())->id;
        $requestId = $request->header('X-Request-ID');

        $context = [
            'method' => $method,
            'uri' => $uri,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'user_id' => $userId,
            'request_id' => $requestId,
            'headers' => $this->filterHeaders($request->headers->all()),
            'query' => $request->query->all(),
            'request' => $this->filterRequestData($request->all()),
        ];

        // Log sensitive requests at a higher level
        if ($this->isSensitiveRequest($request)) {
            Log::channel('security')->info("Sensitive request: {$method} {$uri}", $context);
        } else {
            Log::channel('security')->debug("Request: {$method} {$uri}", $context);
        }
    }

    /**
     * Log the response details.
     *
     * @param  Response  $response
     */
    protected function logResponse(Request $request, $response): void
    {
        $method = strtoupper($request->method());
        $uri = $request->path();
        $status = $response->getStatusCode();
        $userId = optional($request->user())->id;
        $requestId = $request->header('X-Request-ID');

        $context = [
            'method' => $method,
            'uri' => $uri,
            'status' => $status,
            'user_id' => $userId,
            'request_id' => $requestId,
            'response_size' => $this->getResponseSize($response),
        ];

        // Log error responses at a higher level
        if ($status >= 500) {
            Log::channel('security')->error("Server error: {$status} {$method} {$uri}", $context);
        } elseif ($status >= 400) {
            Log::channel('security')->warning("Client error: {$status} {$method} {$uri}", $context);
        } elseif ($this->isSensitiveRequest($request)) {
            Log::channel('security')->info("Sensitive response: {$status} {$method} {$uri}", $context);
        } else {
            Log::channel('security')->debug("Response: {$status} {$method} {$uri}", $context);
        }
    }

    /**
     * Check if the request contains sensitive data.
     */
    protected function isSensitiveRequest(Request $request): bool
    {
        $sensitivePaths = [
            'login',
            'register',
            'password',
            'auth',
            'oauth',
            'token',
            'api/token',
            'api/auth',
        ];

        foreach ($sensitivePaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        $sensitiveMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (in_array($request->method(), $sensitiveMethods)) {
            return true;
        }

        return false;
    }

    /**
     * Filter sensitive headers before logging.
     */
    protected function filterHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'php-auth-pw',
            'php-auth-user',
            'php-auth-pw',
            'php-auth-digest',
            'x-csrf-token',
            'x-xsrf-token',
        ];

        $filtered = [];
        foreach ($headers as $key => $value) {
            $key = strtolower($key);
            if (in_array($key, $sensitiveHeaders)) {
                $filtered[$key] = '***';
            } else {
                $filtered[$key] = is_array($value) ? $value[0] : $value;
            }
        }

        return $filtered;
    }

    /**
     * Filter sensitive request data before logging.
     */
    protected function filterRequestData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_token',
            'access_token',
            'refresh_token',
            'card_number',
            'cvv',
            'expiry_date',
            'credit_card',
            'credit_card_number',
            'credit_card_cvv',
            'credit_card_expiry',
            'ssn',
            'social_security_number',
        ];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->filterRequestData($value);
            } elseif (in_array(strtolower($key), $sensitiveFields)) {
                $data[$key] = '***';
            }
        }

        return $data;
    }

    /**
     * Get the response size in bytes.
     */
    protected function getResponseSize(Response $response): int
    {
        $content = $response->getContent();

        return $content ? strlen($content) : 0;
    }
}
