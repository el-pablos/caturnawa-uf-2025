<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckRegistrationOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip untuk admin dan superadmin
        if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))) {
            return $next($request);
        }

        // Skip untuk route khusus
        $exemptRoutes = [
            'admin.*',
            'login',
            'logout',
            'home',
            'about',
            'contact',
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // Cek apakah registrasi terbuka untuk route registration
        if ($request->routeIs('register*') || $request->routeIs('competition.register*')) {
            if (!Setting::isRegistrationOpen()) {
                return redirect()->route('public.home')->with('error', Setting::getRegistrationClosedMessage());
            }
        }

        return $next($request);
    }
}
