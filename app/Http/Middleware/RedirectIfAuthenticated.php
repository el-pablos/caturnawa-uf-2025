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
                
                // Redirect based on user role
                if ($user->isSuperAdmin() || $user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->isJuri()) {
                    return redirect()->route('juri.dashboard');
                } else {
                    return redirect()->route('peserta.dashboard');
                }
            }
        }

        return $next($request);
    }
}
