<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Requests\StoreBudgetRequest;
use Modules\Core\Http\Requests\UpdateBudgetRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Category;
use Modules\Core\Services\SubscriptionLimitService;

class BudgetController extends Controller
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
        $budgets = Budget::where('user_id', auth()->id())
            ->with('category')
            ->get();

        return view('core::budgets.index', compact('budgets'));
    }

    public function create()
    {
        // Check limit before authorization
        if (! $this->limitService->canCreate(auth()->user(), 'budget')) {
            return view('core::limits.reached-budget');
        }

        $this->authorize('create', Budget::class);
        $categories = Category::forUser(auth()->user())->get();
        $accounts = Account::where('user_id', auth()->id())->orderBy('name')->get();

        return view('core::budgets.create', compact('categories', 'accounts'));
    }

    public function store(StoreBudgetRequest $request)
    {
        if (! $this->limitService->canCreate(auth()->user(), 'budget')) {
            return view('core::limits.reached-budget');
        }

        $this->authorize('create', Budget::class);

        $isPro = auth()->user()?->isPro() ?? false;
        $isRecurring = $request->boolean('is_recurring', true);
        $periodStart = null;
        if (! $isRecurring && $request->filled('period_start')) {
            $periodStart = \Carbon\Carbon::parse($request->period_start)->startOfMonth()->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $isPro, $isRecurring, $periodStart) {
            Budget::create([
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'account_id' => $request->account_id ?: null,
                'limit_amount' => $request->limit_amount,
                'period' => $request->period,
                'is_recurring' => $isRecurring,
                'period_start' => $periodStart,
                'alert_threshold' => $isPro ? $request->alert_threshold : 80,
                'allow_exceed' => $isPro ? $request->boolean('allow_exceed') : true,
            ]);
        });

        return redirect()->route('core.budgets.index')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);
        $categories = Category::forUser(auth()->user())->get();
        $accounts = Account::where('user_id', auth()->id())->orderBy('name')->get();

        return view('core::budgets.edit', compact('budget', 'categories', 'accounts'));
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $isPro = auth()->user()?->isPro() ?? false;
        $isRecurring = $request->boolean('is_recurring', true);
        $periodStart = null;
        if (! $isRecurring && $request->filled('period_start')) {
            $periodStart = \Carbon\Carbon::parse($request->period_start)->startOfMonth()->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $budget, $isPro, $isRecurring, $periodStart) {
            $budget->update([
                'category_id' => $request->category_id,
                'account_id' => $request->account_id ?: null,
                'limit_amount' => $request->limit_amount,
                'period' => $request->period,
                'is_recurring' => $isRecurring,
                'period_start' => $periodStart,
                'alert_threshold' => $isPro ? $request->alert_threshold : $budget->alert_threshold,
                'allow_exceed' => $isPro ? $request->boolean('allow_exceed') : $budget->allow_exceed,
            ]);
        });

        return redirect()->route('core.budgets.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('core.budgets.index')
            ->with('success', 'Orçamento excluído com sucesso!');
    }
}
