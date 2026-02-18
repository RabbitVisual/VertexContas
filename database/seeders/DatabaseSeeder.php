<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles and permissions (required for admin role)
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Único usuário inicial: admin Reinan Rodrigues
        $this->call(AdminUserSeeder::class);

        // 3. Settings (inclui homepage_cookie_consent_enabled) e documentos legais (termos, privacidade, cookies)
        $this->call(\Modules\Core\Database\Seeders\CoreDatabaseSeeder::class);

        // 4. Gateways (Stripe e Mercado Pago inativos; configurar em Admin > Gateways)
        $this->call(\Modules\Gateways\Database\Seeders\GatewaysDatabaseSeeder::class);

        // 5. Blog (categorias e posts; dependem de BlogCategory e do admin)
        $this->call(\Modules\Blog\Database\Seeders\BlogDatabaseSeeder::class);

        // 6. Wiki (categorias primeiro, depois artigos que referenciam o admin)
        $this->call(WikiCategorySeeder::class);
        $this->call(WikiArticleSeeder::class);
    }
}
