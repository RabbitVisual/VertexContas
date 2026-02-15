<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\GamificationService;

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
        // Force HTTPS em produção (hospedagem compartilhada atrás de proxy/SSL)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer(['paneluser::layouts.master', 'paneluser::components.layouts.master'], function ($view): void {
            if (! auth()->check()) {
                return;
            }
            $user = auth()->user();
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
