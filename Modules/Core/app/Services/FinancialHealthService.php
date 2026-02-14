<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Core\Models\RecurringTransaction;

class FinancialHealthService
{
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
