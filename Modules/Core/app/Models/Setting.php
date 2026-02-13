<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'is_encrypted',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // Automatically encrypt value if is_encrypted is true
        static::saving(function (Setting $setting) {
            if ($setting->is_encrypted && $setting->value && !self::isEncrypted($setting->value)) {
                $setting->value = Crypt::encryptString($setting->value);
            }
        });
    }

    /**
     * Get the value attribute with automatic decryption.
     */
    public function getValueAttribute($value)
    {
        if ($this->is_encrypted && $value && self::isEncrypted($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $this->castValue($value);
    }

    /**
     * Cast value based on type.
     */
    protected function castValue($value)
    {
        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Check if a value is encrypted.
     */
    protected static function isEncrypted(string $value): bool
    {
        // Laravel encrypted strings start with "eyJpdiI6" (base64 of {"iv":)
        return str_starts_with($value, 'eyJpdiI6');
    }

    /**
     * Scope a query to only include settings of a given group.
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only include encrypted settings.
     */
    public function scopeEncrypted($query)
    {
        return $query->where('is_encrypted', true);
    }
}
