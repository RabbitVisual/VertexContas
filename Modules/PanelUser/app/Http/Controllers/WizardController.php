<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Core\Models\Account;
use Modules\Core\Models\Goal;
use Modules\Core\Models\RecurringTransaction;
use Modules\Core\Services\FinancialHealthService;
use Modules\Core\Services\SubscriptionLimitService;

class WizardController extends Controller
{
    public const STEP_PURPOSE = 0;
    public const STEP_BALANCE = 1;
    public const STEP_INCOME = 2;
    public const STEP_DONE = 3;

    /** Purpose slugs and display names for the first goal. */
    public const PURPOSE_OPTIONS = [
        'sair_dividas' => 'Sair das dívidas',
        'guardar_dinheiro' => 'Guardar dinheiro',
        'organizar_gastos' => 'Organizar os gastos',
    ];

    public function __construct(
        protected FinancialHealthService $financialService,
        protected SubscriptionLimitService $limitService
    ) {}

    /**
     * Show wizard step. Flow: Purpose → Balance → Income → Done.
     * Complete when: at least 1 Goal, 1 Account, 1 RecurringTransaction (income).
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $step = (int) $request->query('step', $request->query('passo', 0));

        $hasGoal = Goal::where('user_id', $user->id)->exists();
        $hasAccount = Account::where('user_id', $user->id)->exists();
        $hasIncome = RecurringTransaction::where('user_id', $user->id)->where('type', 'income')->exists();

        if ($hasGoal && $hasAccount && $hasIncome && $step < self::STEP_DONE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_DONE]);
        }

        if (! $hasGoal && $step > self::STEP_PURPOSE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_PURPOSE]);
        }
        if ($hasGoal && ! $hasAccount && $step > self::STEP_BALANCE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BALANCE]);
        }
        if ($hasGoal && $hasAccount && ! $hasIncome && $step > self::STEP_INCOME) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_INCOME]);
        }
        if ($hasGoal && $step === self::STEP_PURPOSE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BALANCE]);
        }
        if ($hasAccount && $step === self::STEP_BALANCE) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_INCOME]);
        }
        if ($hasIncome && $step === self::STEP_INCOME) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_DONE]);
        }

        $step = max(self::STEP_PURPOSE, min(self::STEP_DONE, $step));

        $viewData = [
            'step' => $step,
            'totalSteps' => 4,
            'isPro' => $user->isPro(),
        ];

        return match ($step) {
            self::STEP_PURPOSE => view('paneluser::wizard.step-purpose', $viewData),
            self::STEP_BALANCE => view('paneluser::wizard.step-balance', $viewData),
            self::STEP_INCOME => view('paneluser::wizard.step-income', array_merge($viewData, [
                'existingIncomes' => $this->getExistingIncomes($user),
            ])),
            self::STEP_DONE => view('paneluser::wizard.step-done', $viewData),
            default => view('paneluser::wizard.step-purpose', $viewData),
        };
    }

    /**
     * Store purpose (first goal) and redirect to balance step.
     */
    public function storePurpose(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->limitService->canCreate($user, 'goal')) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_PURPOSE])
                ->withErrors(['limit' => 'Limite de metas atingido para seu plano.']);
        }

        $validated = $request->validate([
            'purpose' => ['required', 'string', 'in:' . implode(',', array_keys(self::PURPOSE_OPTIONS))],
        ], [
            'purpose.required' => 'Escolha seu maior objetivo.',
            'purpose.in' => 'Opção inválida.',
        ]);

        $name = self::PURPOSE_OPTIONS[$validated['purpose']] ?? 'Minha primeira meta';

        Goal::create([
            'user_id' => $user->id,
            'name' => $name,
            'target_amount' => 0,
            'current_amount' => 0,
        ]);

        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BALANCE])
            ->with('success', 'Objetivo definido. Agora vamos ao seu saldo.');
    }

    /**
     * Store account "Minha Conta" with initial balance and redirect to income step.
     */
    public function storeAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->limitService->canCreate($user, 'account')) {
            return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_BALANCE])
                ->withErrors(['limit' => 'Limite de contas atingido para seu plano.']);
        }

        $validated = $request->validate([
            'balance' => ['required', 'numeric', 'min:0'],
        ], [
            'balance.required' => 'Informe quanto você tem hoje.',
            'balance.min' => 'O valor não pode ser negativo.',
        ]);

        Account::create([
            'user_id' => $user->id,
            'name' => 'Minha Conta',
            'type' => 'checking',
            'balance' => (float) $validated['balance'],
        ]);

        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_INCOME])
            ->with('success', 'Conta criada. Agora conte-nos sobre sua renda.');
    }

    /**
     * Store income and redirect to done.
     *
     * Durante o onboarding, o objetivo é registrar ao menos uma fonte de renda principal
     * de forma simples, que servirá como base para o motor 50/30/20 e para os cálculos do dashboard.
     * Detalhamentos adicionais de renda podem ser feitos depois na tela \"Minha Renda\".
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

        return redirect()->route('paneluser.wizard.show', ['step' => self::STEP_DONE])
            ->with('success', 'Renda cadastrada. Tudo pronto para começar!');
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
