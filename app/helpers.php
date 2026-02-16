<?php

declare(strict_types=1);

/**
 * Funções globais de conveniência para Helpers LGPD e FormatHelper.
 * Carregadas via composer autoload para uso em views e controllers.
 */

use App\Helpers\FormatHelper;
use App\Helpers\LgpdHelper;

if (! function_exists('format_currency')) {
    /**
     * @param  float|int|string  $value
     */
    function format_currency($value, string $prefix = 'R$', bool $checkInspection = true): string
    {
        return FormatHelper::currency($value, $prefix, $checkInspection);
    }
}

if (! function_exists('format_percent')) {
    /**
     * @param  float|int|string  $value
     */
    function format_percent($value, int $decimals = 1): string
    {
        return FormatHelper::percent($value, $decimals);
    }
}

if (! function_exists('format_percent_decimal')) {
    /**
     * @param  float|int|string  $value
     */
    function format_percent_decimal($value, int $decimals = 1): string
    {
        return FormatHelper::percentDecimal($value, $decimals);
    }
}

if (! function_exists('format_number')) {
    /**
     * @param  float|int|string  $value
     */
    function format_number($value, int $decimals = 2): string
    {
        return FormatHelper::number($value, $decimals);
    }
}

if (! function_exists('format_compact_number')) {
    /**
     * @param  float|int|string  $value
     */
    function format_compact_number($value): string
    {
        return FormatHelper::compactNumber($value);
    }
}

if (! function_exists('lgpd_mask_cpf')) {
    function lgpd_mask_cpf(?string $cpf): string
    {
        return LgpdHelper::maskCpf($cpf);
    }
}

if (! function_exists('lgpd_format_cpf')) {
    function lgpd_format_cpf(?string $cpf): string
    {
        return LgpdHelper::formatCpf($cpf);
    }
}

if (! function_exists('lgpd_mask_cnpj')) {
    function lgpd_mask_cnpj(?string $cnpj): string
    {
        return LgpdHelper::maskCnpj($cnpj);
    }
}

if (! function_exists('lgpd_format_cnpj')) {
    function lgpd_format_cnpj(?string $cnpj): string
    {
        return LgpdHelper::formatCnpj($cnpj);
    }
}

if (! function_exists('lgpd_mask_phone')) {
    function lgpd_mask_phone(?string $phone): string
    {
        return LgpdHelper::maskPhone($phone);
    }
}

if (! function_exists('lgpd_format_phone')) {
    function lgpd_format_phone(?string $phone): string
    {
        return LgpdHelper::formatPhone($phone);
    }
}

if (! function_exists('lgpd_mask_email')) {
    function lgpd_mask_email(?string $email): string
    {
        return LgpdHelper::maskEmail($email);
    }
}

if (! function_exists('lgpd_clean_cpf')) {
    function lgpd_clean_cpf(?string $value): string
    {
        return LgpdHelper::cleanCpf($value);
    }
}

if (! function_exists('lgpd_clean_cnpj')) {
    function lgpd_clean_cnpj(?string $value): string
    {
        return LgpdHelper::cleanCnpj($value);
    }
}

if (! function_exists('lgpd_clean_phone')) {
    function lgpd_clean_phone(?string $value): string
    {
        return LgpdHelper::cleanPhone($value);
    }
}

if (! function_exists('parse_brl_money')) {
    /**
     * Converte string BRL (R$ 1.500,50 ou 1.500,50) para float.
     */
    function parse_brl_money(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        $str = preg_replace('/[^\d,.-]/', '', (string) $value);
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);

        return (float) ($str ?: 0);
    }
}

if (! function_exists('parse_brl_date')) {
    /**
     * Converte data DD/MM/YYYY para Y-m-d (para validação/salvamento).
     */
    function parse_brl_date(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
        }

        return $value;
    }
}

if (! function_exists('plan_pro_name')) {
    /** Nome do plano PRO (configurável em Admin > Planos). */
    function plan_pro_name(string $default = 'Vertex PRO'): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);

        return (string) $settings->get('plan_pro_name', $default);
    }
}

