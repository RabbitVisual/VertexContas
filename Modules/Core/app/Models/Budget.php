<?php

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'account_id',
        'limit_amount',
        'period',
        'is_recurring',
        'period_start',
        'alert_threshold',
        'allow_exceed',
    ];

    protected $casts = [
        'limit_amount' => 'decimal:2',
        'period' => 'string',
        'is_recurring' => 'boolean',
        'period_start' => 'date',
        'alert_threshold' => 'integer',
        'allow_exceed' => 'boolean',
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the budget.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the account this budget draws from (optional).
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get spent amount for current period.
     * When account_id is set, only transactions from that account count.
     * When is_recurring is false, period is determined by period_start.
     */
    public function getSpentAmountAttribute(): float
    {
        if ($this->is_recurring) {
            $startDate = $this->period === 'monthly'
                ? now()->startOfMonth()
                : now()->startOfYear();
            $endDate = $this->period === 'monthly'
                ? now()->endOfMonth()
                : now()->endOfYear();
        } else {
            $periodStart = $this->period_start ?? now();
            $startDate = \Carbon\Carbon::parse($periodStart)->startOfMonth();
            $endDate = \Carbon\Carbon::parse($periodStart)->endOfMonth();
        }

        $query = Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($this->account_id !== null) {
            $query->where('account_id', $this->account_id);
        }

        return (float) $query->sum('amount');
    }

    /**
     * Get remaining budget amount.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->limit_amount - $this->spent_amount);
    }

    /**
     * Get budget usage percentage.
     */
    public function getUsagePercentageAttribute(): float
    {
        if ($this->limit_amount == 0) {
            return 0;
        }

        return min(100, ($this->spent_amount / $this->limit_amount) * 100);
    }

    /**
     * Check if budget is exceeded.
     */
    public function getIsExceededAttribute(): bool
    {
        return $this->spent_amount > $this->limit_amount;
    }

    /**
     * Check if adding an expense would exceed a budget that has allow_exceed = false.
     * Returns the Budget that would be exceeded, or null.
     * When budget has account_id, only transactions on that account (or transaction's account_id) apply.
     *
     * @param  int  $userId
     * @param  int  $categoryId
     * @param  float  $amount
     * @param  string  $date  (Y-m-d)
     * @param  int|null  $excludeTransactionId  For update: exclude this transaction from spent sum
     * @param  int|null  $transactionAccountId  Account of the transaction; budgets with account_id must match this or be null
     */
    public static function getBlockingBudgetIfExceeded(
        int $userId,
        int $categoryId,
        float $amount,
        string $date,
        ?int $excludeTransactionId = null,
        ?int $transactionAccountId = null
    ): ?Budget {
        $dateObj = \Carbon\Carbon::parse($date);

        $budgets = self::where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('allow_exceed', false)
            ->get();

        foreach ($budgets as $budget) {
            if ($budget->account_id !== null && $transactionAccountId !== null && (int) $budget->account_id !== (int) $transactionAccountId) {
                continue;
            }

            if ($budget->is_recurring) {
                $startDate = $budget->period === 'monthly'
                    ? $dateObj->copy()->startOfMonth()
                    : $dateObj->copy()->startOfYear();
                $endDate = $budget->period === 'monthly'
                    ? $dateObj->copy()->endOfMonth()
                    : $dateObj->copy()->endOfYear();
            } else {
                $periodStart = $budget->period_start ?? $dateObj;
                $startDate = \Carbon\Carbon::parse($periodStart)->startOfMonth();
                $endDate = \Carbon\Carbon::parse($periodStart)->endOfMonth();
                if ($dateObj->lt($startDate) || $dateObj->gt($endDate)) {
                    continue;
                }
            }

            $spentQuery = Transaction::where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('date', [$startDate, $endDate]);

            if ($budget->account_id !== null) {
                $spentQuery->where('account_id', $budget->account_id);
            }

            if ($excludeTransactionId !== null) {
                $spentQuery->where('id', '!=', $excludeTransactionId);
            }

            $spent = (float) $spentQuery->sum('amount');
            $limit = (float) $budget->limit_amount;

            if ($spent + $amount > $limit) {
                return $budget;
            }
        }

        return null;
    }
}
