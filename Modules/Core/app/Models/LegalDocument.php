<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'version',
        'is_active',
        'requires_acceptance',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_acceptance' => 'boolean',
    ];

    public function acceptances()
    {
        return $this->hasMany(UserLegalAcceptance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequiresAcceptance($query)
    {
        return $query->where('requires_acceptance', true);
    }

    public static function getBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }
}
