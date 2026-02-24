<?php

declare(strict_types=1);

namespace Modules\PWA\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PwaInstall extends Model
{
    protected $table = 'pwa_installs';

    protected $fillable = [
        'user_id',
        'device_fingerprint',
        'app_version',
        'installed_at',
        'last_seen_at',
        'platform',
        'user_agent',
        'is_pro',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_pro' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
