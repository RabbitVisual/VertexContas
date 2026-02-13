<?php

namespace Modules\Core\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'account_id',
        'type',
        'amount',
        'frequency',
        'next_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_date' => 'date',
        'is_active' => 'boolean',
        'type' => 'string',
        'frequency' => 'string',
    ];

    /**
     * Get the user that owns the recurring transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for the recurring transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the account for the recurring transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Check if this recurring transaction should be processed.
     */
    public function shouldProcess(): bool
    {
        return $this->is_active && $this->next_date->lte(now());
    }

    /**
     * Process this recurring transaction by creating an actual transaction.
     */
    public function process(): Transaction
    {
        $transaction = Transaction::create([
            'user_id' => $this->user_id,
            'account_id' => $this->account_id,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'date' => now(),
            'description' => $this->description . ' (Recorrente)',
            'status' => 'completed',
        ]);

        // Update next_date based on frequency
        $this->updateNextDate();

        return $transaction;
    }

    /**
     * Update the next_date based on frequency.
     */
    public function updateNextDate(): void
    {
        $nextDate = match ($this->frequency) {
            'daily' => $this->next_date->addDay(),
            'weekly' => $this->next_date->addWeek(),
            'monthly' => $this->next_date->addMonth(),
            'yearly' => $this->next_date->addYear(),
            default => $this->next_date->addMonth(),
        };

        $this->update(['next_date' => $nextDate]);
    }

    /**
     * Scope to get only active recurring transactions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get recurring transactions that are due.
     */
    public function scopeDue($query)
    {
        return $query->where('next_date', '<=', now());
    }
}
