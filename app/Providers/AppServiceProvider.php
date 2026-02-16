<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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

        // Login rate limiter (configurable via Admin > Configurações > Segurança)
        RateLimiter::for('login', function (Request $request) {
            $attempts = 5;
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $attempts = (int) app(\Modules\Core\Services\SettingService::class)->get('max_login_attempts', 5);
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

        View::composer(['paneluser::layouts.master', 'paneluser::components.layouts.master'], function ($view): void {
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
            $view->with('vertexBot', app(GamificationService::class)->analyzeUser($user));
        });
    }
}
