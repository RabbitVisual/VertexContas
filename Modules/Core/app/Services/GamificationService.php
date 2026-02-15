<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Transaction;
use Modules\Gamification\Models\Achievement;
use Modules\Gamification\Models\Insight;
use Modules\Gamification\Services\RuleEngineService;

class GamificationService
{
    public function __construct(
        protected FinancialHealthService $financialHealth,
        protected ReportService $reportService,
        protected RuleEngineService $ruleEngine
    ) {}

    /**
     * Analyze user and return insight for Vertex Bot (if any) plus financial score, metrics, and coaching_stats.
     *
     * @return array{insight: array{content: string, level: string, trigger: string}|null, financial_score: int, metrics: array, coaching_stats: array|null}
     */
    public function analyzeUser(User $user): array
    {
        if (! ($user->show_assistant ?? true)) {
            return [
                'insight' => null,
                'financial_score' => 0,
                'metrics' => [],
                'coaching_stats' => null,
            ];
        }

        $snapshot = $this->financialHealth->getUserFinancialSnapshot($user);
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $summary = $this->reportService->getIncomeExpenseSummary($user, $startOfMonth, $endOfMonth);

        $income = (float) $summary['income'];
        $expense = (float) $summary['expense'];
        $balance = (float) $snapshot['account_balance'];
        $monthlyExpenses = (float) $snapshot['monthly_expenses'];

        $financialScore = $this->calculateFinancialScore($user);
        $metrics = [
            'income' => $income,
            'expense' => $expense,
            'savings_rate' => $summary['savings_rate'],
            'account_balance' => $balance,
        ];

        $coachingStats = $this->financialHealth->get503020Breakdown($user, $startOfMonth, $endOfMonth);

        $insight = $this->ruleEngine->evaluate($user);
        if ($insight === null) {
            $insight = $this->resolveInsight($user, $snapshot, $summary, $income, $expense, $balance, $monthlyExpenses);
        }

        return [
            'insight' => $insight,
            'financial_score' => $financialScore,
            'metrics' => $metrics,
            'coaching_stats' => $coachingStats,
        ];
    }

