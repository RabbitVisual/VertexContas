<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Core\Models\RecurringTransaction;

/**
 * Gerencia fontes de receita recorrente (Financial Baseline).
 * Usado por FinancialHealthService para cálculo de capacidade mensal.
 */
class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Exibe o formulário de Minha Renda (linha de base financeira).
     */
    public function index(): View
    {
        $user = Auth::user();
        $existingIncomes = $this->getExistingIncomes($user);
        $hasIncome = ! empty($existingIncomes);
        $isEditMode = (bool) ($user->onboarding_completed ?? false) || $hasIncome;

        return view('core::income.index', [
            'isPro' => $user->isPro(),
            'isEditMode' => $isEditMode,
            'existingIncomes' => $existingIncomes,
        ]);
    }

    /**
     * Atualiza as fontes de receita recorrente do usuário.
     */
    public function store(Request $request): RedirectResponse
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

        $incomes = $request->input('incomes', []);
        $hadExisting = RecurringTransaction::where('user_id', $user->id)->where('type', 'income')->exists();

        DB::transaction(function () use ($user, $incomes) {
            RecurringTransaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->delete();

            foreach ($incomes as $index => $item) {
                $amount = $this->parseMoneyAmount($item['amount'] ?? 0);
                $day = (int) ($item['day'] ?? 1);
                $day = max(1, min(31, $day));
                $nextDate = $this->nextDateFromRecurrenceDay($day);

                RecurringTransaction::create([
                    'user_id' => $user->id,
                    'category_id' => null,
                    'account_id' => null,
                    'type' => 'income',
                    'amount' => $amount,
                    'frequency' => 'monthly',
                    'recurrence_day' => $day,
                    'next_date' => $nextDate,
                    'description' => $item['description'] ?? 'Receita',
                    'is_active' => true,
                ]);
            }
        });

        $redirectTo = $user->isPro() && \Illuminate\Support\Facades\Route::has('core.dashboard')
            ? route('core.dashboard')
            : route('paneluser.index');

        return redirect()
            ->to($redirectTo)
            ->with('success', $hadExisting ? 'Renda atualizada com sucesso.' : 'Fontes de receita cadastradas com sucesso.');
    }

    /**
     * Retorna as receitas recorrentes do usuário para preenchimento do formulário.
     *
     * @return array<int, array{description: string, amount: float, day: string}>
     */
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

    private function nextDateFromRecurrenceDay(int $day): Carbon
    {
        $now = now();
        $day = max(1, min(31, $day));
        $thisMonth = $now->copy()->startOfMonth();
        $daysInThisMonth = $thisMonth->daysInMonth;
        $safeDay = min($day, $daysInThisMonth);
        $thisMonth->day($safeDay);
        if ($thisMonth->gte($now)) {
            return $thisMonth;
        }
        $nextMonth = $now->copy()->addMonth()->startOfMonth();
        $safeDayNext = min($day, $nextMonth->daysInMonth);
        $nextMonth->day($safeDayNext);

        return $nextMonth;
    }
}
