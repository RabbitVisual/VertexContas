<?php

declare(strict_types=1);

namespace Modules\Gamification\Services;

use App\Models\User;
use Carbon\Carbon;
use Modules\Core\Services\FinancialHealthService;
use Modules\Gamification\Models\Achievement;
use Modules\Gamification\Models\CoachingRule;
use Modules\Gamification\Models\UserMedal;

class RuleEngineService
{
    public function __construct(
        protected FinancialHealthService $financialHealth
    ) {}

    /**
     * Evaluate coaching rules and return first matching insight, or null.
     *
     * @return array{content: string, level: string, trigger: string, insight_key: string, medal?: array}|null
     */
    public function evaluate(User $user): ?array
    {
        $dismissed = session('vertex_bot_dismissed', []);
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $breakdown = $this->financialHealth->get503020Breakdown($user, $start, $end);
        $reserveMonths = $this->financialHealth->getReserveMonths($user);
        $consecutiveDays = $this->financialHealth->getConsecutiveTransactionDays($user, 30);

        $rules = CoachingRule::with(['medal', 'insight'])
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->get();

        foreach ($rules as $rule) {
            $achievementKey = $rule->trigger_key . '_' . now()->format('Y-m');
            if (in_array($achievementKey, $dismissed, true)) {
                continue;
            }
            if (Achievement::hasAchieved($user, $achievementKey, now()->startOfMonth())) {
                continue;
            }

            $matches = $this->evaluateCondition($rule, $breakdown, $reserveMonths, $consecutiveDays);
            if (!$matches) {
                continue;
            }

            $content = $rule->message_override ?? $rule->insight?->content ?? '';
            if ($content === '') {
                continue;
            }

            $this->recordAchievement($user, $achievementKey);

            $medalPayload = null;
            if ($rule->medal_id && $rule->medal) {
                if (!UserMedal::hasUnlocked($user, $rule->medal_id)) {
                    UserMedal::create([
                        'user_id' => $user->id,
                        'medal_id' => $rule->medal_id,
                        'unlocked_at' => now(),
                    ]);
                }
                $medalPayload = [
                    'title' => $rule->medal->title,
                    'icon_name' => $rule->medal->icon_name,
                    'color' => $rule->medal->color,
                ];
            }

            $result = [
                'content' => $content,
                'level' => $rule->level ?: 'info',
                'trigger' => $rule->trigger_key,
                'insight_key' => $achievementKey,
            ];

            if ($medalPayload) {
                $result['medal'] = $medalPayload;
            }

            return $result;
        }

        return null;
    }

    protected function evaluateCondition(
        CoachingRule $rule,
        array $breakdown,
        float $reserveMonths,
        int $consecutiveDays
    ): bool {
        $params = $rule->condition_params ?? [];
        $operator = $params['operator'] ?? '>=';
        $value = (float) ($params['value'] ?? 0);

        $operatorMap = [
            '>' => 'gt',
            '>=' => 'gte',
            '<' => 'lt',
            '<=' => 'lte',
            'gt' => 'gt',
            'gte' => 'gte',
            'lt' => 'lt',
            'lte' => 'lte',
        ];
        $op = $operatorMap[$operator] ?? 'gte';

        switch ($rule->condition_type) {
            case 'pillar_threshold':
                $pillar = $params['pillar'] ?? 'essential';
                $key = $pillar . '_pct';
                $actual = (float) ($breakdown[$key] ?? 0);
                return $this->compare($actual, $value, $op);
            case 'reserve_months':
                return $this->compare($reserveMonths, $value, $op);
            case 'consecutive_days':
                return $this->compare((float) $consecutiveDays, $value, $op);
            default:
                return false;
        }
    }

    protected function compare(float $actual, float $expected, string $op): bool
    {
        return match ($op) {
            'gt' => $actual > $expected,
            'gte' => $actual >= $expected,
            'lt' => $actual < $expected,
            'lte' => $actual <= $expected,
            default => $actual >= $expected,
        };
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
}
