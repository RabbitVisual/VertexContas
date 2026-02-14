<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // User::factory(10)->create();

        $demoUsers = [
            [
                'email' => 'admin@vertexcontas.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
            ],
            [
                'email' => 'pro@vertexcontas.com',
                'first_name' => 'Pro',
                'last_name' => 'User',
                'role' => 'pro_user',
            ],
            [
                'email' => 'user@vertexcontas.com',
                'first_name' => 'Free',
                'last_name' => 'User',
                'role' => 'free_user',
            ],
            [
                'email' => 'support@vertexcontas.com',
                'first_name' => 'Suporte',
                'last_name' => 'Técnico',
                'role' => 'support',
            ],
        ];

        foreach ($demoUsers as $demo) {
            $user = User::updateOrCreate(
                ['email' => $demo['email']],
                [
                    'first_name' => $demo['first_name'],
                    'last_name' => $demo['last_name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->syncRoles([$demo['role']]);
        }
    }
}
