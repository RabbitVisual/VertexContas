<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\StoreGoalRequest;
use Modules\Core\Http\Requests\UpdateGoalRequest;
use Modules\Core\Models\Goal;
use Modules\Core\Services\SubscriptionLimitService;

class GoalController extends Controller
{
    protected SubscriptionLimitService $limitService;

    public function __construct(SubscriptionLimitService $limitService)
    {
        $this->limitService = $limitService;
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view')->only(['index', 'show']);
        $this->middleware('permission:core.create')->only(['create', 'store']);
    }

    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->orderBy('deadline', 'asc')
            ->get();

        return view('core::goals.index', compact('goals'));
    }

    public function create()
    {
        // Check limit before authorization
        if (! $this->limitService->canCreate(auth()->user(), 'goal')) {
            return view('core::limits.reached-goal');
        }

        $this->authorize('create', Goal::class);

        return view('core::goals.create');
    }

    public function store(StoreGoalRequest $request)
    {
        // Check limit again on store
        if (! $this->limitService->canCreate(auth()->user(), 'goal')) {
            return view('core::limits.reached-goal');
        }

        $this->authorize('create', Goal::class);

        Goal::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('core.goals.index')
            ->with('success', 'Meta criada com sucesso!');
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        return view('core::goals.edit', compact('goal'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? $goal->current_amount,
            'deadline' => $request->deadline,
        ]);

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
