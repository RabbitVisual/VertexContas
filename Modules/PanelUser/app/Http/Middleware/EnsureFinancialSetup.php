<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Core\Models\RecurringTransaction;

class EnsureFinancialSetup
{
    /**
     * Routes that do not require income setup (avoid redirect loop).
     */
    private const EXCLUDED_ROUTES = [
        'paneluser.onboarding.setup-income',
        'paneluser.onboarding.store-income',
        'paneluser.onboarding.complete',
        'user.subscription.index',
        'login',
        'register',
        'password.request',
        'password.reset',
        'verification.notice',
        'verification.verify',
    ];

    /**
     * Redirect to income setup wizard if user has no recurring income.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::EXCLUDED_ROUTES, true)) {
            return $next($request);
        }

        $count = RecurringTransaction::where('user_id', $request->user()->id)
            ->where('type', 'income')
            ->count();

        if ($count === 0) {
            return redirect()->route('paneluser.onboarding.setup-income')
                ->with('info', __('Configure suas fontes de receita para continuar.'));
        }

        return $next($request);
    }
}
