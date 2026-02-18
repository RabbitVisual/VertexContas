<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Services\GamificationService;
use Modules\VertexChat\Models\Conversation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('conversation', fn (string $value) => Conversation::findOrFail($value));

        Event::listen(MessageSent::class, \App\Listeners\LogEmailSentListener::class);

        Password::defaults(function () {
            $minChars = (int) setting('security_password_min_chars', 8);
            $rule = Password::min(max(6, $minChars));
            if (setting('security_password_require_special', true)) {
                $rule->letters()->numbers()->symbols();
            }

            return $rule;
        });

        // Login rate limiter (configurable via Admin > Configurações > Segurança)
        RateLimiter::for('login', function (Request $request) {
            $attempts = 5;
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = app(\Modules\Core\Services\SettingService::class);
                $attempts = (int) ($settings->get('security_login_max_attempts') ?? $settings->get('max_login_attempts') ?? 5);
            }
            $attempts = max(1, min($attempts, 20));

            return Limit::perMinute($attempts)->by($request->ip())->response(function () {
                return redirect()->route('login')
                    ->withErrors(['throttle' => 'Muitas tentativas de login. Aguarde 1 minuto e tente novamente.'])
                    ->onlyInput('email');
            });
        });

        // Force HTTPS em produção (hospedagem compartilhada atrás de proxy/SSL)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Apply mail config from Admin settings (real-time)
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $mailer = setting('mail_mailer');
            if ($mailer) {
                config(['mail.default' => $mailer]);
            }
            $fromAddr = setting('mail_from_address');
            if ($fromAddr) {
                config(['mail.from.address' => $fromAddr]);
            }
            $fromName = setting('mail_from_name');
            if ($fromName) {
                config(['mail.from.name' => $fromName]);
            }
            if ($mailer === 'smtp') {
                $host = setting('mail_host');
                if ($host) {
                    config([
                        'mail.mailers.smtp.host' => $host,
                        'mail.mailers.smtp.port' => (int) (setting('mail_port') ?? 587),
                        'mail.mailers.smtp.username' => setting('mail_username'),
                        'mail.mailers.smtp.password' => setting('mail_password'),
                        'mail.mailers.smtp.encryption' => setting('mail_encryption') ?: 'tls',
                    ]);
                }
            }
        }

        $vertexBotComposer = function ($view): void {
            if (! Auth::check()) {
                return;
            }
            $user = Auth::user();
            if (! ($user->show_assistant ?? true)) {
                $view->with('vertexBot', [
                    'insight' => null,
                    'financial_score' => 0,
                    'metrics' => [],
                    'coaching_stats' => null,
                ]);
                return;
            }
            $vertexBot = app(GamificationService::class)->analyzeUser($user, request()->route()?->getName());
            if (isset($vertexBot['insight'])) {
                session(['vertex_bot_last_insight' => $vertexBot['insight']]);
                session(['vertex_bot_financial_score' => $vertexBot['financial_score'] ?? 0]);
            }
            $view->with('vertexBot', $vertexBot);
        };

        // Layout: Vertex Bot widget e variáveis globais do painel
        View::composer(['paneluser::layouts.master', 'paneluser::components.layouts.master'], $vertexBotComposer);

        // Dashboards: score financeiro no card (conteúdo da página usa $vertexBot['financial_score'])
        View::composer(['core::dashboard', 'paneluser::index'], $vertexBotComposer);
    }
}
