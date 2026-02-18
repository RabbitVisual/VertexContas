<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => env('APP_NAME', 'Vertex Contas'),
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'app_description',
                'value' => 'Sistema de Controle Financeiro Profissional',
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'app_url',
                'value' => env('APP_URL', 'http://localhost'),
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'app_timezone',
                'value' => 'America/Sao_Paulo',
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'app_locale',
                'value' => env('APP_LOCALE', 'pt_BR'),
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => false,
                'group' => 'general',
                'type' => 'boolean',
                'is_encrypted' => false,
            ],
            [
                'key' => 'panel_user_name',
                'value' => env('APP_NAME', 'Vertex Contas'),
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'panel_admin_name',
                'value' => 'Administração',
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'panel_suporte_name',
                'value' => 'Suporte',
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],

            // Branding Settings
            [
                'key' => 'app_logo',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'app_favicon',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_user',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_user_dark',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_admin',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_admin_dark',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_suporte',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'logo_suporte_dark',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'group' => 'branding',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'company_legal_name',
                'value' => 'Vertex Solutions LTDA',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],

            // Mail Settings
            [
                'key' => 'mail_mailer',
                'value' => env('MAIL_MAILER', 'log'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_host',
                'value' => env('MAIL_HOST', '127.0.0.1'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_port',
                'value' => env('MAIL_PORT', '2525'),
                'group' => 'mail',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_username',
                'value' => env('MAIL_USERNAME'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_password',
                'value' => env('MAIL_PASSWORD'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => true, // Encrypted
            ],
            [
                'key' => 'mail_encryption',
                'value' => env('MAIL_ENCRYPTION', 'tls'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => env('MAIL_FROM_NAME', env('APP_NAME', 'Vertex Contas')),
                'group' => 'mail',
                'type' => 'string',
                'is_encrypted' => false,
            ],

            // Document Templates (invoices, reports)
            [
                'key' => 'company_name',
                'value' => env('APP_NAME', 'Vertex Contas'),
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'company_address',
                'value' => '',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'company_cnpj',
                'value' => '',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'company_phone',
                'value' => '',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'company_email',
                'value' => '',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'document_footer_text',
                'value' => 'Vertex Contas - Sistema de Gestão Financeira',
                'group' => 'document_templates',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'limit_download_invoice_per_day',
                'value' => 10,
                'group' => 'document_templates',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'limit_download_report_per_day',
                'value' => 5,
                'group' => 'document_templates',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'limit_ai_report_per_month',
                'value' => 5,
                'group' => 'document_templates',
                'type' => 'integer',
                'is_encrypted' => false,
            ],

            // Security Settings
            [
                'key' => 'max_login_attempts',
                'value' => 5,
                'group' => 'security',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'session_lifetime',
                'value' => 120,
                'group' => 'security',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'recaptcha_enabled',
                'value' => false,
                'group' => 'security',
                'type' => 'boolean',
                'is_encrypted' => false,
            ],
            [
                'key' => 'recaptcha_site_key',
                'value' => env('RECAPTCHA_SITE_KEY'),
                'group' => 'security',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'recaptcha_secret_key',
                'value' => env('RECAPTCHA_SECRET_KEY'),
                'group' => 'security',
                'type' => 'string',
                'is_encrypted' => true,
            ],
            [
                'key' => 'recaptcha_min_score',
                'value' => 0.5,
                'group' => 'security',
                'type' => 'float',
                'is_encrypted' => false,
            ],

            // Features Settings (Vertex Chat, etc.)
            [
                'key' => 'vertex_chat_enabled',
                'value' => true,
                'group' => 'features',
                'type' => 'boolean',
                'is_encrypted' => false,
            ],

            // Maintenance custom message
            [
                'key' => 'maintenance_message',
                'value' => null,
                'group' => 'general',
                'type' => 'string',
                'is_encrypted' => false,
            ],

            // Notifications retention
            [
                'key' => 'notifications_retention_days',
                'value' => 90,
                'group' => 'notifications',
                'type' => 'integer',
                'is_encrypted' => false,
            ],
            [
                'key' => 'notifications_auto_clean_read',
                'value' => true,
                'group' => 'notifications',
                'type' => 'boolean',
                'is_encrypted' => false,
            ],

            // Public plan page (copy editable in Admin > Plan)
            [
                'key' => 'plan_page_headline',
                'value' => 'Assuma o Controle da sua Elite Financeira',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_subhead',
                'value' => 'Pare de apenas anotar gastos. Comece a construir riqueza com a Inteligência do Vertex PRO.',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_monthly_price',
                'value' => '29,90',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_yearly_price',
                'value' => '197,00',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_yearly_savings',
                'value' => '160,00',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_cta_text',
                'value' => 'QUERO SER VERTEX PRO',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_features_html',
                'value' => '',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            [
                'key' => 'plan_page_table_html',
                'value' => '',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
            // Cookie consent (LGPD) – exibido para todos os visitantes, inclusive anônimos
            [
                'key' => 'homepage_cookie_consent_enabled',
                'value' => true,
                'group' => 'homepage',
                'type' => 'boolean',
                'is_encrypted' => false,
            ],
            [
                'key' => 'homepage_cookie_consent_message',
                'value' => 'Utilizamos cookies para garantir a melhor experiência, segurança e funcionamento do site. Ao continuar navegando, você concorda com nossa Política de Cookies e com o tratamento de dados conforme a LGPD. Você pode revogar ou alterar suas preferências a qualquer momento.',
                'group' => 'homepage',
                'type' => 'string',
                'is_encrypted' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
