<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, \Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                if ($user->isSuperAdmin()) {
                    return redirect()->route('admin.admin.dashboard');
                } elseif ($user->isAdmin()) {
                    return redirect()->route('admin.admin.dashboard');
                } elseif ($user->isJuri()) {
                    return redirect()->route('juri.juri.dashboard');
                } elseif ($user->isPeserta()) {
                    return redirect()->route('peserta.peserta.dashboard');
                } else {
                    // User has no roles - log them out to prevent redirect loop
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Akun Anda belum memiliki role yang valid. Silakan hubungi administrator.');
                }
            }
        }

        return $next($request);
    }
}
