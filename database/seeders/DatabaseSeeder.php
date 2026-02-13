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

        // Create a default admin user
        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@vertexcontas.com',
            'password' => bcrypt('password'), // password
        ]);
        $admin->assignRole('admin');

        // Create a default free user
        $user = User::factory()->create([
            'first_name' => 'Free',
            'last_name' => 'User',
            'email' => 'user@vertexcontas.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('free_user');
    }
}
