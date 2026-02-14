<?php

namespace Modules\Gateways\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'gateway_slug',
        'external_id',
        'amount',
        'currency',
        'status', // pending, succeeded, failed
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
