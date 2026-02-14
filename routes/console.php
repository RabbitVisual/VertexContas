<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Processar transações recorrentes agendadas (Repetir) diariamente ao amanhecer
Schedule::command('core:run-recurring')->dailyAt('06:00');
