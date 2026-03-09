<?php

declare(strict_types=1);

namespace Modules\Core\Observers;

use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Goal;
use Modules\Core\Services\GoalContributionService;

class GoalObserver
{
    public function __construct(
        protected GoalContributionService $goalContributionService
    ) {}

    public function created(Goal $goal): void
    {
        DB::transaction(function () use ($goal) {
            $this->goalContributionService->syncRecurringForGoal($goal);
        });
    }

    public function updated(Goal $goal): void
    {
        $relevant = $goal->isDirty([
            'monthly_contribution',
            'contribution_account_id',
            'contribution_category_id',
            'contribution_recurrence_day',
            'completed_at',
        ]);

        if (! $relevant) {
            return;
        }

        DB::transaction(function () use ($goal) {
            $this->goalContributionService->syncRecurringForGoal($goal);
        });
    }

    public function deleted(Goal $goal): void
    {
        DB::transaction(function () use ($goal) {
            $this->goalContributionService->deactivateRecurringForGoal((int) $goal->id);
        });
    }
}
