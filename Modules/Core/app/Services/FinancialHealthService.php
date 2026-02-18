<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Account;
use Modules\Core\Models\RecurringTransaction;
use Modules\Core\Models\Ticket;
use Modules\Core\Models\Transaction;

class FinancialHealthService
{
    /**
     * Single source of truth: full financial snapshot for a user.
     * Used by Admin and Support panels.
     */
    public function getUserFinancialSnapshot(User $user): array
    {
        $monthlyIncome = $this->calculateMonthlyCapacity($user);
        $accountBalance = (float) Account::where('user_id', $user->id)->sum('balance');

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $monthlyExpenses = (float) Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $freeCashflow = $monthlyIncome - $monthlyExpenses;

        return [
            'monthly_income' => $monthlyIncome,
            'account_balance' => $accountBalance,
            'monthly_expenses' => $monthlyExpenses,
            'free_cashflow' => $freeCashflow,
        ];
    }

    /**
     * Count users with open/pending tickets and support access who have negative free cashflow.
     * Used by Support Dashboard for "Usuários em Risco Financeiro" card.
     *
     * @param  int  $maxUsers  Limit for performance (default 50)
     */
    public function getUsersAtFinancialRiskCount(int $maxUsers = 50): int
    {
        $userIds = Ticket::query()
            ->whereIn('status', ['open', 'pending'])
            ->distinct()
            ->pluck('user_id')
            ->take($maxUsers)
            ->toArray();

        if (empty($userIds)) {
            return 0;
        }

        $usersWithAccess = User::query()
            ->whereIn('id', $userIds)
            ->whereNotNull('support_access_expires_at')
            ->where('support_access_expires_at', '>', now())
            ->get();

        $count = 0;
        foreach ($usersWithAccess as $user) {
            $snapshot = $this->getUserFinancialSnapshot($user);
            if (($snapshot['free_cashflow'] ?? 0) < 0) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Batch monthly income for multiple users (avoids N+1 in lists).
     */
    public function getMonthlyIncomeForUserIds(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }

        $totals = RecurringTransaction::query()
            ->whereIn('user_id', $userIds)
            ->where('type', 'income')
            ->where('is_baseline', true)
            ->active()
            ->get()
            ->groupBy('user_id')
            ->map(function (Collection $items) {
                return $items->sum(function (RecurringTransaction $rt) {
                    return match ($rt->frequency) {
                        'monthly' => (float) $rt->amount,
                        'yearly' => (float) $rt->amount / 12,
                        'weekly' => (float) $rt->amount * 4.33,
                        'daily' => (float) $rt->amount * 30,
                        default => (float) $rt->amount,
                    };
                });
            });

        $result = [];
        foreach ($userIds as $id) {
            $result[$id] = (float) ($totals->get($id) ?? 0);
        }
        return $result;
    }

    /**
     * Sum of monthly capacity (Income - Recurring Expenses).
     * For frequency = 'monthly', amount is used as-is.
     */
    public function calculateMonthlyCapacity(User $user): float
    {
        $recurring = RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('is_baseline', true)
            ->active()
            ->get();

        $income = $recurring->where('type', 'income')->sum(function (RecurringTransaction $rt) {
            return $this->getNormalizedMonthlyAmount($rt);
        });

        $expenses = $recurring->where('type', 'expense')->sum(function (RecurringTransaction $rt) {
            return $this->getNormalizedMonthlyAmount($rt);
        });

        return (float) ($income - $expenses);
    }

    /**
     * Renda Base: soma das receitas recorrentes (baseline) cadastradas no Onboarding.
     * Usado como denominador no 50/30/20.
     */
    public function getBaselineIncome(User $user): float
    {
        $recurring = RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_baseline', true)
            ->active()
            ->get();

        return (float) $recurring->sum(fn (RecurringTransaction $rt) => $this->getNormalizedMonthlyAmount($rt));
    }

    /**
     * Budget health analysis: 50/30/20 deviations per pillar.
     * Uses Baseline Income as denominator. Returns actual %, target %, deviation, status (over/under/ok).
     *
     * @return array{baseline_income: float, total_expenses: float, pillars: array, savings_pct: float}
     */
    public function getBudgetHealthAnalysis(User $user): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $baselineIncome = $this->getBaselineIncome($user);
        if ($baselineIncome <= 0) {
            $baselineIncome = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
        }

        if ($baselineIncome <= 0) {
            return [
                'baseline_income' => 0.0,
                'total_expenses' => 0.0,
                'pillars' => [
                    'essential' => ['actual_pct' => 0.0, 'target_pct' => 50, 'deviation' => 0.0, 'status' => 'ok', 'label' => 'Essential: 0% (Ok)'],
                    'lifestyle' => ['actual_pct' => 0.0, 'target_pct' => 30, 'deviation' => 0.0, 'status' => 'ok', 'label' => 'Lifestyle: 0% (Ok)'],
                    'financial' => ['actual_pct' => 0.0, 'target_pct' => 20, 'deviation' => 0.0, 'status' => 'ok', 'label' => 'Financial: 0% (Ok)'],
                ],
                'savings_pct' => 0.0,
            ];
        }

        $expensesByTypeGroup = Transaction::query()
            ->where('transactions.user_id', $user->id)
            ->where('transactions.type', 'expense')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.date', [$start, $end])
            ->whereNull('transactions.destination_account_id')
            ->whereNull('transactions.parent_id')
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(DB::raw('COALESCE(NULLIF(categories.type_group, ""), "lifestyle") as type_group'), DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('type_group')
            ->get()
            ->keyBy('type_group');

        $essentialTotal = (float) ($expensesByTypeGroup->get('essential')?->total ?? 0);
        $lifestyleTotal = (float) ($expensesByTypeGroup->get('lifestyle')?->total ?? 0);
        $financialTotal = (float) ($expensesByTypeGroup->get('financial')?->total ?? 0);
        $totalExpenses = $essentialTotal + $lifestyleTotal + $financialTotal;

        $essentialPct = ($essentialTotal / $baselineIncome) * 100;
        $lifestylePct = ($lifestyleTotal / $baselineIncome) * 100;
        $financialPct = ($financialTotal / $baselineIncome) * 100;
        $savingsPct = max(0.0, (($baselineIncome - $totalExpenses) / $baselineIncome) * 100);

        $targets = ['essential' => 50, 'lifestyle' => 30, 'financial' => 20];
        $actuals = ['essential' => $essentialPct, 'lifestyle' => $lifestylePct, 'financial' => $financialPct];
        $labels = ['essential' => 'Essencial', 'lifestyle' => 'Estilo de Vida', 'financial' => 'Financeiro'];

        $pillars = [];
        foreach (['essential', 'lifestyle', 'financial'] as $key) {
            $actual = round($actuals[$key], 2);
            $target = $targets[$key];
            $deviation = round($actual - $target, 2);
            $status = $deviation > 0 ? 'over' : ($deviation < -2 ? 'under' : 'ok');
            $statusLabel = $status === 'over' ? 'Acima' : ($status === 'under' ? 'Abaixo' : 'Ok');
            $pillars[$key] = [
                'actual_pct' => $actual,
                'target_pct' => $target,
                'deviation' => $deviation,
                'status' => $status,
                'label' => sprintf('%s: %s%s%% (%s)', $labels[$key], $deviation >= 0 ? '+' : '', $deviation, $statusLabel),
            ];
        }

        return [
            'baseline_income' => round($baselineIncome, 2),
            'total_expenses' => round($totalExpenses, 2),
            'pillars' => $pillars,
            'savings_pct' => round($savingsPct, 2),
        ];
    }

    /**
     * Days since last modification of any baseline income (for anti-gaming).
     * Returns 999 if never modified or no baseline; 0 if modified today.
     */
    public function getBaselineStableDays(User $user): int
    {
        $lastModified = RecurringTransaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_baseline', true)
            ->max('updated_at');

        if (! $lastModified) {
            return 999;
        }

        return (int) now()->startOfDay()->diffInDays(Carbon::parse($lastModified)->startOfDay(), false);
    }

    /**
     * Average monthly income from actual Transaction records (real receipts).
     * Used to detect baseline inflation vs. realized income.
     */
    public function getRealizedMonthlyIncomeAverage(User $user, int $months = 3): float
    {
        if ($months < 1) {
            return 0.0;
        }

        $totals = [];
        for ($i = 0; $i < $months; $i++) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $total = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
            $totals[] = $total;
        }

        $sum = array_sum($totals);
        return $sum > 0 ? round($sum / $months, 2) : 0.0;
    }

