<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Goal;
use Modules\Core\Models\Transaction;

class SubscriptionLimitService
{
    /**
     * Subscription limits for free users.
     */
    private const FREE_LIMITS = [
        'income' => 5,
        'expense' => 5,
        'goal' => 1,
        'budget' => 1,
        'account' => 1,
        'category' => 0, // Custom categories
    ];

    /**
     * Default limits for PRO when pro_has_limits=1 but limit_pro_* is missing or invalid.
     */
    private const PRO_LIMITS_DEFAULT = [
        'account' => 50,
        'income' => 5000,
        'expense' => 5000,
        'goal' => 15,
        'budget' => 20,
        'category' => 50,
    ];

    /**
     * Check if user can create a new entity.
     */
    public function canCreate(User $user, string $entity): bool
    {
        $limit = $this->getLimit($user, $entity);
        if ($limit === 'unlimited') {
            return true;
        }

        $currentCount = $this->getCurrentCount($user, $entity);

        return $currentCount < $limit;
    }

    /**
     * Get the current count of entities for a user.
     */
    public function getCurrentCount(User $user, string $entity): int
    {
        return match ($entity) {
            'income' => Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->count(),
            'expense' => Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->count(),
            'goal' => Goal::where('user_id', $user->id)->count(),
            'budget' => Budget::where('user_id', $user->id)->count(),
            'account' => Account::where('user_id', $user->id)->count(),
            'category' => \Modules\Core\Models\Category::where('user_id', $user->id)->count(),
            default => 0,
        };
    }

    /**
     * Get the limit for an entity based on user role.
     *
     * @return int|string Returns 'unlimited' for pro when pro_has_limits=0 or limit < 0, int otherwise
     */
    public function getLimit(User $user, string $entity): int|string
    {
        $settings = app(SettingService::class);

        if ($user->isPro()) {
            $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
            if (! $proHasLimits) {
                return 'unlimited';
            }

            $proLimit = (int) $settings->get("limit_pro_{$entity}", -1);
            if ($proLimit < 0) {
                return (int) (self::PRO_LIMITS_DEFAULT[$entity] ?? 0);
            }

            return $proLimit;
        }

        return (int) $settings->get("limit_free_{$entity}", self::FREE_LIMITS[$entity] ?? 0);
    }

    /**
     * Get remaining count before hitting limit.
     */
    public function getRemainingCount(User $user, string $entity): int|string
    {
        $limit = $this->getLimit($user, $entity);

        if ($limit === 'unlimited') {
            return 'unlimited';
        }

        $currentCount = $this->getCurrentCount($user, $entity);

        return max(0, $limit - $currentCount);
    }

    /**
     * Get a user-friendly error message when limit is reached.
     */
    public function getLimitReachedMessage(string $entity): string
    {
        $entityNames = [
            'income' => 'receitas',
            'expense' => 'despesas',
            'goal' => 'metas',
            'budget' => 'orçamentos',
            'account' => 'contas',
            'category' => 'categorias personalizadas',
        ];

        $entityName = $entityNames[$entity] ?? $entity;
        $planProName = (string) app(SettingService::class)->get('plan_pro_name', 'Vertex PRO');

        return "Limite de {$entityName} atingido! Migre para o plano {$planProName} para cadastros ilimitados.";
    }

    /**
     * Get usage statistics for a user and entity.
     *
     * @return array{current: int, limit: int|string, percentage: int}
     */
    public function getUsageStats(User $user, string $entity): array
    {
        $current = $this->getCurrentCount($user, $entity);
        $limit = $this->getLimit($user, $entity); // Logic for unlimited is handled inside getLimit

        if ($limit === 'unlimited') {
            return [
                'current' => $current,
                'limit' => 'unlimited',
                'limit_display' => 'Ilimitado',
                'percentage' => 0,
            ];
        }

        $percentage = $limit > 0 ? (int) round(($current / $limit) * 100) : 100;

        return [
            'current' => $current,
            'limit' => $limit,
            'limit_display' => (string) $limit,
            'percentage' => min(100, $percentage),
        ];
    }

    /**
     * Check if user reached a limit threshold and notify them.
     */
    public function checkAndNotify(User $user, string $entity): void
    {
        $stats = $this->getUsageStats($user, $entity);
        $percentage = $stats['percentage'];

        // Only for free users (or those with limits)
        if ($stats['limit'] === 'unlimited') {
            return;
        }

        $notificationService = app(\Modules\Notifications\Services\NotificationService::class);
        $settings = app(SettingService::class);
        $cacheKey = "limit_notify_{$user->id}_{$entity}";
        $planProName = (string) $settings->get('plan_pro_name', 'Vertex PRO');

        if ($percentage >= 100) {
            if (! \Illuminate\Support\Facades\Cache::has("{$cacheKey}_100")) {
                $notificationService->sendToUser(
                    $user,
                    "Limite de {$entity} Atingido!",
                    "Você atingiu 100% do seu limite de {$entity}. Faça upgrade para {$planProName} para remover os limites.",
                    'danger',
                    route('user.subscription.index')
                );
                \Illuminate\Support\Facades\Cache::put("{$cacheKey}_100", true, now()->addDay());
            }
        } elseif ($percentage >= 80) {
            if (! \Illuminate\Support\Facades\Cache::has("{$cacheKey}_80")) {
                $notificationService->sendToUser(
                    $user,
                    "Limite de {$entity} Próximo",
                    "Você já usou {$percentage}% do seu limite de {$entity}. Considere fazer upgrade para {$planProName}.",
                    'warning',
                    route('user.subscription.index')
                );
                \Illuminate\Support\Facades\Cache::put("{$cacheKey}_80", true, now()->addDay());
            }
        }
    }
}
