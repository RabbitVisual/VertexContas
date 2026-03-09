<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Todos os seeders são idempotentes: apenas inserem quando não existe e atualizam quando aplicável;
     * nenhum trunca ou substitui dados existentes. Seguro rodar após migrate ou em produção.
     *
     * Ordem: base (roles, admin, core) → gateways → gamificação (insights + medalhas + regras) → blog → wiki → módulos opcionais.
     */
    public function run(): void
    {
        // 1. Roles and permissions (required for admin and all panels)
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Usuário admin inicial
        $this->call(AdminUserSeeder::class);

        // 3. Core: settings, documentos legais, categorias sistema, permissões core, planos (free/pro), templates de e-mail
        $this->call(\Modules\Core\Database\Seeders\CoreDatabaseSeeder::class);

        // 4. Usuários de demo para auto-login em ambiente local (Pro, Gratis, Suporte)
        $this->call(DevUsersSeeder::class);

        // 5. Gateways (Stripe, Mercado Pago — inativos por padrão)
        $this->call(\Modules\Gateways\Database\Seeders\GatewaysDatabaseSeeder::class);

        // 6. Gamificação: insights (Vertex Bot), medalhas (conquistas), regras de coaching
        $this->call(\Modules\Gamification\Database\Seeders\GamificationDatabaseSeeder::class);

        // 7. Blog: categorias, posts gratuitos e premium
        $this->call(\Modules\Blog\Database\Seeders\BlogDatabaseSeeder::class);

        // 8. Wiki: categorias e artigos (dependem de admin e WikiCategory)
        $this->call(WikiCategorySeeder::class);
        $this->call(WikiArticleSeeder::class);

        // 9. Módulos (seeders vazios por padrão; ao adicionar seeds, passam a ser populados aqui)
        $this->call(\Modules\Notifications\Database\Seeders\NotificationsDatabaseSeeder::class);
        $this->call(\Modules\VertexChat\Database\Seeders\VertexChatDatabaseSeeder::class);
        $this->call(\Modules\PWA\Database\Seeders\PWADatabaseSeeder::class);
        $this->call(\Modules\HomePage\Database\Seeders\HomePageDatabaseSeeder::class);
        $this->call(\Modules\PanelAdmin\Database\Seeders\PanelAdminDatabaseSeeder::class);
        $this->call(\Modules\PanelUser\Database\Seeders\PanelUserDatabaseSeeder::class);
        $this->call(\Modules\PanelSuporte\Database\Seeders\PanelSuporteDatabaseSeeder::class);
    }
}
