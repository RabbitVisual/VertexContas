<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Cria ou atualiza o único usuário inicial: administrador Reinan Rodrigues.
 * Executar após RolesAndPermissionsSeeder.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'r.rodriguesjs@gmail.com';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Reinan',
                'last_name' => 'Rodrigues',
                'password' => 'Re&32579345',
                'cpf' => '06696425000',
                'birth_date' => '1993-05-19',
                'email_verified_at' => now(),
                'membership' => 'pro',
                'status' => 'active',
            ]
        );

        $user->syncRoles(['admin']);
    }
}
