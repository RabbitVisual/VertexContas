<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        if (! $this->isEnabled()) {
            return null;
        }
        $key = $this->settingService->get('recaptcha_site_key');

        return $key ? (string) $key : null;
    }

    public function verify(string $token, ?string $action = 'login'): bool
    {
        $secret = $this->settingService->get('recaptcha_secret_key');
        if (! $secret || trim($secret) === '') {
            Log::warning('RecaptchaService: Secret key not configured or empty');

            return false;
        }

        $response = Http::timeout(10)->asForm()->post(self::VERIFY_URL, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->successful()) {
            Log::warning('RecaptchaService: HTTP request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        $data = $response->json();
        if (! ($data['success'] ?? false)) {
            Log::warning('RecaptchaService: Google verification failed', [
                'error_codes' => $data['error-codes'] ?? [],
                'action' => $action,
            ]);

            return false;
        }

        $minScore = (float) ($this->settingService->get('recaptcha_min_score', 0.5));
        $score = (float) ($data['score'] ?? 0);

        if ($score < $minScore) {
            Log::warning('RecaptchaService: Score below threshold', [
                'score' => $score,
                'min_score' => $minScore,
                'action' => $action,
            ]);

            return false;
        }

        return true;
    }
}
