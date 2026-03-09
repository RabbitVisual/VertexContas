<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Carbon\Carbon;
use Modules\Core\Models\Goal;
use Modules\Core\Models\RecurringTransaction;

class GoalContributionService
{
    /**
     * Sync recurring transaction for goal's automated monthly contribution.
     * Creates or updates one RecurringTransaction when goal has valid contribution; otherwise deactivates.
     */
    public function syncRecurringForGoal(Goal $goal): void
    {
        $hasContribution = $this->hasAutomatedContribution($goal);

        if ($hasContribution && $goal->completed_at === null) {
            $this->createOrUpdateRecurring($goal);
        } else {
            $this->deactivateRecurringForGoal($goal->id);
        }
    }

    public function hasAutomatedContribution(Goal $goal): bool
    {
        $amount = $goal->monthly_contribution ?? 0;

        return $amount > 0
            && $goal->contribution_account_id !== null
            && $goal->contribution_category_id !== null;
    }

    protected function createOrUpdateRecurring(Goal $goal): void
    {
        $day = (int) ($goal->contribution_recurrence_day ?? 1);
        $day = max(1, min(31, $day));

        $nextDate = $this->getNextOccurrenceDate($day);

        $recurring = RecurringTransaction::where('goal_id', $goal->id)->first();

        $payload = [
            'user_id' => $goal->user_id,
            'account_id' => $goal->contribution_account_id,
            'category_id' => $goal->contribution_category_id,
            'type' => 'expense',
            'amount' => $goal->monthly_contribution,
            'frequency' => 'monthly',
            'recurrence_day' => $day,
            'next_date' => $nextDate,
            'description' => 'Contribuição automática: ' . $goal->name,
            'is_active' => true,
            'is_baseline' => false,
            'goal_id' => $goal->id,
        ];

        if ($recurring) {
            $recurring->update($payload);
        } else {
            RecurringTransaction::create($payload);
        }
    }

    protected function getNextOccurrenceDate(int $day): Carbon
    {
        $now = now();
        $start = $now->copy()->startOfMonth();
        $daysInMonth = $start->daysInMonth;
        $safeDay = min($day, $daysInMonth);
        $thisMonth = $start->copy()->addDays($safeDay - 1);
        if ($thisMonth->gte($now)) {
            return $thisMonth;
        }

        $next = $now->copy()->addMonth()->startOfMonth();
        $nextDaysInMonth = $next->daysInMonth;
        $nextSafeDay = min($day, $nextDaysInMonth);

        return $next->copy()->addDays($nextSafeDay - 1);
    }

    public function deactivateRecurringForGoal(int $goalId): void
    {
        RecurringTransaction::where('goal_id', $goalId)->update(['is_active' => false]);
    }
}
