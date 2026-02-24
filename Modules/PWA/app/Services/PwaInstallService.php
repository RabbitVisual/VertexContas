<?php

declare(strict_types=1);

namespace Modules\PWA\Services;

use App\Models\User;
use Modules\PWA\Models\PwaInstall;

class PwaInstallService
{
    public function recordInstall(array $data): PwaInstall
    {
        $userId = $data['user_id'] ?? (auth()->id() ?: null);
        $isPro = $userId ? (User::find($userId)?->isPro() ?? false) : false;

        $install = PwaInstall::firstOrNew([
            'device_fingerprint' => $data['device_fingerprint'],
        ]);

        $install->fill([
            'user_id' => $userId,
            'app_version' => $data['app_version'],
            'platform' => $data['platform'] ?? 'web',
            'user_agent' => $data['user_agent'] ?? null,
            'is_pro' => $isPro,
        ]);

        if (! $install->exists) {
            $install->installed_at = now();
        }

        $install->last_seen_at = now();
        $install->save();

        return $install;
    }

    public function updateLastSeen(string $deviceFingerprint): bool
    {
        $install = PwaInstall::where('device_fingerprint', $deviceFingerprint)->first();

        if (! $install) {
            return false;
        }

        $install->last_seen_at = now();
        if (auth()->id()) {
            $install->user_id = auth()->id();
            $install->is_pro = auth()->user()->isPro();
        }
        $install->save();

        return true;
    }
}
