<?php

declare(strict_types=1);

/**
 * Homepage PRO - Vitrine personalizada. Agrega dados financeiros e insights.
 *
 * @author Vertex Solutions LTDA
 */

namespace Modules\HomePage\Services;

use App\Models\User;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Goal;
use Modules\Core\Models\Transaction;
use Modules\Core\Services\InspectionGuard;

final class HomePageProDataService
{
    /**
     * Retorna todos os dados para a vitrine PRO na homepage.
     *
     * @return array{financialSnapshot: array, cashFlowData: array, categoryData: array, budgets: \Illuminate\Support\Collection, goals: \Illuminate\Support\Collection, accounts: \Illuminate\Support\Collection, insights: array}
     */
    public function getProHomeData(User $user): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyStats = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->first();

        $monthlyIncome = (float) ($monthlyStats->income ?? 0);
        $monthlyExpense = (float) ($monthlyStats->expense ?? 0);
        $totalBalance = Account::where('user_id', $user->id)->sum('balance');
        $monthlyBalance = $monthlyIncome - $monthlyExpense;
        $savingsRate = $monthlyIncome > 0
            ? round((($monthlyIncome - $monthlyExpense) / $monthlyIncome) * 100, 1)
            : 0;

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['income', 'expense'])
            ->where('status', 'completed')
            ->with('category')
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get();

        $financialSnapshot = [
            'total_balance' => $totalBalance,
            'monthly_income' => $monthlyIncome,
            'monthly_expense' => $monthlyExpense,
            'monthly_balance' => $monthlyBalance,
            'savings_rate' => $savingsRate,
            'recent_transactions' => $recentTransactions,
        ];

        $cashFlowData = $this->prepareCashFlowData($user);
        $categoryData = $this->prepareCategoryData($user);
        $cashFlowData = InspectionGuard::maskChartData($cashFlowData);
        $categoryData = InspectionGuard::maskChartData($categoryData);

        $budgets = Budget::where('user_id', $user->id)
            ->with('category')
            ->get();

        $goals = Goal::where('user_id', $user->id)
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get();

        $accounts = Account::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        $insights = $this->buildInsights(
            $budgets,
            $monthlyBalance,
            $monthlyIncome,
            $monthlyExpense,
            $savingsRate,
            $goals,
            $categoryData
        );

        return [
            'financialSnapshot' => $financialSnapshot,
            'cashFlowData' => $cashFlowData,
            'categoryData' => $categoryData,
            'budgets' => $budgets,
            'goals' => $goals,
            'accounts' => $accounts,
            'insights' => $insights,
        ];
    }

    /**
     * Fluxo de caixa - últimos 6 meses.
     */
    private function prepareCashFlowData(User $user): array
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
            $income[] = $stats ? (float) $stats->income : 0;
            $expenses[] = $stats ? (float) $stats->expense : 0;
        }

        return [
            'months' => $months,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Gastos por categoria do mês atual.
     */
    private function prepareCategoryData(User $user): array
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
     * Constrói insights acionáveis.
     */
    private function buildInsights(
        $budgets,
        float $monthlyBalance,
        float $monthlyIncome,
        float $monthlyExpense,
        float $savingsRate,
        $goals,
        array $categoryData
    ): array {
        $budgetExceeded = [];
        $budgetWarning = [];
        $monthlyDeficit = $monthlyBalance < 0 ? abs($monthlyBalance) : null;
        $savingsTip = null;
        $topGoal = null;
        $topCategory = null;

        foreach ($budgets as $budget) {
            $name = $budget->category->name ?? 'Categoria';
            if ($budget->is_exceeded) {
                $overBy = $budget->spent_amount - $budget->limit_amount;
                $pct = $budget->limit_amount > 0
                    ? round((($budget->spent_amount / $budget->limit_amount) - 1) * 100, 1)
                    : 0;
                $budgetExceeded[] = [
                    'name' => $name,
                    'over_by' => $overBy,
                    'limit' => (float) $budget->limit_amount,
                    'pct_over' => $pct,
                ];
            } elseif ($budget->limit_amount > 0) {
                $usage = $budget->usage_percentage;
                $threshold = $budget->alert_threshold ?? 80;
                if ($usage >= $threshold) {
                    $budgetWarning[] = [
                        'name' => $name,
                        'usage_pct' => round($usage, 1),
                    ];
                }
            }
        }

        if ($savingsRate < 10 && $monthlyIncome > 0) {
            $savingsTip = sprintf(
                'Sua taxa de economia está em %s%%. Considere aumentar para 10%% ou mais.',
                number_format($savingsRate, 1, ',', '.')
            );
        } elseif ($savingsRate >= 20 && $savingsRate < 30) {
            $savingsTip = sprintf(
                'Ótimo! Sua taxa de economia está em %s%%. O ideal é chegar a 30%% ou mais.',
                number_format($savingsRate, 1, ',', '.')
            );
        }

        $goalsNotCompleted = $goals->filter(fn ($g) => ! $g->is_completed);
        if ($goalsNotCompleted->isNotEmpty()) {
            $closest = $goalsNotCompleted->sortByDesc('progress_percentage')->first();
            $topGoal = [
                'name' => $closest->name,
                'pct' => round($closest->progress_percentage, 1),
            ];
        }

        if (! empty($categoryData['labels']) && ! empty($categoryData['values'])) {
            $topCategory = [
                'name' => $categoryData['labels'][0],
                'amount' => (float) $categoryData['values'][0],
            ];
        }

        return [
            'budget_exceeded' => $budgetExceeded,
            'budget_warning' => $budgetWarning,
            'monthly_deficit' => $monthlyDeficit,
            'savings_tip' => $savingsTip,
            'top_goal' => $topGoal,
            'top_category' => $topCategory,
        ];
    }
}
