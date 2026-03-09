<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Core\Models\Account;
use Modules\Core\Models\Goal;
use Modules\Core\Models\RecurringTransaction;

class EnsureWizardComplete
{
    /**
     * Routes that are allowed when wizard is incomplete (wizard steps + legal + logout etc).
     */
    private const EXCLUDED_ROUTES = [
        'paneluser.legal.acceptance',
        'paneluser.legal.store',
        'paneluser.wizard.show',
        'paneluser.wizard.purpose.store',
        'paneluser.wizard.income.store',
        'paneluser.wizard.account.store',
        'paneluser.wizard.complete',
        'paneluser.onboarding.complete',
        'login',
        'register',
        'logout',
        'password.request',
        'password.reset',
        'verification.notice',
        'verification.verify',
    ];

    /**
     * Redirect to wizard step 1 if user has no recurring income or no account.
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

        $userId = $request->user()->id;

        $hasGoal = Goal::where('user_id', $userId)->exists();
        $hasAccount = Account::where('user_id', $userId)->exists();
        $hasIncome = RecurringTransaction::where('user_id', $userId)
            ->where('type', 'income')
            ->exists();

        if (! $hasGoal || ! $hasAccount || ! $hasIncome) {
            return redirect()->route('paneluser.wizard.show')
                ->with('info', 'Complete a configuração inicial para acessar o painel.');
        }

        return $next($request);
    }
}
