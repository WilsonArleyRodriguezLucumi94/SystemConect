<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generar facturación diaria
Schedule::command('app:check-daily-invoices')->dailyAt('00:00');

// Suspender clientes morosos en MikroTik (5 minutos después)
Schedule::command('isp:corte-mora')->dailyAt('00:05');