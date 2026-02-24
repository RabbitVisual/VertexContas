<?php

declare(strict_types=1);

namespace Modules\Core\Listeners;

use Modules\Core\Events\GoalCompleted;
use Modules\Notifications\Services\NotificationService;

class NotifyUserGoalCompleted
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function handle(GoalCompleted $event): void
    {
        $goal = $event->goal;
        $user = $goal->user;

        if (! $user) {
            return;
        }

        $this->notificationService->sendToUser(
            $user,
            'Meta atingida!',
            'Parabéns! Você atingiu a meta "' . $goal->name . '". Esse valor faz parte do seu planejamento de economia.',
            'success',
            route('core.goals.index'),
            'trophy',
            'text-emerald-500'
        );
    }
}
