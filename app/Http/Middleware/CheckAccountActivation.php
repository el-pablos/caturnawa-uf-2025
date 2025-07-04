<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountActivation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip untuk user yang belum login
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Skip untuk admin dan superadmin
        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Skip untuk route khusus
        $exemptRoutes = [
            'logout',
            'account.pending',
            'profile.edit',
            'profile.update',
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // Cek apakah akun sudah diaktivasi
        if (!$user->is_account_active) {
            return response()->view('errors.account-pending', [
                'message' => 'Akun Anda belum diaktivasi. Silakan hubungi admin untuk aktivasi akun.'
            ], 403);
        }

        return $next($request);
    }
}
