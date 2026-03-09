<?php

declare(strict_types=1);

namespace Modules\PWA\Console;

use Illuminate\Console\Command;
use Modules\PWA\Models\PwaVersion;

class PwaReleaseCommand extends Command
{
    protected $signature = 'pwa:release
                            {version? : Versão a publicar (ex: v2). Se omitido, usa PWA_CACHE_VERSION do .env}
                            {--notes= : Notas da versão (opcional)}
                            {--force : Exigir atualização em todos os clientes}
                            {--no-force : Não exigir atualização (padrão)}';

    protected $description = 'Publica uma nova versão do PWA para que todos os clientes recebam a atualização (admin/pwa/versions).';

    public function handle(): int
    {
        $version = $this->argument('version') ?? config('pwa.cache_version', 'v1');
        $notes = $this->option('notes') ?: $this->defaultReleaseNotes();
        $isForceUpdate = $this->option('no-force')
            ? false
            : ($this->option('force') ? true : $this->confirm('Exigir atualização? (clientes verão aviso para recarregar o app)', true));

        if (PwaVersion::where('version', $version)->exists()) {
            $this->warn("A versão \"{$version}\" já existe em admin/pwa/versions. Nada a fazer.");
            $this->line('Defina PWA_CACHE_VERSION=' . $version . ' no .env para o Service Worker usar esta versão.');
            return self::SUCCESS;
        }

        PwaVersion::create([
            'version' => $version,
            'release_notes' => $notes,
            'is_force_update' => $isForceUpdate,
            'released_at' => now(),
        ]);

        $this->info("Versão PWA \"{$version}\" publicada com sucesso.");
        $this->line('Clientes que acessarem o app receberão a notificação de atualização.');
        if ($isForceUpdate) {
            $this->line('Exigir atualização: ativado (usuários verão aviso para recarregar).');
        }
        $cacheVersion = config('pwa.cache_version');
        if ($cacheVersion !== $version) {
            $this->newLine();
            $this->comment("Dica: no .env defina PWA_CACHE_VERSION={$version} para o Service Worker usar esta versão e invalidar cache antigo.");
        }

        return self::SUCCESS;
    }

    private function defaultReleaseNotes(): string
    {
        return 'PWA completo: manifest com ícones e shortcuts, Service Worker com cache offline, página offline, layout responsivo com safe-area e banner de instalação.';
    }
}
