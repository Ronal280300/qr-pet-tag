<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define el schedule de comandos.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('clients:enforce-status')->dailyAt('00:30');

        // Enviar recordatorios de pago 1 día antes del vencimiento
        $schedule->command('payments:send-reminders')->dailyAt('09:00');

        // Bloquear cuentas con más de 3 días de vencimiento
        $schedule->command('accounts:block-expired')->dailyAt('01:00');
    }

    /**
     * Registra comandos (Laravel 12 los autodetecta en app/Console/Commands).
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
