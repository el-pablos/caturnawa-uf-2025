<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorStatistic;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests for public pages
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('api/*')) {
            try {
                VisitorStatistic::recordVisitor($request);
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Log::error('Visitor tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
