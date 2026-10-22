<?php

namespace App\Exceptions;

use App\Utils\SecurityUtil;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Custom reporting logic can be added here
        })->stop();

        // Log security-related exceptions
        $this->reportable(function (Throwable $e) {
            if ($this->shouldLogForSecurity($e)) {
                $this->logSecurityException($e);
            }
        })->stop();
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Log security-related exceptions
        if ($this->shouldLogForSecurity($e)) {
            $this->logSecurityException($e);
        }

        // Handle TokenMismatchException (expired session)
        if ($e instanceof TokenMismatchException) {
            return $this->handleTokenMismatch($request, $e);
        }

        // Handle 403 Forbidden errors
        if ($e instanceof AuthorizationException) {
            return $this->handleAuthorizationException($request, $e);
        }

        // Handle 404 Not Found errors
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return $this->handleNotFound($request, $e);
        }

        // Handle 405 Method Not Allowed errors
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->handleMethodNotAllowed($request, $e);
        }

        // Handle 429 Too Many Requests errors
        if ($e instanceof ThrottleRequestsException) {
            return $this->handleThrottleRequests($request, $e);
        }

        // Handle 500 Internal Server Error
        if ($this->isHttpException($e) && $e->getStatusCode() >= 500) {
            return $this->handleServerError($request, $e);
        }

        // Handle 419 Page Expired (Session Expired)
        if ($e->getPrevious() instanceof TokenMismatchException) {
            return $this->handleTokenMismatch($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle token mismatch exception (expired session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleTokenMismatch($request, Throwable $e)
    {
        $message = 'Your session has expired. Please refresh the page and try again.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => ['session' => [$message]],
            ], 419);
        }

        return redirect()
            ->back()
            ->withInput($request->except('password', '_token', 'password_confirmation'))
            ->withErrors(['session_expired' => $message]);
    }

    /**
     * Handle 403 Forbidden errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleAuthorizationException($request, Throwable $e)
    {
        $message = $e->getMessage() ?: 'This action is unauthorized.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => ['authorization' => [$message]],
            ], 403);
        }

        return response()->view('errors.403', [
            'exception' => $e,
            'message' => $message,
        ], 403);
    }

    /**
     * Handle 404 Not Found errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleNotFound($request, Throwable $e)
    {
        $message = 'The requested resource was not found.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => ['not_found' => [$message]],
            ], 404);
        }

        return response()->view('errors.404', [
            'exception' => $e,
            'message' => $message,
        ], 404);
    }

    /**
     * Handle 405 Method Not Allowed errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleMethodNotAllowed($request, Throwable $e)
    {
        $message = 'The requested method is not allowed for this resource.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => ['method_not_allowed' => [$message]],
            ], 405);
        }

        return response()->view('errors.405', [
            'exception' => $e,
            'message' => $message,
        ], 405);
    }

    /**
     * Handle 429 Too Many Requests errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleThrottleRequests($request, Throwable $e)
    {
        $message = 'Too many attempts. Please try again later.';

        if ($request->expectsJson()) {
            $headers = $this->getThrottleHeaders($e);

            return response()->json([
                'message' => $message,
                'errors' => ['throttle' => [$message]],
                'retry_after' => $headers['Retry-After'] ?? null,
            ], 429, $headers);
        }

        return response()->view('errors.429', [
            'exception' => $e,
            'message' => $message,
        ], 429);
    }

    /**
     * Handle 500 Internal Server Error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleServerError($request, Throwable $e)
    {
        $message = 'Whoops, something went wrong on our servers.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => ['server_error' => [$message]],
            ], 500);
        }

        return response()->view('errors.500', [
            'exception' => $e,
            'message' => $message,
        ], 500);
    }

    /**
     * Get the throttle headers from the exception.
     */
    protected function getThrottleHeaders(Throwable $e): array
    {
        if (method_exists($e, 'getHeaders')) {
            return $e->getHeaders();
        }

        return [];
    }

    /**
     * Determine if the exception should be logged for security.
     */
    protected function shouldLogForSecurity(Throwable $e): bool
    {
        // Don't log validation exceptions
        if ($e instanceof ValidationException) {
            return false;
        }

        // Check if the exception is in the security loggable list
        foreach ($this->securityLoggable as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        // Check if the HTTP status code should be logged
        if ($this->isHttpException($e)) {
            return in_array($e->getStatusCode(), $this->securityStatusCodes);
        }

        return false;
    }

    /**
     * Log a security-related exception.
     */
    protected function logSecurityException(Throwable $e): void
    {
        $context = [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => request()?->fullUrl(),
            'method' => request()?->method(),
            'ip' => SecurityUtil::getClientIp(),
            'user_agent' => request()?->userAgent(),
            'user_id' => auth()?->id(),
        ];

        // Add HTTP status code if available
        if ($this->isHttpException($e)) {
            $context['status_code'] = $e->getStatusCode();
        }

        // Add previous exception if available
        if ($previous = $e->getPrevious()) {
            $context['previous'] = [
                'exception' => get_class($previous),
                'message' => $previous->getMessage(),
                'code' => $previous->getCode(),
                'file' => $previous->getFile(),
                'line' => $previous->getLine(),
            ];
        }

        // Log the exception to the security channel
        Log::channel('security')->error('Security Exception', $context);
    }

    /**
     * Handle authorization exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleAuthorizationException($request, AuthorizationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $e->getMessage() ?: 'This action is unauthorized.',
            ], 403);
        }

        // If user is not authenticated, redirect to login
        if (auth()->guest()) {
            return redirect()->guest(route('login'));
        }

        // Show a custom 403 page for authenticated users
        return response()->view('errors.403', [
            'exception' => $e,
            'message' => $e->getMessage() ?: 'You are not authorized to access this page.',
        ], 403);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('login'));
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