    /**
     * Income to use for medal evaluation (anti-gaming: prevents temporary baseline inflation).
     * Uses min(baseline, realized) when baseline was recently changed or suspiciously higher than realized.
     */
    public function getEffectiveIncomeForMedals(User $user, Carbon $start, Carbon $end): float
    {
        $baseline = $this->getBaselineIncome($user);
        if ($baseline <= 0) {
            return (float) Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
        }

        $stableDays = $this->getBaselineStableDays($user);
        $realizedAvg = $this->getRealizedMonthlyIncomeAverage($user, 3);

        if ($stableDays < 30 && $realizedAvg > 0) {
            return min($baseline, $realizedAvg);
        }

        if ($realizedAvg > 0 && $baseline > $realizedAvg * 1.15) {
            return min($baseline, $realizedAvg);
        }

        return $baseline;
    }

    /**
     * 50/30/20 breakdown: percentages of income in each pillar.
     * Uses type_group (essential, lifestyle, financial). Returns want_pct/savings_pct for RuleEngine compatibility.
     * When $overrideIncome > 0, uses it instead of baseline (for medal anti-gaming).
     *
     * @return array{essential_pct: float, want_pct: float, savings_pct: float, lifestyle_pct: float, financial_pct: float}
     */
    public function get503020Breakdown(User $user, Carbon $start, Carbon $end, ?float $overrideIncome = null): array
    {
        $income = $overrideIncome > 0 ? $overrideIncome : $this->getBaselineIncome($user);
        if ($income <= 0) {
            $income = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->where('status', 'completed')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
        }

        if ($income <= 0) {
            return [
                'essential_pct' => 0.0,
                'want_pct' => 0.0,
                'savings_pct' => 0.0,
                'lifestyle_pct' => 0.0,
                'financial_pct' => 0.0,
            ];
        }

        $expensesByTypeGroup = Transaction::query()
            ->where('transactions.user_id', $user->id)
            ->where('transactions.type', 'expense')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.date', [$start, $end])
            ->whereNull('transactions.destination_account_id')
            ->whereNull('transactions.parent_id')
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(DB::raw('COALESCE(NULLIF(categories.type_group, ""), "lifestyle") as type_group'), DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('type_group')
            ->get()
            ->keyBy('type_group');

        $essentialTotal = (float) ($expensesByTypeGroup->get('essential')?->total ?? 0);
        $lifestyleTotal = (float) ($expensesByTypeGroup->get('lifestyle')?->total ?? 0);
        $financialTotal = (float) ($expensesByTypeGroup->get('financial')?->total ?? 0);
        $totalExpense = $essentialTotal + $lifestyleTotal + $financialTotal;

        $savingsPct = $income > 0 ? max(0.0, (($income - $totalExpense) / $income) * 100) : 0.0;
        $essentialPct = $income > 0 ? ($essentialTotal / $income) * 100 : 0.0;
        $lifestylePct = $income > 0 ? ($lifestyleTotal / $income) * 100 : 0.0;
        $financialPct = $income > 0 ? ($financialTotal / $income) * 100 : 0.0;
        $wantPct = $lifestylePct;

        return [
            'essential_pct' => round($essentialPct, 2),
            'want_pct' => round($wantPct, 2),
            'savings_pct' => round($savingsPct, 2),
            'lifestyle_pct' => round($lifestylePct, 2),
            'financial_pct' => round($financialPct, 2),
        ];
    }

    /**
     * Average monthly expenses over the last N months.
     * Used for reserve calculation to avoid gaming (e.g. zero expenses in current month).
     */
    public function getMonthlyExpensesAverage(User $user, int $months = 3): float
    {
        if ($months < 1) {
            return 0.0;
        }

        $totals = [];
        for ($i = 0; $i < $months; $i++) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $total = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
            $totals[] = $total;
        }

        $sum = array_sum($totals);
        return $sum > 0 ? round($sum / $months, 2) : 0.0;
    }

