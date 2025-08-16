<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CsrfDebugger
{
    public function handle(Request $request, Closure $next)
    {
        // Only debug POST requests to login
        if ($request->isMethod('POST') && $request->is('login')) {
            $this->debugCsrfInfo($request);
        }

        return $next($request);
    }

    private function debugCsrfInfo(Request $request)
    {
        $debugInfo = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'csrf_token_from_session' => $request->session()->token(),
            'csrf_token_from_request' => $request->input('_token'),
            'csrf_token_from_header' => $request->header('X-CSRF-TOKEN'),
            'csrf_token_from_meta' => $request->header('X-CSRF-TOKEN'),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'session_driver' => config('session.driver'),
            'session_cookie_name' => config('session.cookie'),
            'session_domain' => config('session.domain'),
            'session_secure' => config('session.secure'),
            'session_lifetime' => config('session.lifetime'),
            'app_url' => config('app.url'),
            'cookies' => $request->cookies->all(),
            'session_data' => $request->session()->all(),
        ];

        Log::info('CSRF Debug Info for Login Attempt:', $debugInfo);
    }
}
