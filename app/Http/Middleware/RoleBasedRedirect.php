<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk redirect berdasarkan role
 * 
 * Memastikan setiap role diarahkan ke dashboard yang sesuai
 * dan tidak bisa mengakses dashboard role lain
 */
class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('auth.login');
        }

        $user = auth()->user();
        $currentRoute = $request->route()->getName();
        
        // Definisi route patterns untuk setiap role
        $roleRoutes = [
            'admin' => ['admin.', 'Super Admin', 'Admin'],
            'juri' => ['juri.', 'Juri'],
            'peserta' => ['peserta.', 'Peserta']
        ];

        // Cek apakah user mengakses route yang tidak sesuai dengan rolenya
        foreach ($roleRoutes as $routePrefix => $roles) {
            if (str_starts_with($currentRoute, $roles[0])) {
                // User mengakses route dengan prefix tertentu
                $hasValidRole = false;
                
                // Cek apakah user memiliki role yang sesuai
                for ($i = 1; $i < count($roles); $i++) {
                    if ($user->hasRole($roles[$i])) {
                        $hasValidRole = true;
                        break;
                    }
                }
                
                if (!$hasValidRole) {
                    // Redirect ke dashboard yang sesuai dengan role user
                    return $this->redirectToUserDashboard($user);
                }
                
                break;
            }
        }

        // Jika mengakses root dashboard, redirect ke dashboard yang sesuai
        if ($currentRoute === 'dashboard' || $currentRoute === 'home') {
            return $this->redirectToUserDashboard($user);
        }

        return $next($request);
    }

    /**
     * Redirect user ke dashboard yang sesuai dengan rolenya
     */
    private function redirectToUserDashboard($user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isJuri()) {
            return redirect()->route('juri.dashboard');
        } elseif ($user->isPeserta()) {
            return redirect()->route('peserta.dashboard');
        }

        // Fallback jika role tidak dikenali
        return redirect()->route('auth.login')
            ->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
    }
}
