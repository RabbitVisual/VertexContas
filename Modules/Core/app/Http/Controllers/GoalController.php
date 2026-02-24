<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\StoreGoalRequest;
use Modules\Core\Http\Requests\UpdateGoalRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Category;
use Modules\Core\Models\Goal;
use Modules\Core\Services\GoalContributionService;
use Modules\Core\Services\SubscriptionLimitService;

class GoalController extends Controller
{
    public function __construct(
        protected SubscriptionLimitService $limitService,
        protected GoalContributionService $goalContributionService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view')->only(['index', 'show']);
        $this->middleware('permission:core.create')->only(['create', 'store']);
    }

    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->orderByRaw('completed_at IS NOT NULL')
            ->orderBy('deadline', 'asc')
            ->get();

        return view('core::goals.index', compact('goals'));
    }

    public function create()
    {
        if (! $this->limitService->canCreate(auth()->user(), 'goal')) {
            return view('core::limits.reached-goal');
        }

        $this->authorize('create', Goal::class);

        $accounts = Account::where('user_id', auth()->id())->orderBy('name')->get();
        $expenseCategories = Category::forUser(auth()->user())->where('type', 'expense')->orderBy('name')->get();

        return view('core::goals.create', compact('accounts', 'expenseCategories'));
    }

    public function store(StoreGoalRequest $request)
    {
        if (! $this->limitService->canCreate(auth()->user(), 'goal')) {
            return view('core::limits.reached-goal');
        }

        $this->authorize('create', Goal::class);

        $goal = Goal::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'deadline' => $request->deadline,
            'monthly_contribution' => $request->monthly_contribution ?: null,
            'contribution_account_id' => $request->contribution_account_id,
            'contribution_category_id' => $request->contribution_category_id,
            'contribution_recurrence_day' => $request->contribution_recurrence_day ?: null,
        ]);

        $this->goalContributionService->syncRecurringForGoal($goal);

        return redirect()->route('core.goals.index')
            ->with('success', 'Meta criada com sucesso!');
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        $accounts = Account::where('user_id', auth()->id())->orderBy('name')->get();
        $expenseCategories = Category::forUser(auth()->user())->where('type', 'expense')->orderBy('name')->get();

        return view('core::goals.edit', compact('goal', 'accounts', 'expenseCategories'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? $goal->current_amount,
            'deadline' => $request->deadline,
            'monthly_contribution' => $request->monthly_contribution ?: null,
            'contribution_account_id' => $request->contribution_account_id,
            'contribution_category_id' => $request->contribution_category_id,
            'contribution_recurrence_day' => $request->contribution_recurrence_day ?: null,
        ]);

        $this->goalContributionService->syncRecurringForGoal($goal);

        return redirect()->route('core.goals.index')
            ->with('success', 'Meta atualizada com sucesso!');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return redirect()->route('core.goals.index')
            ->with('success', 'Meta excluída com sucesso!');
    }
}
