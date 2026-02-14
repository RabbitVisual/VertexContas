<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Core\Models\RecurringTransaction;

/**
 * Gerencia fontes de receita recorrente (Financial Baseline).
 * Usado por FinancialHealthService para cálculo de capacidade mensal.
 */
class IncomeController extends Controller
{
    protected \Modules\Core\Services\FinancialHealthService $financialService;

    public function __construct(\Modules\Core\Services\FinancialHealthService $financialService)
    {
        $this->financialService = $financialService;
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Exibe o formulário de Minha Renda (linha de base financeira).
     */
    public function index(): View
    {
        $user = Auth::user();
        $existingIncomes = $this->getExistingPlanning($user, 'income');
        $existingExpenses = $this->getExistingPlanning($user, 'expense');

        $accounts = \Modules\Core\Models\Account::where('user_id', $user->id)->get();
        $categories = \Modules\Core\Models\Category::forUser($user)->get();

        return view('core::income.index', compact('existingIncomes', 'existingExpenses', 'accounts', 'categories'));
    }

    /**
     * Atualiza as fontes de receita recorrente do usuário.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isPro = $user->isPro();

        $incomes = $request->input('incomes', []);
        $expenses = $request->input('expenses', []);

        $parsedIncomes = [];
        foreach ($incomes as $i => $item) {
            $parsedIncomes[$i] = $item;
            $parsedIncomes[$i]['amount'] = $this->parseMoneyAmount($item['amount'] ?? 0);
        }
        $request->merge(['incomes' => $parsedIncomes]);

        $parsedExpenses = [];
        foreach ($expenses as $i => $item) {
            $parsedExpenses[$i] = $item;
            $parsedExpenses[$i]['amount'] = $this->parseMoneyAmount($item['amount'] ?? 0);
        }
        $request->merge(['expenses' => $parsedExpenses]);

        $userId = $user->id;
        $rules = [
            'incomes' => ['required', 'array', 'min:1'],
            'incomes.*.description' => ['required', 'string', 'max:255'],
            'incomes.*.amount' => ['required', 'numeric', 'min:0'],
            'incomes.*.day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'incomes.*.account_id' => ['nullable', Rule::exists('accounts', 'id')->where('user_id', $userId)],
            'incomes.*.category_id' => ['nullable', Rule::exists('categories', 'id')->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId))],
        ];

        if (! $isPro) {
            $rules['incomes'][] = 'max:1';
        }

        $expenses = $request->input('expenses', []);
        if (! empty($expenses)) {
            $rules['expenses'] = ['array'];
            $rules['expenses.*.description'] = ['required', 'string', 'max:255'];
            $rules['expenses.*.amount'] = ['required', 'numeric', 'min:0'];
            $rules['expenses.*.day'] = ['nullable', 'integer', 'min:1', 'max:31'];
            $rules['expenses.*.account_id'] = ['nullable', Rule::exists('accounts', 'id')->where('user_id', $userId)];
            $rules['expenses.*.category_id'] = ['nullable', Rule::exists('categories', 'id')->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId))];
        }

        $request->validate($rules);

        $incomes = $request->input('incomes', []);
        $expenses = $request->input('expenses', []);
        foreach ($incomes as $i => $item) {
            $incomes[$i]['day'] = $item['day'] ?? 1;
        }
        foreach ($expenses as $i => $item) {
            $expenses[$i]['day'] = $item['day'] ?? 1;
        }
        $hadExisting = RecurringTransaction::where('user_id', $user->id)->where('is_baseline', true)->exists();

        $this->financialService->syncUserPlanning($user, $incomes, $expenses);

        $redirectTo = $user->isPro() && \Illuminate\Support\Facades\Route::has('core.dashboard')
            ? route('core.dashboard')
            : route('paneluser.index');

        return redirect()
            ->to($redirectTo)
            ->with('success', $hadExisting ? 'Planejamento atualizado com sucesso.' : 'Planejamento financeiro cadastrado com sucesso.');
    }

    /**
     * Retorna o planejamento recorrente do usuário.
     */
    private function getExistingPlanning(\App\Models\User $user, string $type): array
    {
        return RecurringTransaction::where('user_id', $user->id)
            ->where('type', $type)
            ->where('is_baseline', true)
            ->where('is_active', true)
            ->orderBy('recurrence_day')
            ->get()
            ->map(fn ($r) => [
                'description' => $r->description,
                'amount' => $r->amount,
                'day' => (string) ($r->recurrence_day ?? 1),
                'account_id' => $r->account_id,
                'category_id' => $r->category_id,
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
