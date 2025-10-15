<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            // Log additional context for debugging
            try {
                Log::error('Exception occurred', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => app()->bound('request') ? request()->fullUrl() : 'N/A',
                    'user_id' => app()->bound('auth') ? auth()->id() : null,
                    'ip' => app()->bound('request') ? request()->ip() : 'N/A',
                    'user_agent' => app()->bound('request') ? request()->userAgent() : 'N/A',
                ]);
            } catch (\Exception $logException) {
                // Fallback logging if services are not available
                error_log('Exception occurred: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle API requests
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        // Handle specific exceptions with custom error pages
        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->view('errors.403', [
                'exception' => $exception,
                'request' => $request
            ], 403);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('errors.404', [
                'exception' => $exception,
                'request' => $request
            ], 404);
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            // Log CSRF token mismatch for debugging
            \Illuminate\Support\Facades\Log::warning('CSRF Token Mismatch', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
                'csrf_from_session' => $request->session()->token(),
                'csrf_from_request' => $request->input('_token'),
                'referer' => $request->header('referer')
            ]);

            // If it's a login attempt, redirect back with error
            if ($request->is('login') && $request->isMethod('POST')) {
                return redirect()->route('login')
                    ->withInput($request->except('password'))
                    ->withErrors(['csrf' => 'Sesi kedaluwarsa. Silakan coba login kembali.']);
            }

            // For other requests, show 419 page
            return response()->view('errors.419', [
                'exception' => $exception,
                'request' => $request
            ], 419);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
            return response()->view('errors.429', [
                'exception' => $exception,
                'request' => $request
            ], 429);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $exception->getStatusCode();

            // Check if we have a custom error page for this status code
            if (view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", [
                    'exception' => $exception,
                    'request' => $request
                ], $statusCode);
            }
        }

        // Handle 500 errors
        if (app()->environment('production')) {
            return response()->view('errors.500', [
                'exception' => $exception,
                'request' => $request
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Throwable $exception)
    {
        $status = 500;
        $message = 'Internal Server Error';

        // Handle specific exception types
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found';
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $status = 401;
            $message = 'Unauthenticated';
        } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            $status = 403;
            $message = 'Forbidden';
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            $status = 422;
            $message = 'Validation failed';
        } elseif (method_exists($exception, 'getStatusCode')) {
            $status = $exception->getStatusCode();
            $message = $exception->getMessage();
        } else {
            $message = $exception->getMessage();
        }

        $response = [
            'success' => false,
            'message' => $message,
            'error_code' => $status,
        ];

        // Add debug info if in debug mode
        if (config('app.debug')) {
            $response['exception'] = get_class($exception);
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = collect($exception->getTrace())->take(5)->toArray();
        }

        return response()->json($response, $status);
    }
}
