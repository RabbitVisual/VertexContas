<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Models\Plan;

/**
 * Usuários de demo para auto-login na tela de login (ambiente local).
 * Só insere se não existir; não altera usuários já criados.
 * Os botões "Admin", "Pro", "Gratis" e "Suporte" na login esperam estes e-mails/senhas.
 */
class DevUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command->info('DevUsersSeeder: ignorado (apenas APP_ENV=local).');
            return;
        }

        $planFree = Plan::where('slug', 'free')->first();
        $planPro = Plan::where('slug', 'pro')->first();

        $demoUsers = [
            [
                'email' => 'pro@vertexcontas.com',
                'password' => 'password',
                'first_name' => 'Demo',
                'last_name' => 'Pro',
                'role' => 'pro_user',
                'plan_id' => $planPro?->id,
                'membership' => 'pro',
            ],
            [
                'email' => 'user@vertexcontas.com',
                'password' => 'password',
                'first_name' => 'Demo',
                'last_name' => 'Gratis',
                'role' => 'free_user',
                'plan_id' => $planFree?->id,
                'membership' => 'free',
            ],
            [
                'email' => 'support@vertexcontas.com',
                'password' => 'password',
                'first_name' => 'Demo',
                'last_name' => 'Suporte',
                'role' => 'support',
                'plan_id' => $planFree?->id,
                'membership' => 'free',
            ],
        ];

        foreach ($demoUsers as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => $data['password'],
                    'email_verified_at' => now(),
                    'plan_id' => $data['plan_id'],
                    'membership' => $data['membership'],
                    'status' => 'active',
                ]
            );
            $user->syncRoles([$role]);
        }

        $this->command->info('DevUsersSeeder: usuários de demo (Pro, Gratis, Suporte) criados ou já existentes.');
    }
}
