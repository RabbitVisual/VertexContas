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
        'core.income.index',
        'core.income.store',
        'paneluser.onboarding.complete',
        'paneluser.index',
        'user.subscription.index',
        'user.profile.show',
        'user.profile.edit',
        'user.profile.update',
        'user.profile.photo.upload',
        'user.profile.photo.active',
        'user.profile.photo.delete',
        'user.security.index',
        'user.security.password',
        'user.security.support-access.grant',
        'user.security.support-access.revoke',
        'user.security.export-logs',
        'user.notifications.index',
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
            return redirect()->route('core.income.index')
                ->with('info', 'Configure suas fontes de receita para continuar.');
        }

        return $next($request);
    }
}
