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

        if (method_exists($exception, 'getStatusCode')) {
            $status = $exception->getStatusCode();
        }

        if (config('app.debug')) {
            $message = $exception->getMessage();
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => $status,
        ], $status);
    }
}
