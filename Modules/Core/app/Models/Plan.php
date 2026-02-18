<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    public const BILLING_MONTHLY = 'monthly';

    public const BILLING_YEARLY = 'yearly';

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'billing_interval',
        'is_free',
        'limit_account',
        'limit_income',
        'limit_expense',
        'limit_goal',
        'limit_budget',
        'limit_category',
        'sort_order',
        'is_active',
        'stripe_price_id',
        'mercadopago_plan_id',
        'amount',
        'currency',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'limit_account' => 'integer',
        'limit_income' => 'integer',
        'limit_expense' => 'integer',
        'limit_goal' => 'integer',
        'limit_budget' => 'integer',
        'limit_category' => 'integer',
        'sort_order' => 'integer',
        'amount' => 'decimal:2',
    ];

    /**
     * Entity keys that have a limit column on the plan.
     */
    public static function limitEntities(): array
    {
        return ['account', 'income', 'expense', 'goal', 'budget', 'category'];
    }

    /**
     * Get limit for an entity. Returns (int) or 'unlimited' when -1 or null.
     */
    public function getLimit(string $entity): int|string
    {
        $key = 'limit_' . $entity;
        if (! in_array($entity, self::limitEntities(), true)) {
            return 0;
        }
        $value = $this->{$key};
        if ($value === null || $value === -1) {
            return 'unlimited';
        }
        return (int) $value;
    }

    /**
     * Whether the plan has unlimited usage for the given entity.
     */
    public function isUnlimited(string $entity): bool
    {
        return $this->getLimit($entity) === 'unlimited';
    }

    /**
     * Get the default free plan (first active plan with is_free).
     */
    public static function getDefaultFree(): ?self
    {
        return self::where('is_free', true)->where('is_active', true)->orderBy('sort_order')->first();
    }

    /**
     * Get the default paid plan (first active non-free plan). Used for checkout fallback.
     */
    public static function getDefaultPaid(): ?self
    {
        return self::where('is_free', false)->where('is_active', true)->orderBy('sort_order')->first();
    }

    /**
     * Find plan by Stripe price id.
     */
    public static function findByStripePriceId(string $priceId): ?self
    {
        return self::where('stripe_price_id', $priceId)->where('is_active', true)->first();
    }

    /**
     * Find plan by Mercado Pago plan id.
     */
    public static function findByMercadoPagoPlanId(string $planId): ?self
    {
        return self::where('mercadopago_plan_id', $planId)->where('is_active', true)->first();
    }

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class, 'plan_id');
    }
}
