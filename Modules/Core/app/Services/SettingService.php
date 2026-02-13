<?php

namespace Modules\Core\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\Setting;

class SettingService
{
    /**
     * Cache key for all settings.
     */
    protected const CACHE_KEY = 'settings.all';

    /**
     * Cache TTL (forever until manually flushed).
     */
    protected const CACHE_TTL = null;

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAllCached();

        return $settings->get($key, $default);
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $encrypt = false): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'is_encrypted' => $encrypt,
            ]
        );

        $this->flush();
    }

    /**
     * Check if a setting exists.
     */
    public function has(string $key): bool
    {
        $settings = $this->getAllCached();

        return $settings->has($key);
    }

    /**
     * Delete a setting.
     */
    public function forget(string $key): void
    {
        Setting::where('key', $key)->delete();

        $this->flush();
    }

    /**
     * Get all settings by group.
     */
    public function getByGroup(string $group): Collection
    {
        return Setting::byGroup($group)
            ->get()
            ->pluck('value', 'key');
    }

    /**
     * Get all settings (cached).
     */
    protected function getAllCached(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::all()->pluck('value', 'key');
        });
    }

    /**
     * Flush the settings cache.
     */
    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get multiple settings at once.
     */
    public function getMany(array $keys): Collection
    {
        $settings = $this->getAllCached();

        return collect($keys)->mapWithKeys(function ($key) use ($settings) {
            return [$key => $settings->get($key)];
        });
    }

    /**
     * Set multiple settings at once.
     */
    public function setMany(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value, $group);
        }
    }
}