    /**
     * Projection data for Vertex AI card: reserve months, savings rate, trend summary.
     *
     * @return array{reserve_months: float, savings_rate: float, monthly_surplus: float, months_until_reserve_depleted: float|null, trend_summary: string, balance: float, monthly_income: float, monthly_expense: float}
     */
    public function getProjectionData(User $user): array
    {
        $snapshot = $this->getUserFinancialSnapshot($user);
        $income = (float) ($snapshot['monthly_income'] ?? 0);
        $expense = (float) ($snapshot['monthly_expenses'] ?? 0);
        $balance = (float) ($snapshot['account_balance'] ?? 0);
        $surplus = $income - $expense;

        $reserveMonths = $expense > 0 ? round($balance / $expense, 1) : 0.0;
        $savingsRate = $income > 0 ? max(0.0, ($surplus / $income) * 100) : 0.0;

        $monthsUntilDepleted = null;
        if ($surplus < 0 && $balance > 0) {
            $monthsUntilDepleted = round($balance / abs($surplus), 1);
        }

        if ($surplus < 0 && $monthsUntilDepleted !== null) {
            $trendSummary = sprintf('Se continuar gastando assim, sua reserva acaba em aproximadamente %.0f meses.', $monthsUntilDepleted);
        } elseif ($savingsRate >= 20) {
            $trendSummary = sprintf('Com taxa de poupança de %.0f%%, seu patrimônio tende a crescer. Mantenha a disciplina!', $savingsRate);
        } elseif ($savingsRate > 0) {
            $trendSummary = sprintf('Reserva de %.1f meses de gastos. Taxa de poupança de %.0f%% — há espaço para melhorar.', $reserveMonths, $savingsRate);
        } else {
            $trendSummary = sprintf('Sua reserva cobre %.1f meses de gastos. Considere reduzir despesas para recompor.', $reserveMonths);
        }

        return [
            'reserve_months' => $reserveMonths,
            'savings_rate' => round($savingsRate, 1),
            'monthly_surplus' => round($surplus, 2),
            'months_until_reserve_depleted' => $monthsUntilDepleted,
            'trend_summary' => $trendSummary,
            'balance' => $balance,
            'monthly_income' => $income,
            'monthly_expense' => $expense,
        ];
    }

