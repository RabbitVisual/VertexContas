<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'deadline',
        'completed_at',
        'monthly_contribution',
        'contribution_account_id',
        'contribution_category_id',
        'contribution_recurrence_day',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'monthly_contribution' => 'decimal:2',
        'contribution_recurrence_day' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contributionAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'contribution_account_id');
    }

    public function contributionCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'contribution_category_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'goal_id');
    }

    public function recurringContributions(): HasMany
    {
        return $this->hasMany(RecurringTransaction::class, 'goal_id');
    }

    public function hasAutomatedContribution(): bool
    {
        $amount = $this->monthly_contribution ?? 0;

        return $amount > 0
            && $this->contribution_account_id !== null
            && $this->contribution_category_id !== null;
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }

        return min(100, (float) (($this->current_amount / $this->target_amount) * 100));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) ($this->target_amount - $this->current_amount));
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->completed_at !== null || (float) $this->current_amount >= (float) $this->target_amount;
    }
}
