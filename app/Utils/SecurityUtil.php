<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class SecurityUtil
{
    /**
     * Generate a secure random token.
     */
    public static function generateSecureToken(int $length = 64): string
    {
        return Str::random($length);
    }

    /**
     * Check if the request is coming from a secure connection.
     */
    public static function isSecureConnection(): bool
    {
        return Request::secure() ||
               Request::header('X-Forwarded-Proto') === 'https' ||
               Request::header('X-Forwarded-SSL') === 'on';
    }

    /**
     * Get the client's IP address.
     */
    public static function getClientIp(): string
    {
        $ip = Request::ip();

        // Check for Cloudflare
        if (Request::hasHeader('CF-Connecting-IP')) {
            $ip = Request::header('CF-Connecting-IP');
        }

        // Check for other proxy headers
        $proxyHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($proxyHeaders as $header) {
            if (Request::hasHeader($header)) {
                $ip = Request::header($header);
                break;
            }
        }

        // Handle multiple IPs in the header
        if (str_contains($ip, ',')) {
            $ips = explode(',', $ip);
            $ip = trim($ips[0]);
        }

        return $ip;
    }

    /**
     * Get the user agent from the request.
     */
    public static function getUserAgent(): string
    {
        return Request::header('User-Agent', 'unknown');
    }

    /**
     * Generate a secure password.
     */
    public static function generateSecurePassword(
        int $length = 16,
        bool $includeSpecialChars = true
    ): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($includeSpecialChars) {
            $chars .= '!@#$%^&*()-_=+[]{}|;:,.<>?';
        }

        $password = '';
        $max = mb_strlen($chars, '8bit') - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $max)];
        }

        return $password;
    }

    /**
     * Check if a password meets the security requirements.
     */
    public static function validatePasswordStrength(
        string $password,
        array $options = []
    ): array {
        $minLength = $options['min_length'] ?? 12;
        $requireUppercase = $options['require_uppercase'] ?? true;
        $requireLowercase = $options['require_lowercase'] ?? true;
        $requireNumbers = $options['require_numbers'] ?? true;
        $requireSpecialChars = $options['require_special_chars'] ?? true;

        $errors = [];

        if (strlen($password) < $minLength) {
            $errors[] = "The password must be at least $minLength characters long.";
        }

        if ($requireUppercase && ! preg_match('/[A-Z]/', $password)) {
            $errors[] = 'The password must contain at least one uppercase letter.';
        }

        if ($requireLowercase && ! preg_match('/[a-z]/', $password)) {
            $errors[] = 'The password must contain at least one lowercase letter.';
        }

        if ($requireNumbers && ! preg_match('/[0-9]/', $password)) {
            $errors[] = 'The password must contain at least one number.';
        }

        if ($requireSpecialChars && ! preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'The password must contain at least one special character.';
        }

        // Check for common passwords
        $commonPasswords = [
            'password', '123456', '12345678', '1234', 'qwerty', '12345',
            'dragon', 'baseball', 'football', 'letmein', 'monkey', 'abc123',
        ];

        if (in_array(strtolower($password), $commonPasswords)) {
            $errors[] = 'The password is too common. Please choose a more secure password.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Generate a two-factor authentication secret key.
     */
    public static function generateTwoFactorSecret(): string
    {
        $google2fa = new Google2FA;

        return $google2fa->generateSecretKey();
    }

    /**
     * Verify a two-factor authentication code.
     */
    public static function verifyTwoFactorCode(string $secret, string $code): bool
    {
        $google2fa = new Google2FA;

        try {
            return $google2fa->verifyKey($secret, $code, config('google2fa.window', 4));
        } catch (\Exception $e) {
            Log::error('2FA verification failed', [
                'error' => $e->getMessage(),
                'secret' => $secret ? '***' : 'empty',
                'code' => $code ? '***' : 'empty',
            ]);

            return false;
        }
    }

    /**
     * Log a security event.
     */
    public static function logSecurityEvent(string $level, string $message, array $context = []): void
    {
        $context = array_merge([
            'ip' => self::getClientIp(),
            'user_agent' => self::getUserAgent(),
            'url' => Request::fullUrl(),
            'user_id' => auth()->id(),
        ], $context);

        Log::channel('security')->$level($message, $context);
    }

    /**
     * Sanitize user input to prevent XSS attacks.
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Generate a secure random string.
     */
    public static function randomString(int $length = 32): string
    {
        $bytes = random_bytes($length);

        return bin2hex($bytes);
    }

    /**
     * Check if the current request is a potential CSRF attack.
     */
    public static function isPotentialCsrfAttack(): bool
    {
        // Check for missing or invalid CSRF token
        if (! Request::hasHeader('X-CSRF-TOKEN') && ! Request::has('_token')) {
            return true;
        }

        // Check for non-POST requests that modify state
        if (! in_array(Request::method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        // Check for missing or empty referer/origin headers
        $referer = Request::header('Referer');
        $origin = Request::header('Origin');

        if (empty($referer) && empty($origin)) {
            return true;
        }

        // Check if the request origin matches the application URL
        $appUrl = config('app.url');

        if (! empty($origin) && strpos($origin, $appUrl) !== 0) {
            return true;
        }

        if (! empty($referer)) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            $appHost = parse_url($appUrl, PHP_HOST);

            if ($refererHost !== $appHost) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the current request is a potential XSS attack.
     */
    public static function isPotentialXssAttack(): bool
    {
        // Check for common XSS patterns in input
        $inputs = Request::all();

        $xssPatterns = [
            '/<script[^>]*>.*<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<\?php/i',
            '/<\?=/i',
            '/<\?\s+echo/i',
            '/<\?\s+print/i',
            '/<\?\s+include/i',
            '/<\?\s+require/i',
            '/<\?\s+eval/i',
            '/<\?\s+system/i',
            '/<\?\s+shell_exec/i',
            '/<\?\s+passthru/i',
            '/<\?\s+exec/i',
            '/<\?\s+`.*`/i',
        ];

        foreach ($inputs as $input) {
            if (! is_string($input)) {
                continue;
            }

            foreach ($xssPatterns as $pattern) {
                if (preg_match($pattern, $input)) {
                    return true;
                }
            }
        }

        return false;
    }
}
