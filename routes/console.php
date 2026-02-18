<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Processar transações recorrentes agendadas (Repetir) diariamente ao amanhecer
Schedule::command('core:run-recurring')->dailyAt('06:00');

// Processar fila de e-mails e jobs (para shared hosting: cron * * * * * php artisan schedule:run)
Schedule::command('queue:work database --stop-when-empty --max-time=55')->everyMinute();

// Limpar notificações lidas antigas (retenção configurável no Admin)
Schedule::command('notifications:prune')->daily();
