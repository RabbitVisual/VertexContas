<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Core\Models\Account;
use Modules\Core\Models\RecurringTransaction;
use Modules\Core\Services\FinancialHealthService;
use Modules\Core\Services\SubscriptionLimitService;

class WizardController extends Controller
{
    public const STEP_WELCOME = 0;
    public const STEP_INCOME = 1;
    public const STEP_ACCOUNT = 2;
    public const STEP_BUDGET_OPTIONAL = 3;
    public const STEP_DONE = 4;

    public function __construct(
        protected FinancialHealthService $financialService,
        protected SubscriptionLimitService $limitService
    ) {}

    /**
     * Show wizard step. Step is resolved from request or from current data (income/account).
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $step = (int) $request->query('step', $request->query('passo', 0));

        $hasIncome = RecurringTransaction::where('user_id', $user->id)->where('type', 'income')->exists();
        $hasAccount = Account::where('user_id', $user->id)->exists();

        // If wizard already complete, redirect to panel
        if ($hasIncome && $hasAccount && $step < self::STEP_DONE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_DONE]);
        }

        // Force step 1 or 2 if user skipped ahead without data (allow step 0 welcome)
        if (! $hasIncome && $step > self::STEP_INCOME) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_INCOME]);
        }
        if ($hasIncome && ! $hasAccount && $step > self::STEP_ACCOUNT) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_ACCOUNT]);
        }
        // If already has income and viewing step 1, skip to step 2
        if ($hasIncome && $step === self::STEP_INCOME) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_ACCOUNT]);
        }
        // If already has account and viewing step 2, skip to step 3
        if ($hasAccount && $step === self::STEP_ACCOUNT) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BUDGET_OPTIONAL]);
        }

        $step = max(self::STEP_WELCOME, min(self::STEP_DONE, $step));

        $viewData = [
            'step' => $step,
            'totalSteps' => 5,
            'isPro' => $user->isPro(),
        ];

        return match ($step) {
            self::STEP_WELCOME => view('paneluser::wizard.step-welcome', $viewData),
            self::STEP_INCOME => view('paneluser::wizard.step-income', array_merge($viewData, [
                'existingIncomes' => $this->getExistingIncomes($user),
            ])),
            self::STEP_ACCOUNT => view('paneluser::wizard.step-account', $viewData),
            self::STEP_BUDGET_OPTIONAL => view('paneluser::wizard.step-budget-optional', $viewData),
            self::STEP_DONE => view('paneluser::wizard.step-done', $viewData),
            default => view('paneluser::wizard.step-welcome', $viewData),
        };
    }

    /**
     * Store income and redirect to account step.
     */
    public function storeIncome(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isPro = $user->isPro();

        $incomes = $request->input('incomes', []);
        $parsedIncomes = [];
        foreach ($incomes as $i => $item) {
            $parsedIncomes[$i] = $item;
            $parsedIncomes[$i]['amount'] = $this->parseMoneyAmount($item['amount'] ?? 0);
        }
        $request->merge(['incomes' => $parsedIncomes]);

        $rules = [
            'incomes' => ['required', 'array', 'min:1'],
            'incomes.*.description' => ['required', 'string', 'max:255'],
            'incomes.*.amount' => ['required', 'numeric', 'min:0'],
            'incomes.*.day' => ['required', 'integer', 'min:1', 'max:31'],
        ];
        if (! $isPro) {
            $rules['incomes'][] = 'max:1';
        }

        $request->validate($rules);

        $this->financialService->syncUserPlanning($user, $request->input('incomes', []), []);

        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_ACCOUNT])
            ->with('success', 'Renda cadastrada. Agora cadastre sua primeira conta.');
    }

    /**
     * Store account and redirect to budget-optional step.
     */
    public function storeAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->limitService->canCreate($user, 'account')) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_ACCOUNT])
                ->withErrors(['limit' => 'Limite de contas atingido para seu plano.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:checking,savings,cash'],
            'balance' => ['required', 'numeric', 'min:0'],
        ], [
            'name.required' => 'O nome da conta é obrigatório.',
            'type.required' => 'O tipo da conta é obrigatório.',
            'type.in' => 'Selecione um tipo válido.',
            'balance.required' => 'O saldo inicial é obrigatório.',
            'balance.min' => 'O saldo não pode ser negativo.',
        ]);

        Account::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'balance' => (float) $validated['balance'],
        ]);

        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BUDGET_OPTIONAL])
            ->with('success', 'Conta criada. Quer definir um orçamento agora?');
    }

    /**
     * Skip budget step and go to done.
     */
    public function skipBudget(): RedirectResponse
    {
        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_DONE]);
    }

    /**
     * Mark onboarding complete and redirect to panel.
     */
    public function complete(): RedirectResponse
    {
        $user = Auth::user();
        $user->onboarding_completed = true;
        $user->save();

        $route = $user->isPro() && \Illuminate\Support\Facades\Route::has('core.dashboard')
            ? 'core.dashboard'
            : 'paneluser.index';

        return redirect()->route($route)->with('success', 'Configuração concluída. Bem-vindo ao Vertex Contas!');
    }

    private function getExistingIncomes(\App\Models\User $user): array
    {
        return RecurringTransaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_active', true)
            ->orderBy('recurrence_day')
            ->get()
            ->map(fn ($r) => [
                'description' => $r->description,
                'amount' => $r->amount,
                'day' => (string) ($r->recurrence_day ?? 1),
            ])
            ->values()
            ->all();
    }

    private function parseMoneyAmount(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        $str = preg_replace('/[^\d,.-]/', '', (string) $value);
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);

        return (float) ($str ?: 0);
    }
}
