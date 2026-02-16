<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotLockedOut
{
    /**
     * Handle an incoming request. Redirects if IP is locked out after too many failed logins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('settings')) {
            return $next($request);
        }

        $ip = $request->ip();
        $lockoutKey = 'login_lockout_' . $ip;

        if (Cache::has($lockoutKey)) {
            $lockoutMinutes = (int) setting('security_lockout_time', 15);

            return redirect()->route('login')
                ->withErrors(['throttle' => "Muitas tentativas de login. Aguarde {$lockoutMinutes} minutos e tente novamente."])
                ->onlyInput('email');
        }

        return $next($request);
    }
}
