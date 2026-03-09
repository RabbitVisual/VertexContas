<?php

declare(strict_types=1);

namespace Modules\Notifications\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Core\Services\FinancialHealthService;
use Modules\Notifications\Services\NotificationService;

class SendSmartInsights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vertex:send-insights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia notificações inteligentes baseadas na regra 50/30/20 para usuários Free e Pro.';

    public function __construct(
        protected FinancialHealthService $financialHealth,
        protected NotificationService $notificationService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Enviando insights financeiros para usuários ativos...');

        $now = now();
        $month = (int) $now->month;
        $year = (int) $now->year;
        $isWeekendOrFriday = in_array($now->dayOfWeekIso, [5, 6, 7], true);

        User::whereNull('deleted_at')
            ->whereNotNull('email')
            ->chunkById(200, function ($users) use ($month, $year, $isWeekendOrFriday): void {
                foreach ($users as $user) {
                    /** @var \App\Models\User $user */
                    if (! $user->hasVerifiedEmail()) {
                        continue;
                    }

                    if ($user->isPro()) {
                        $this->handleProUser($user, $month, $year);
                    } elseif ($isWeekendOrFriday) {
                        $this->handleFreeUser($user);
                    }
                }
            });

        $this->info('Envio de insights finalizado.');

        return self::SUCCESS;
    }

    protected function handleProUser(User $user, int $month, int $year): void
    {
        $distribution = $this->financialHealth->calculate503020Distribution($user->id, $month, $year);
        $pillars = $distribution['pillars'] ?? [];
        $wants = $pillars['wants'] ?? null;

        if (! $wants) {
            return;
        }

        $percentage = (float) ($wants['percentage'] ?? 0.0);
        if ($percentage <= 25.0 || $percentage > 30.0) {
            return;
        }

        $title = 'Mentor VIP: Alerta de Desejos 🍔';
        $message = 'Você está quase atingindo seu limite saudável de 30% para desejos este mês. Um pequeno ajuste agora garante o seu futuro!';

        $this->notificationService->sendToUser(
            $user,
            $title,
            $message,
            'warning',
            null,
            'crown',
            'text-amber-400'
        );
    }

    protected function handleFreeUser(User $user): void
    {
        $title = 'Lembrete Amigável 📝';
        $message = 'Tire 5 minutinhos hoje para registrar seus gastos e manter sua bússola 50/30/20 atualizada!';

        $this->notificationService->sendToUser(
            $user,
            $title,
            $message,
            'info',
            null,
            'bell',
            'text-sky-500'
        );
    }
}

