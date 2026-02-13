<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPro
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isPro()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Recurso exclusivo para assinantes Pro.'], 403);
            }

            return back()->with('error', 'Recurso exclusivo para assinantes Pro.');
        }

        return $next($request);
    }
}