    /**
     * Resolve which insight to show (priority: low_balance → budget_reached → savings_milestone → daily_tip).
     */
    protected function resolveInsight(
        User $user,
        array $snapshot,
        array $summary,
        float $income,
        float $expense,
        float $balance,
        float $monthlyExpenses
    ): ?array {
        $monthKey = now()->format('Y-m');
        $todayKey = now()->toDateString();
        $dismissed = session('vertex_bot_dismissed', []);
        $isPro = $user->isPro();

        // 1. low_balance: account_balance < (monthly_expenses * 0.5)
        if ($monthlyExpenses > 0 && $balance < ($monthlyExpenses * 0.5)) {
            $achievementKey = "low_balance_{$monthKey}";
            if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfMonth())) {
                $insight = Insight::getRandomForTrigger('low_balance', $isPro);
                if ($insight) {
                    $this->recordAchievement($user, $achievementKey);

                    return [
                        'content' => $insight->content,
                        'level' => $insight->level ?: 'danger',
                        'trigger' => 'low_balance',
                        'insight_key' => $achievementKey,
                    ];
                }
            }
        }

        // 2. budget_reached: any budget usage >= alert_threshold
        $budgets = Budget::where('user_id', $user->id)->with('category')->get();
        foreach ($budgets as $budget) {
            $threshold = (int) ($budget->alert_threshold ?? 80);
            if ($budget->usage_percentage < $threshold) {
                continue;
            }
            $achievementKey = "budget_warning_{$budget->id}_{$monthKey}";
            if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfMonth())) {
                $insight = Insight::getRandomForTrigger('budget_reached', $isPro);
                if ($insight) {
                    $this->recordAchievement($user, $achievementKey, [
                        'budget_id' => $budget->id,
                        'category' => $budget->category?->name,
                        'percent' => round($budget->usage_percentage, 1),
                    ]);
                    $content = $this->replacePlaceholders($insight->content, [
                        'category' => $budget->category?->name ?? 'Orçamento',
                        'percent' => round($budget->usage_percentage, 1),
                    ]);
                    $level = $budget->is_exceeded ? 'danger' : ($insight->level ?: 'warning');

                    return [
                        'content' => $content,
                        'level' => $level,
                        'trigger' => 'budget_reached',
                        'insight_key' => $achievementKey,
                    ];
                }
            }
        }

        // 3. savings_milestone: (expense / income) < 0.5 and income > 0
        if ($income > 0 && ($expense / $income) < 0.5) {
            $achievementKey = "savings_milestone_{$monthKey}";
            if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfMonth())) {
                $insight = Insight::getRandomForTrigger('savings_milestone', $isPro);
                if ($insight) {
                    $this->recordAchievement($user, $achievementKey);

                    return [
                        'content' => $insight->content,
                        'level' => $insight->level ?: 'success',
                        'trigger' => 'savings_milestone',
                        'insight_key' => $achievementKey,
                    ];
                }
            }
        }

        // 4. daily_tip: first login today (show once per day)
        $achievementKey = "daily_tip_{$todayKey}";
        if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfDay())) {
            $insight = Insight::getRandomForTrigger('daily_tip', $isPro);
            if ($insight) {
                $this->recordAchievement($user, $achievementKey);

                return [
                    'content' => $insight->content,
                    'level' => $insight->level ?: 'info',
                    'trigger' => 'daily_tip',
                    'insight_key' => $achievementKey,
                ];
            }
        }

        return null;
    }

    /**
     * Calculate financial score 0–100 (savings rate, budget adherence, reserve, consistency).
     */
    public function calculateFinancialScore(User $user): int
    {
        $snapshot = $this->financialHealth->getUserFinancialSnapshot($user);
        $income = (float) $snapshot['monthly_income'];
        $expense = (float) $snapshot['monthly_expenses'];
        $balance = (float) $snapshot['account_balance'];

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $summary = $this->reportService->getIncomeExpenseSummary($user, $startOfMonth, $endOfMonth);
        $actualIncome = (float) $summary['income'];
        $actualExpense = (float) $summary['expense'];

        // 1. Savings rate score (0–35)
        $savingsRate = $actualIncome > 0 ? (($actualIncome - $actualExpense) / $actualIncome) * 100 : 0;
        $savingsScore = min(35, max(0, ($savingsRate / 100) * 35));

        // 2. Budget adherence (0–25)
        $budgets = Budget::where('user_id', $user->id)->get();
        $budgetScore = $budgets->isEmpty()
            ? 25.0
            : (float) $budgets->avg(fn (Budget $b) => max(0, 25 - ($b->usage_percentage / 100) * 25));

        // 3. Reserve ratio (0–25): 6 months of reserve = 100%
        $reserveMonths = $expense > 0 ? $balance / $expense : 6.0;
        $reserveScore = min(25.0, ($reserveMonths / 6) * 25);

        // 4. Consistency (0–15): unique days with transactions in last 30 days
        $daysWithTx = (int) Transaction::where('user_id', $user->id)
            ->where('date', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(date)'))
            ->count();
        $consistencyScore = min(15.0, ($daysWithTx / 30) * 15);

        return (int) round($savingsScore + $budgetScore + $reserveScore + $consistencyScore);
    }

    protected function recordAchievement(User $user, string $key, array $metadata = []): void
    {
        Achievement::create([
            'user_id' => $user->id,
            'achievement_key' => $key,
            'triggered_at' => now(),
            'metadata' => $metadata ?: null,
        ]);
    }

    protected function replacePlaceholders(string $content, array $replace): string
    {
        foreach ($replace as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', (string) $value, $content);
            $content = str_replace('{{' . $key . '}}', (string) $value, $content);
        }

        return $content;
    }
}
