<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $response instanceof Response) {
            return $response;
        }

        // Get security headers configuration
        $headers = Config::get('security.headers', []);

        // Set standard security headers
        $this->setStandardHeaders($response, $headers);

        // Set Content Security Policy header if enabled
        if (Config::get('security.headers.content_security_policy.enabled', true)) {
            $this->setContentSecurityPolicyHeader($response, $headers['content_security_policy'] ?? []);
        }

        // Set Strict Transport Security header if HTTPS
        if ($request->secure()) {
            $this->setStrictTransportSecurityHeader($response, $headers['strict_transport_security'] ?? null);
        }

        return $response;
    }

    /**
     * Set standard security headers.
     */
    protected function setStandardHeaders(Response $response, array $headers): void
    {
        $standardHeaders = [
            'X-Frame-Options' => $headers['x_frame_options'] ?? 'SAMEORIGIN',
            'X-Content-Type-Options' => $headers['x_content_type_options'] ?? 'nosniff',
            'X-XSS-Protection' => $headers['x_xss_protection'] ?? '1; mode=block',
            'Referrer-Policy' => $headers['referrer_policy'] ?? 'strict-origin-when-cross-origin',
            'Permissions-Policy' => $headers['permissions_policy'] ?? 'geolocation=(), microphone=()',
            'X-Permitted-Cross-Domain-Policies' => 'none',
        ];

        foreach ($standardHeaders as $header => $value) {
            if (! $response->headers->has($header)) {
                $response->headers->set($header, $value);
            }
        }
    }

    /**
     * Set Content Security Policy header.
     */
    protected function setContentSecurityPolicyHeader(Response $response, array $cspConfig): void
    {
        if (! $this->shouldSetCspHeader($response)) {
            return;
        }

        $cspHeader = $this->buildCspHeader($cspConfig);

        if (! empty($cspHeader)) {
            $response->headers->set('Content-Security-Policy', $cspHeader);
        }
    }

    /**
     * Check if CSP header should be set.
     */
    protected function shouldSetCspHeader(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html') ||
               str_contains($contentType, 'text/xml') ||
               str_contains($contentType, 'application/xml');
    }

    /**
     * Build the CSP header string from config.
     */
    protected function buildCspHeader(array $cspConfig): string
    {
        $directives = [];

        foreach ($cspConfig as $directive => $sources) {
            if (empty($sources)) {
                $directives[] = $directive;
            } else {
                $sources = is_array($sources) ? $sources : [$sources];
                $directives[] = "{$directive} ".implode(' ', $sources);
            }
        }

        return implode('; ', $directives);
    }

    /**
     * Set Strict Transport Security header.
     */
    protected function setStrictTransportSecurityHeader(Response $response, ?string $hstsConfig): void
    {
        if (! $hstsConfig) {
            $hstsConfig = 'max-age=31536000; includeSubDomains';
        }

        $response->headers->set('Strict-Transport-Security', $hstsConfig);
    }
}
