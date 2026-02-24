<?php

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'account_id',
        'destination_account_id',
        'category_id',
        'goal_id',
        'type',
        'amount',
        'date',
        'description',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'type' => 'string',
        'status' => 'string',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that owns the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the category that owns the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the goal this transaction contributes to (when type is expense).
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    /**
     * Scope a query to only include transactions for a specific goal.
     */
    public function scopeForGoal($query, $goalId)
    {
        return $query->where('goal_id', $goalId);
    }

    /**
     * Scope a query to only include income transactions.
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope a query to only include expense transactions.
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
