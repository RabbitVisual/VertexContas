<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust proxies em produção (hospedagem compartilhada/CDN/Cloudflare)
        if ((string) (env('APP_ENV') ?? 'production') === 'production') {
            $middleware->trustProxies(at: '*');
        }
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);
        $middleware->append(\Modules\Core\Http\Middleware\CheckMaintenanceMode::class);
        $middleware->append(\Modules\Core\Http\Middleware\BlockSensitiveInspectionActions::class);
        $middleware->append(\Modules\Core\Http\Middleware\StoreInspectionViewUrl::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'pro' => \Modules\Core\Http\Middleware\EnsureUserIsPro::class,
            'financial.setup' => \Modules\PanelUser\Http\Middleware\EnsureFinancialSetup::class,
            'legal.acceptance' => \Modules\PanelUser\Http\Middleware\EnsureLegalAcceptance::class,
            'chat.access' => \Modules\VertexChat\Http\Middleware\EnsureChatAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
