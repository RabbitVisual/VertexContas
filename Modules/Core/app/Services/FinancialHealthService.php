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
     * Sum of monthly income from all active recurring income sources.
     * For frequency = 'monthly', amount is used as-is.
     */
    public function calculateMonthlyCapacity(User $user): float
    {
        return (float) RecurringTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'income')
            ->active()
            ->get()
            ->sum(function (RecurringTransaction $rt) {
                return match ($rt->frequency) {
                    'monthly' => (float) $rt->amount,
                    'yearly' => (float) $rt->amount / 12,
                    'weekly' => (float) $rt->amount * 4.33,
                    'daily' => (float) $rt->amount * 30,
                    default => (float) $rt->amount,
                };
            });
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
            ->active()
            ->orderBy('description')
            ->get()
            ->map(fn (RecurringTransaction $rt) => [
                'description' => $rt->description ?? 'Receita',
                'amount' => (float) $rt->amount,
            ]);
    }
}
