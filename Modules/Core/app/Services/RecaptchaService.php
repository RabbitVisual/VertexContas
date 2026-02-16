<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    protected const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        protected SettingService $settingService
    ) {}

    public function isEnabled(): bool
    {
        return (bool) $this->settingService->get('recaptcha_enabled', false);
    }

    public function getSiteKey(): ?string
    {
        $key = $this->settingService->get('recaptcha_site_key');

        return $key ? (string) $key : null;
    }

    public function verify(string $token, ?string $action = 'login'): bool
    {
        $secret = $this->settingService->get('recaptcha_secret_key');
        if (! $secret) {
            return false;
        }

        $response = Http::asForm()->post(self::VERIFY_URL, [
            'secret' => $secret,
            'response' => $token,
        ]);

        if (! $response->successful()) {
            return false;
        }

        $data = $response->json();
        if (! ($data['success'] ?? false)) {
            return false;
        }

        $minScore = (float) ($this->settingService->get('recaptcha_min_score', 0.5));
        $score = (float) ($data['score'] ?? 0);

        return $score >= $minScore;
    }
}
