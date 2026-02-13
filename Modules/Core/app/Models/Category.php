<?php

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'icon',
        'color',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the user that owns the category.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the category.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope a query to only include categories for a specific user (and system defaults).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('user_id'); // System defaults

            // If user is Pro or Admin, include their custom categories
            if ($user->hasRole('pro_user') || $user->hasRole('admin')) {
                $q->orWhere('user_id', $user->id);
            }
            // If user WAS Pro and has used custom categories in transactions,
            // we might want to still show them in filters, but for creation...
            // The requirement says: "accounts already selected with the category remain...
            // he cannot select personal category for new ones".
            // So for the purposes of the dropdown in CREATE form (which this scope will support),
            // we should HIDE them if not Pro.
            // Existing transactions will still render fine via relations.
        });
    }

    /**
     * Get the budgets for the category.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }
}
