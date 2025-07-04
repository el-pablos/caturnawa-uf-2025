<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check untuk admin dan superadmin
        if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))) {
            return $next($request);
        }

        // Skip maintenance check untuk route khusus
        $exemptRoutes = [
            'admin.*',
            'login',
            'logout',
            'maintenance',
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // Cek apakah maintenance mode aktif
        if (SystemSetting::isMaintenanceMode()) {
            return response()->view('errors.maintenance', [
                'message' => SystemSetting::getMaintenanceMessage()
            ], 503);
        }

        return $next($request);
    }
}
