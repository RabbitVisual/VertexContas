<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Goal;
use Modules\Core\Models\Transaction;
use Modules\Core\Services\FinancialHealthService;
use Modules\Core\Services\InspectionGuard;
use Modules\Core\Services\SubscriptionLimitService;

class CoreController extends Controller
{
    protected SubscriptionLimitService $limitService;

    protected FinancialHealthService $financialHealthService;

    public function __construct(SubscriptionLimitService $limitService, FinancialHealthService $financialHealthService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view');
        $this->limitService = $limitService;
        $this->financialHealthService = $financialHealthService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core::index');
    }

    /**
     * Display the Core financial dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get all accounts and calculate total balance
        $accounts = Account::where('user_id', $user->id)->get();
        $totalBalance = $accounts->sum('balance');

        // Get current month transactions
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyStats = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->first();

        $monthlyIncome = $monthlyStats->income ?? 0;
        $monthlyExpenses = $monthlyStats->expense ?? 0;

        $monthlyBalance = $monthlyIncome - $monthlyExpenses;

        // Calculate trends (compare with previous month)
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();

        $previousMonthStats = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$previousMonthStart->format('Y-m-d'), $previousMonthEnd->format('Y-m-d')])
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->first();

        $previousMonthIncome = $previousMonthStats->income ?? 0;
        $previousMonthExpenses = $previousMonthStats->expense ?? 0;

        $incomeTrendPercentage = $previousMonthIncome > 0
            ? (($monthlyIncome - $previousMonthIncome) / $previousMonthIncome) * 100
            : 0;

        $expenseTrendPercentage = $previousMonthExpenses > 0
            ? (($monthlyExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100
            : 0;

        // Get goals and budgets
        $goals = Goal::where('user_id', $user->id)
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get();

        $budgets = Budget::where('user_id', $user->id)
            ->with('category')
            ->get();

        // Get subscription limits for free users
        $limits = [];
        if ($user->hasRole('free_user')) {
            foreach (['income', 'expense', 'account', 'goal', 'budget'] as $entity) {
                $limits[$entity] = [
                    'current' => $this->limitService->getCurrentCount($user, $entity),
                    'limit' => $this->limitService->getLimit($user, $entity),
                ];
            }
        }

        // Prepare cash flow data for chart (last 6 months)
        $cashFlowData = $this->prepareCashFlowData($user);

        // Prepare category spending data for chart
        $categoryData = $this->prepareCategoryData($user);

        // Mask chart data when inspection active without financial permission
        $cashFlowData = InspectionGuard::maskChartData($cashFlowData);
        $categoryData = InspectionGuard::maskChartData($categoryData);

        // Recent transactions for Pro dashboard table
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Monthly capacity from recurring income (baseline) + income breakdown for Pro
        $monthlyCapacity = $this->financialHealthService->calculateMonthlyCapacity($user);
        $incomeBreakdown = $this->financialHealthService->getIncomeBreakdown($user);

        return view('core::dashboard', compact(
            'accounts',
            'totalBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'monthlyBalance',
            'incomeTrendPercentage',
            'expenseTrendPercentage',
            'goals',
            'budgets',
            'limits',
            'cashFlowData',
            'categoryData',
            'recentTransactions',
            'monthlyCapacity',
            'incomeBreakdown'
        ));
    }

    /**
     * Prepare cash flow data for the last 6 months.
     */
    private function prepareCashFlowData($user): array
    {
        $months = [];
        $income = [];
        $expenses = [];

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        $cashFlowStats = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('date', '>=', $sixMonthsAgo->format('Y-m-d'))
            ->selectRaw("substr(date, 1, 7) as month_key")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');

            $months[] = $date->translatedFormat('M');

            $stats = $cashFlowStats->get($monthKey);
            $income[] = $stats ? (float)$stats->income : 0;
            $expenses[] = $stats ? (float)$stats->expense : 0;
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Prepare category spending data for current month.
     */
    private function prepareCategoryData($user): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $categorySpending = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions) {
                return [
                    'name' => $transactions->first()->category->name ?? 'Sem categoria',
                    'total' => $transactions->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->take(8)
            ->values();

        return [
            'labels' => $categorySpending->pluck('name')->toArray(),
            'values' => $categorySpending->pluck('total')->toArray(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
