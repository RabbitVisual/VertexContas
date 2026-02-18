<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Shim so "php artisan db:seed --class=EmailTemplatesSeeder" works.
 * Delegates to the Core module EmailTemplatesSeeder.
 */
class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(\Modules\Core\Database\Seeders\EmailTemplatesSeeder::class);
    }
}