    /**
     * Reserve months: account_balance / average_monthly_expenses.
     * Returns 0 if no meaningful expenses (anti-gaming: no infinite reserve).
     */
    public function getReserveMonths(User $user, int $avgMonths = 3): float
    {
        $balance = (float) Account::where('user_id', $user->id)->sum('balance');
        $monthlyExpenses = $this->getMonthlyExpensesAverage($user, $avgMonths);

        if ($monthlyExpenses <= 0) {
            return 0.0;
        }

        return round($balance / $monthlyExpenses, 2);
    }

    /**
     * Transaction count within date range (for rule guards).
     */
    public function getTransactionCount(User $user, Carbon $start, Carbon $end): int
    {
        return Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$start, $end])
            ->count();
    }

    /**
     * Account age in days (for rule guards).
     */
    public function getAccountAgeDays(User $user): int
    {
        $createdAt = $user->created_at ?? now();
        return (int) now()->diffInDays($createdAt, false);
    }

    /**
     * Max consecutive days with at least one completed transaction in the last N days.
     */
    public function getConsecutiveTransactionDays(User $user, int $lookback = 30): int
    {
        $start = now()->subDays($lookback)->startOfDay();
        $dates = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('date', '>=', $start)
            ->selectRaw('DATE(date) as d')
            ->distinct()
            ->orderBy('d')
            ->pluck('d')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $maxStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < count($dates); $i++) {
            $prev = Carbon::parse($dates[$i - 1]);
            $curr = Carbon::parse($dates[$i]);
            if ($curr->diffInDays($prev) === 1) {
                $currentStreak++;
            } else {
                $maxStreak = max($maxStreak, $currentStreak);
                $currentStreak = 1;
            }
        }

        return max($maxStreak, $currentStreak);
    }

    /**
     * Normalize amounts based on frequency.
     */
    private function getNormalizedMonthlyAmount(RecurringTransaction $rt): float
    {
        return match ($rt->frequency) {
            'monthly' => (float) $rt->amount,
            'yearly' => (float) $rt->amount / 12,
            'weekly' => (float) $rt->amount * 4.33,
            'daily' => (float) $rt->amount * 30,
            default => (float) $rt->amount,
        };
    }

    /**
     * Active recurring income sources for breakdown (description, amount).
     * Used for tooltip/list on dashboard when user has multiple sources (Pro).
     */
    public function getIncomeBreakdown(User $user): Collection
    {
        return RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_baseline', true)
            ->active()
            ->orderBy('description')
            ->get()
            ->map(fn (RecurringTransaction $rt) => [
                'description' => $rt->description ?? 'Receita',
                'amount' => (float) $rt->amount,
            ]);
    }

    /**
     * Income sources with normalized monthly amounts for consulting report / AI context.
     */
    public function getIncomeSourcesForConsulting(User $user): Collection
    {
        return RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->where('is_baseline', true)
            ->active()
            ->orderBy('description')
            ->get()
            ->map(fn (RecurringTransaction $rt) => [
                'description' => $rt->description ?? 'Receita',
                'amount' => $this->getNormalizedMonthlyAmount($rt),
            ]);
    }
    /**
     * Centralized logic to sync user budget planning (Baseline).
     * Now supports both Income and Expense with Account linkage.
     */
    public function syncUserPlanning(\App\Models\User $user, array $incomes, array $expenses = []): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $incomes, $expenses) {
            // 1. Soft delete only baseline (Planejamento); keep scheduled "Repetir" recurrences
            RecurringTransaction::where('user_id', $user->id)
                ->where('is_baseline', true)
                ->delete();

            // 2. Create incomes
            foreach ($incomes as $item) {
                $this->createRecurringFromBaseline($user, $item, 'income');
            }

            // 3. Create recurring expenses (fixed costs)
            foreach ($expenses as $item) {
                $this->createRecurringFromBaseline($user, $item, 'expense');
            }
        });
    }

    private function createRecurringFromBaseline(\App\Models\User $user, array $item, string $type): void
    {
        // Parse amount if it's a string with "R$" or similar (common in current views)
        $amount = (float) $this->parseMoneyAmount($item['amount'] ?? 0);
        if ($amount <= 0) return;

        $day = (int) ($item['day'] ?? 1);
        $day = max(1, min(31, $day));
        $nextDate = $this->calculateNextDateFromDay($day);

        RecurringTransaction::create([
            'user_id' => $user->id,
            'category_id' => !empty($item['category_id']) ? $item['category_id'] : null,
            'account_id' => !empty($item['account_id']) ? $item['account_id'] : null,
            'type' => $type,
            'amount' => $amount,
            'frequency' => 'monthly',
            'recurrence_day' => $day,
            'next_date' => $nextDate,
            'description' => $item['description'] ?? ($type === 'income' ? 'Receita' : 'Despesa Fixa'),
            'is_active' => true,
            'is_baseline' => true,
        ]);
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

    /**
     * Helper to calculate next date from recurrence day.
     */
    private function calculateNextDateFromDay(int $day): \Carbon\Carbon
    {
        $now = now();
        $thisMonth = $now->copy()->startOfMonth();
        $safeDay = min($day, $thisMonth->daysInMonth);
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
