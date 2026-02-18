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
    /** Nome do plano pago padrão (primeiro plano ativo não gratuito ou Settings). */
    function plan_pro_name(string $default = 'Vertex PRO'): string
    {
        $plan = \Modules\Core\Models\Plan::getDefaultPaid();
        if ($plan) {
            return $plan->name;
        }
        $settings = app(\Modules\Core\Services\SettingService::class);
        return (string) $settings->get('plan_pro_name', $default);
    }
}

if (! function_exists('plan_free_name')) {
    /** Nome do plano gratuito (primeiro plano ativo com is_free ou Settings). */
    function plan_free_name(string $default = 'Plano Gratuito'): string
    {
        $plan = \Modules\Core\Models\Plan::getDefaultFree();
        if ($plan) {
            return $plan->name;
        }
        $settings = app(\Modules\Core\Services\SettingService::class);
        return (string) $settings->get('plan_free_name', $default);
    }
}

if (! function_exists('current_plan_name')) {
    /** Nome do plano atual do usuário (via getPlan()). */
    function current_plan_name(?\App\Models\User $user): string
    {
        if (! $user) {
            return plan_free_name();
        }
        try {
            return $user->getPlan()->name;
        } catch (\Throwable) {
            return plan_free_name();
        }
    }
}

if (! function_exists('pro_benefit_accounts_label')) {
    /** Rótulo de benefício de contas do plano PRO (ilimitadas ou até N). */
    function pro_benefit_accounts_label(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
        $limit = (int) $settings->get('limit_pro_account', -1);
        if (! $proHasLimits || $limit < 0) {
            return 'Contas ilimitadas';
        }
        return 'Até ' . $limit . ' contas';
    }
}

if (! function_exists('pro_benefit_goals_label')) {
    /** Rótulo de benefício de metas do plano PRO (ilimitadas ou até N). */
    function pro_benefit_goals_label(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
        $limit = (int) $settings->get('limit_pro_goal', -1);
        if (! $proHasLimits || $limit < 0) {
            return 'Metas ilimitadas';
        }
        return 'Até ' . $limit . ' metas';
    }
}

if (! function_exists('pro_benefit_budgets_label')) {
    /** Rótulo de benefício de orçamentos do plano PRO (ilimitados ou até N). */
    function pro_benefit_budgets_label(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
        $limit = (int) $settings->get('limit_pro_budget', -1);
        if (! $proHasLimits || $limit < 0) {
            return 'Orçamentos ilimitados';
        }
        return 'Até ' . $limit . ' orçamentos';
    }
}

if (! function_exists('pro_benefits_short_description')) {
    /** Frase curta de benefícios PRO para CTAs/balões (variant: cta, sidebar, profile, onboarding). */
    function pro_benefits_short_description(string $variant = 'cta'): string
    {
        $accounts = pro_benefit_accounts_label();
        $planName = plan_pro_name();
        return match ($variant) {
            'cta' => "Gráficos avançados, {$accounts} e mais recursos para suas finanças.",
            'sidebar' => "{$accounts}, relatórios em PDF/CSV, metas e suporte VIP.",
            'profile' => "Relatórios avançados, {$accounts} e suporte prioritário com o {$planName}.",
            'onboarding' => "Libere relatórios em PDF/CSV, {$accounts} e suporte VIP com o plano PRO.",
            default => "Gráficos avançados, {$accounts} e mais recursos para suas finanças.",
        };
    }
}

if (! function_exists('pro_benefits_welcome_message')) {
    /** Mensagem de boas-vindas pós-pagamento PRO (evita "ilimitado" quando admin definiu limites). */
    function pro_benefits_welcome_message(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
        if (! $proHasLimits) {
            return 'Seu pagamento foi confirmado e você agora tem acesso ilimitado a todos os recursos.';
        }
        return 'Seu pagamento foi confirmado. Aproveite os benefícios do seu plano conforme configurado.';
    }
}

if (! function_exists('pro_benefits_active_description')) {
    /** Descrição curta para usuário PRO ativo (perfil/card): ilimitado ou conforme plano. */
    function pro_benefits_active_description(): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
        $proHasLimits = (bool) $settings->get('pro_has_limits', 0);
        if (! $proHasLimits) {
            return 'Acesso ilimitado a todos os recursos. Aproveite!';
        }
        return 'Aproveite os benefícios do seu plano conforme configurado.';
    }
}

if (! function_exists('replace_plan_name_in_text')) {
    /** Substitui "Vertex PRO" por plan_pro_name() em textos vindos de DB/seeders (medalhas, coaching). */
    function replace_plan_name_in_text(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }
        return str_replace('Vertex PRO', plan_pro_name(), $text);
    }
}

if (! function_exists('ticket_sla_message')) {
    /** Mensagem de SLA de resposta para chamados: 24h PRO / 72h plano gratuito. */
    function ticket_sla_message(): string
    {
        $isPro = auth()->user()?->isPro() ?? false;

        return $isPro
            ? 'Resposta em até 24 horas (assinantes ' . plan_pro_name() . ').'
            : 'Resposta em até 72 horas (plano gratuito).';
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

if (! function_exists('branding_logo_base64')) {
    /**
     * Retorna a logo como data URI Base64 para uso em PDF (evita falhas de SSL/path no DomPDF).
     * Mesma lógica de branding_logo_url para resolver o arquivo; lê do disco e codifica em base64.
     * Retorna string vazia se o arquivo não existir.
     */
    function branding_logo_base64(string $context = 'default', bool $dark = false): string
    {
        $settings = app(\Modules\Core\Services\SettingService::class);
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
        $relative = $path
            ? (str_starts_with((string) $path, 'storage/') ? $path : 'storage/' . $path)
            : ($dark ? 'storage/logos/logo-white.svg' : 'storage/logos/logo.svg');
        $absolute = public_path($relative);
        if (! is_file($absolute) || ! is_readable($absolute)) {
            return '';
        }
        $content = file_get_contents($absolute);
        if ($content === false) {
            return '';
        }
        $ext = strtolower(pathinfo($absolute, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
        return 'data:' . $mime . ';base64,' . base64_encode($content);
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

if (! function_exists('medal_color_hex')) {
    /**
     * Mapeia nome de cor da medalha (sky, emerald, indigo, etc.) para hex válido para CSS.
     */
    function medal_color_hex(string $color): string
    {
        $map = [
            'sky' => '#0ea5e9',
            'emerald' => '#10b981',
            'indigo' => '#6366f1',
            'rose' => '#f43f5e',
            'amber' => '#f59e0b',
            'purple' => '#a855f7',
            'slate' => '#64748b',
            'teal' => '#14b8a6',
        ];

        return $map[strtolower($color)] ?? '#64748b';
    }
}
