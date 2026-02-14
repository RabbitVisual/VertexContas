<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // 1. Total Balance
        $totalBalance = \Modules\Core\Models\Account::where('user_id', $user->id)->sum('balance');

        // 2. Monthly Income & Expenses
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyIncome = \Modules\Core\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyExpense = \Modules\Core\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // 3. Top 2 Goals
        $goals = \Modules\Core\Models\Goal::where('user_id', $user->id)
            ->orderBy('deadline', 'asc')
            ->take(2)
            ->get();

        // 4. Cash Flow Chart Data (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }


        // Re-implementing Cash Flow for DB Agnosticism (safer)
        $cashFlowData = \Modules\Core\Models\Transaction::where('user_id', $user->id)
            ->where('date', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->date)->format('Y-m');
            });

        $chartLabels = $months->map(fn($m) => $m->format('M/Y'));
        $incomeData = $months->map(function($m) use ($cashFlowData) {
            $key = $m->format('Y-m');
            return $cashFlowData->has($key) ? $cashFlowData[$key]->where('type', 'income')->sum('amount') : 0;
        });
        $expenseData = $months->map(function($m) use ($cashFlowData) {
            $key = $m->format('Y-m');
            return $cashFlowData->has($key) ? $cashFlowData[$key]->where('type', 'expense')->sum('amount') : 0;
        });

        // 5. Spending by Category (Current Month)
        $spendingByCategory = \Modules\Core\Models\Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions) {
                $first = $transactions->first();
                return [
                    'name' => $first->category->name ?? 'Sem Categoria',
                    'color' => $first->category->color ?? '#94a3b8',
                    'total' => $transactions->sum('amount')
                ];
            })->values();

        // 6. Recent Transactions
        $recentTransactions = \Modules\Core\Models\Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('paneluser::index', compact(
            'totalBalance',
            'monthlyIncome',
            'monthlyExpense',
            'goals',
            'user',
            'chartLabels',
            'incomeData',
            'expenseData',
            'spendingByCategory',
            'recentTransactions'
        ));
    }

    public function completeOnboarding()
    {
        $user = auth()->user();
        $user->onboarding_completed = true;
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Dismiss sidebar CTA (free users only). Reappears on next login (new session).
     */
    public function dismissSidebarCta(Request $request)
    {
        $user = auth()->user();
        if ($user->isPro()) {
            return response()->json(['success' => false], 403);
        }

        session(['sidebar_cta_dismissed' => true]);

        return $request->wantsJson()
            ? response()->json(['success' => true])
            : back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paneluser::create');
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
        return view('paneluser::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paneluser::edit');
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
