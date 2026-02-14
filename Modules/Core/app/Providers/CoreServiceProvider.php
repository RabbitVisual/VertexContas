<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CoreServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Core';

    protected string $nameLower = 'core';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));

        // Register observers
        \Modules\Core\Models\Transaction::observe(\Modules\Core\Observers\TransactionObserver::class);

        // Limit Observers
        \Modules\Core\Models\Account::observe(\Modules\Core\Observers\LimitObserver::class);
        \Modules\Core\Models\Goal::observe(\Modules\Core\Observers\LimitObserver::class);
        \Modules\Core\Models\Budget::observe(\Modules\Core\Observers\LimitObserver::class);
        \Modules\Core\Models\Transaction::observe(\Modules\Core\Observers\LimitObserver::class);

        // Override configs from database
        $this->overrideConfigsFromDatabase();
    }

    /**
     * Override Laravel configs with database settings.
     */
    protected function overrideConfigsFromDatabase(): void
    {
        try {
            // Only run if settings table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return;
            }

            $settings = app(\Modules\Core\Services\SettingService::class);

            // Override app configs from admin panel
            $timezone = $settings->get('app_timezone', 'America/Sao_Paulo');
            $locale = $settings->get('app_locale', 'pt_BR');

            config([
                'app.name' => $settings->get('app_name', env('APP_NAME', 'Vertex Contas')),
                'app.url' => $settings->get('app_url', env('APP_URL', 'http://localhost')),
                'app.timezone' => $timezone,
                'app.locale' => $locale,
            ]);

            // Apply timezone and locale globally (datas e Carbon)
            date_default_timezone_set($timezone);
            \Carbon\Carbon::setLocale($locale);

            // Override mail configs
            config([
                'mail.default' => $settings->get('mail_mailer', env('MAIL_MAILER', 'log')),
                'mail.mailers.smtp.host' => $settings->get('mail_host', env('MAIL_HOST', '127.0.0.1')),
                'mail.mailers.smtp.port' => $settings->get('mail_port', env('MAIL_PORT', 2525)),
                'mail.mailers.smtp.username' => $settings->get('mail_username', env('MAIL_USERNAME')),
                'mail.mailers.smtp.password' => $settings->get('mail_password', env('MAIL_PASSWORD')),
                'mail.mailers.smtp.encryption' => $settings->get('mail_encryption', env('MAIL_ENCRYPTION', 'tls')),
                'mail.from.address' => $settings->get('mail_from_address', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
                'mail.from.name' => $settings->get('mail_from_name', env('MAIL_FROM_NAME', env('APP_NAME', 'Vertex Contas'))),
            ]);
        } catch (\Exception $e) {
            // Silently fail if database is not ready (e.g., during migrations)
            \Illuminate\Support\Facades\Log::debug('Settings override skipped: ' . $e->getMessage());
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([
            \Modules\Core\Console\Commands\ProcessRecurringTransactions::class,
        ]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
