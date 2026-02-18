<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Models\Post;
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
        protected RuleEngineService $ruleEngine,
        protected SettingService $settingService,
        protected GeminiService $geminiService
    ) {}

    /**
     * Analyze user and return insight for Vertex Bot (if any) plus financial score, metrics, and coaching_stats.
     * When $routeName is provided, contextual tips for the current page (Metas, Categorias, Relatórios, etc.) are preferred.
     *
     * @return array{insight: array{content: string, level: string, trigger: string}|null, financial_score: int, metrics: array, coaching_stats: array|null}
     */
    public function analyzeUser(User $user, ?string $routeName = null): array
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
            $insight = $this->resolveInsight($user, $snapshot, $summary, $income, $expense, $balance, $monthlyExpenses, $financialScore, $metrics, $coachingStats, $routeName);
        }

        if ($insight !== null && ! $user->isPro() && ! empty($insight['content'] ?? null)) {
            $insight['content'] = rtrim($insight['content']) . "\n\nNo plano PRO você teria projeção de saldo, relatórios avançados e suporte prioritário.";
        }

        return [
            'insight' => $insight,
            'financial_score' => $financialScore,
            'metrics' => $metrics,
            'coaching_stats' => $coachingStats,
        ];
    }

    /**
     * Map route name to page context for contextual Mentor tips (Metas, Categorias, Relatórios, etc.).
     */
    protected static function getPageSectionFromRoute(?string $routeName): ?string
    {
        if ($routeName === null || $routeName === '') {
            return null;
        }
        if (str_starts_with($routeName, 'core.goals')) {
            return 'goals';
        }
        if (str_starts_with($routeName, 'core.categories')) {
            return 'categories';
        }
        if (str_starts_with($routeName, 'core.reports')) {
            return 'reports';
        }
        if (str_starts_with($routeName, 'core.budgets')) {
            return 'budgets';
        }
        if (str_starts_with($routeName, 'core.income')) {
            return 'income';
        }
        if (str_starts_with($routeName, 'core.transactions')) {
            return 'transactions';
        }
        if (str_starts_with($routeName, 'user.tickets')) {
            return 'tickets';
        }
        if ($routeName === 'paneluser.index' || $routeName === 'core.dashboard') {
            return 'dashboard';
        }

        return null;
    }

    /**
     * Resolve which insight to show (priority: low_balance → budget_reached → savings_milestone → page_context → daily_tip).
     * Tries Gemini AI when enabled; falls back to local insights on failure or when disabled.
     */
    protected function resolveInsight(
        User $user,
        array $snapshot,
        array $summary,
        float $income,
        float $expense,
        float $balance,
        float $monthlyExpenses,
        int $financialScore,
        array $metrics,
        array $coachingStats,
        ?string $routeName = null
    ): ?array {
        $monthKey = now()->format('Y-m');
        $todayKey = now()->toDateString();
        $dismissed = session('vertex_bot_dismissed', []);
        $isPro = $user->isPro();

        // 1. low_balance: account_balance < (monthly_expenses * 0.5)
        if ($monthlyExpenses > 0 && $balance < ($monthlyExpenses * 0.5)) {
            $achievementKey = "low_balance_{$monthKey}";
            if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfMonth())) {
                $content = $this->resolveInsightContent('low_balance', $user, $financialScore, $metrics, $coachingStats, $isPro, null);
                if ($content) {
                    $this->recordAchievement($user, $achievementKey);

                    return [
                        'content' => $content,
                        'level' => 'danger',
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
                $triggerExtra = [
                    'category' => $budget->category?->name ?? 'Orçamento',
                    'percent' => round($budget->usage_percentage, 1),
                ];
                $content = $this->resolveInsightContent('budget_reached', $user, $financialScore, $metrics, $coachingStats, $isPro, $triggerExtra);
                if ($content) {
                    $this->recordAchievement($user, $achievementKey, [
                        'budget_id' => $budget->id,
                        'category' => $budget->category?->name,
                        'percent' => round($budget->usage_percentage, 1),
                    ]);
                    $level = $budget->is_exceeded ? 'danger' : 'warning';

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
                $content = $this->resolveInsightContent('savings_milestone', $user, $financialScore, $metrics, $coachingStats, $isPro, null);
                if ($content) {
                    $this->recordAchievement($user, $achievementKey);

                    return [
                        'content' => $content,
                        'level' => 'success',
                        'trigger' => 'savings_milestone',
                        'insight_key' => $achievementKey,
                    ];
                }
            }
        }

        // 3.5. page_context: dica contextual da página atual (Metas, Categorias, Relatórios, Orçamentos, Minha Renda, etc.)
        $pageSection = self::getPageSectionFromRoute($routeName);
        if ($pageSection !== null) {
            $trigger = "page_{$pageSection}";
            $achievementKey = "{$trigger}_{$todayKey}";
            if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfDay())) {
                $content = $this->resolveInsightContent($trigger, $user, $financialScore, $metrics, $coachingStats, $isPro, null);
                if ($content) {
                    $this->recordAchievement($user, $achievementKey);

                    return [
                        'content' => $content,
                        'level' => 'info',
                        'trigger' => $trigger,
                        'insight_key' => $achievementKey,
                    ];
                }
            }
        }

        // 4. daily_tip: low score = more tips (every 4h), mid = every 8h, high = once/day
        $slot = $this->getDailyTipSlot($financialScore);
        $achievementKey = "daily_tip_{$todayKey}" . ($slot !== null ? "_{$slot}" : '');
        if (! in_array($achievementKey, $dismissed, true) && ! Achievement::hasAchieved($user, $achievementKey, now()->startOfDay())) {
            $content = $this->resolveInsightContent('daily_tip', $user, $financialScore, $metrics, $coachingStats, $isPro, null);
            if ($content) {
                $this->recordAchievement($user, $achievementKey);

                return [
                    'content' => $content,
                    'level' => 'info',
                    'trigger' => 'daily_tip',
                    'insight_key' => $achievementKey,
                ];
            }
        }

        return null;
    }

    /**
     * Resolve insight content: try Gemini when enabled, fallback to local insights (Plano B).
     *
     * @param  array{category?: string, percent?: float}|null  $triggerExtra
     */
    protected function resolveInsightContent(
        string $trigger,
        User $user,
        int $financialScore,
        array $metrics,
        array $coachingStats,
        bool $isPro,
        ?array $triggerExtra
    ): ?string {
        $useGemini = (bool) ($this->settingService->get('gemini_enabled') ?? false) && $this->geminiService->isAvailable();

        if ($useGemini) {
            $contextData = $this->buildPromptContext($financialScore, $metrics, $coachingStats, $triggerExtra);
            $content = $this->geminiService->generateInsight($contextData, $trigger, $isPro);
            if ($content !== null && trim($content) !== '') {
                return trim($content);
            }
        }

        $insight = Insight::getRandomForTrigger($trigger, $isPro);
        if (! $insight) {
            return null;
        }

        return $this->replacePlaceholders($insight->content, $triggerExtra ?? []);
    }

    /**
     * Build context data for Gemini prompt. LGPD: metrics only, no PII.
     *
     * @param  array{category?: string, percent?: float}|null  $triggerExtra
     * @return array{financial_score: int, coaching_stats: array, trigger_extra?: array, metrics: array, blog_titles: array}
     */
    protected function buildPromptContext(int $financialScore, array $metrics, array $coachingStats, ?array $triggerExtra): array
    {
        $blogTitles = [];
        if (class_exists(Post::class)) {
            $blogTitles = Post::query()
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->pluck('title')
                ->values()
                ->toArray();
        }

        $data = [
            'financial_score' => $financialScore,
            'coaching_stats' => $coachingStats,
            'metrics' => $metrics,
            'blog_titles' => $blogTitles,
        ];

        if ($triggerExtra !== null && $triggerExtra !== []) {
            $data['trigger_extra'] = $triggerExtra;
        }

        return $data;
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

    /**
     * Daily tip slot for low-score users: more frequent tips when score is low.
     * Score <= 40: slot every 4h (0-5). Score 41-70: slot every 8h (0-2). Score > 70: null (once/day).
     */
    protected function getDailyTipSlot(int $financialScore): ?int
    {
        $hour = (int) now()->format('H');
        if ($financialScore <= 40) {
            return (int) floor($hour / 4);
        }
        if ($financialScore <= 70) {
            return (int) floor($hour / 8);
        }

        return null;
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
