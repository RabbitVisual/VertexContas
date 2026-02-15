<?php

declare(strict_types=1);

namespace Modules\Gamification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon_name',
        'trigger_key',
        'color',
        'rarity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userMedals(): HasMany
    {
        return $this->hasMany(UserMedal::class);
    }
}
