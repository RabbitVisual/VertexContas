<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Core\Services\SettingService;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if maintenance mode is enabled
        $isMaintenanceOn = $this->settingService->get('maintenance_mode', false);

        if (!$isMaintenanceOn) {
            return $next($request);
        }

        // 2. Define Bypass Rules

        // A. Always allow the maintenance page itself to avoid redirect loops
        if ($request->routeIs('maintenance')) {
            return $next($request);
        }

        // B. Allow Admin Panel routes (URLs starting with /admin)
        if ($request->is('admin*')) {
            return $next($request);
        }

        // C. Allow Health Checks
        if ($request->is('up*')) {
            return $next($request);
        }

        // D. Allow Authenticated Admins (regardless of route, though mostly covering edge cases)
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        // 3. If none of the above, redirect to maintenance page
        return redirect()->route('maintenance');
    }
}
