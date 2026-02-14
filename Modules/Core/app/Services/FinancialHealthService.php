<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Core\Models\Account;
use Modules\Core\Models\RecurringTransaction;
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