if (! function_exists('plan_free_name')) {
    /** Nome do plano gratuito (configurável em Admin > Planos). */
    function plan_free_name(string $default = 'Plano Gratuito'): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);

        return (string) $settings->get('plan_free_name', $default);
    }
}

if (! function_exists('branding_logo_url')) {
    /**
     * Retorna a URL da logo para o contexto e modo (claro/escuro).
     * Context: user, admin, suporte, homepage, default.
     * Fallback: logo específica -> app_logo -> storage/logos/logo.svg ou logo-white.svg.
     */
    function branding_logo_url(string $context = 'default', bool $dark = false): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $basePath = asset('storage/logos');

        $key = match ($context) {
            'user', 'homepage' => $dark ? 'logo_user_dark' : 'logo_user',
            'admin' => $dark ? 'logo_admin_dark' : 'logo_admin',
            'suporte' => $dark ? 'logo_suporte_dark' : 'logo_suporte',
            default => null,
        };

        $path = $key ? $settings->get($key) : null;
        if (! $path) {
            $path = $settings->get('app_logo');
        }

        if ($path) {
            return str_starts_with((string) $path, 'storage/') ? asset($path) : asset('storage/' . $path);
        }

        return $dark ? $basePath . '/logo-white.svg' : $basePath . '/logo.svg';
    }
}

if (! function_exists('branding_favicon_url')) {
    /** Retorna a URL do favicon. Fallback: app_favicon -> storage/logos/favicon.svg */
    function branding_favicon_url(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $path = $settings->get('favicon') ?? $settings->get('app_favicon');

        if ($path) {
            return str_starts_with((string) $path, 'storage/') ? asset($path) : asset('storage/' . $path);
        }

        return asset('storage/logos/favicon.svg');
    }
}

if (! function_exists('branding_panel_name')) {
    /** Nome do painel (user, admin, suporte). Fallback para app_name ou padrões. */
    function branding_panel_name(string $panel): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $appName = $settings->get('app_name', config('app.name', 'Vertex Contas'));

        return match ($panel) {
            'user' => (string) ($settings->get('panel_user_name') ?: $appName),
            'admin' => (string) ($settings->get('panel_admin_name') ?: 'Administração'),
            'suporte' => (string) ($settings->get('panel_suporte_name') ?: 'Suporte'),
            default => $appName,
        };
    }
}

if (! function_exists('branding_company_legal_name')) {
    /** Nome legal da empresa (documentos, rodapés). */
    function branding_company_legal_name(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);

        return (string) ($settings->get('company_legal_name') ?: $settings->get('company_name', config('app.name', 'Vertex Solutions LTDA')));
    }
}

if (! function_exists('vertex_chat_enabled')) {
    /** Verifica se o Vertex Chat VIP está habilitado globalmente. */
    function vertex_chat_enabled(): bool
    {
        $settings = app(\Modules\Core\Services\SettingService::class);

        return (bool) $settings->get('vertex_chat_enabled', true);
    }
}

if (! function_exists('maintenance_message')) {
    /** Mensagem customizável exibida na página de manutenção. */
    function maintenance_message(): ?string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $msg = $settings->get('maintenance_message');

        return $msg ? (string) $msg : null;
    }
}

if (! function_exists('recaptcha_enabled')) {
    /** Verifica se reCAPTCHA v3 está habilitado. */
    function recaptcha_enabled(): bool
    {
        return app(\Modules\Core\Services\RecaptchaService::class)->isEnabled();
    }
}

if (! function_exists('recaptcha_site_key')) {
    /** Retorna a chave pública do reCAPTCHA (para o frontend). */
    function recaptcha_site_key(): ?string
    {
        return app(\Modules\Core\Services\RecaptchaService::class)->getSiteKey();
    }
}

if (! function_exists('setting')) {
    /** Retorna valor de configuração do SettingService por chave. */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(\Modules\Core\Services\SettingService::class)->get($key, $default);
    }
}
