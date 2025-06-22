<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk mengecek role pengguna
 * 
 * Memastikan pengguna memiliki role yang sesuai
 * untuk mengakses route tertentu
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();

        // Cek apakah user memiliki salah satu role yang diizinkan
        if (!$user->hasAnyRole($roles)) {
            // Redirect berdasarkan role user saat ini
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } elseif ($user->isJuri()) {
                return redirect()->route('juri.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } elseif ($user->isPeserta()) {
                return redirect()->route('peserta.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            // Fallback jika role tidak dikenali
            return redirect()->route('auth.login')
                ->with('error', 'Akses ditolak. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
