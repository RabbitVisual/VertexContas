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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
