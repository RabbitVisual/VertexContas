<?php

declare(strict_types=1);

namespace Modules\Gateways\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'gateway_slug',
        'external_subscription_id',
        'external_customer_id',
        'status',
        'amount',
        'currency',
        'current_period_end',
        'canceled_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }
}
