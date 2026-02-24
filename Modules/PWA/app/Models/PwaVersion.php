<?php

declare(strict_types=1);

namespace Modules\PWA\Models;

use Illuminate\Database\Eloquent\Model;

class PwaVersion extends Model
{
    protected $table = 'pwa_versions';

    protected $fillable = [
        'version',
        'release_notes',
        'is_force_update',
        'released_at',
    ];

    protected $casts = [
        'is_force_update' => 'boolean',
        'released_at' => 'datetime',
    ];

    public static function latestRelease(): ?self
    {
        return static::orderByDesc('released_at')->first();
    }
}
