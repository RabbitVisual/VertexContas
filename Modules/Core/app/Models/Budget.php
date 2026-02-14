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
        'limit_amount',
        'period',
        'alert_threshold',
        'allow_exceed',
    ];

    protected $casts = [
        'limit_amount' => 'decimal:2',
        'period' => 'string',
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
     * Get spent amount for current period.
     */
    public function getSpentAmountAttribute(): float
    {
        $startDate = $this->period === 'monthly'
            ? now()->startOfMonth()
            : now()->startOfYear();

        return Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->where('date', '>=', $startDate)
            ->sum('amount');
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
}
