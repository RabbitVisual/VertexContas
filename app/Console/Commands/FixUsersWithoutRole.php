<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixUsersWithoutRole extends Command
{
    protected $signature = 'users:fix-missing-role';

    protected $description = 'Atribui o papel free_user a usuários que não têm nenhum papel (corrige 403 após registro).';

    public function handle(): int
    {
        $usersWithoutRole = User::whereDoesntHave('roles')->get();

        if ($usersWithoutRole->isEmpty()) {
            $this->info('Nenhum usuário sem papel encontrado.');

            return self::SUCCESS;
        }

        foreach ($usersWithoutRole as $user) {
            $user->assignRole('free_user');
            $this->line("  → {$user->email} (ID: {$user->id})");
        }

        $this->info("Corrigido: {$usersWithoutRole->count()} usuário(s) com papel free_user.");

        return self::SUCCESS;
    }
}
