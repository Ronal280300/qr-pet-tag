<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\EmailLog;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios automáticos de pago 1 día antes del vencimiento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando clientes con planes por vencer...');

        // Buscar usuarios con plan activo que vence mañana
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        $users = User::where('is_admin', false)
            ->where('plan_is_active', true)
            ->whereNotNull('plan_expires_at')
            ->whereBetween('plan_expires_at', [$tomorrow, $endOfTomorrow])
            ->with('currentPlan')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No hay clientes con planes que venzan mañana.');
            return 0;
        }

        $this->info("Encontrados {$users->count()} clientes. Enviando recordatorios...");

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::send('emails.client.payment-reminder', [
                    'user' => $user,
                    'plan' => $user->currentPlan,
                    'expiresAt' => $user->plan_expires_at,
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('⏰ Tu plan vence mañana - ' . config('app.name'));
                    $message->getSwiftMessage()->setContentType('text/html; charset=UTF-8');
                });

                EmailLog::logEmail(
                    recipient: $user->email,
                    subject: 'Recordatorio Automático de Pago',
                    type: 'auto_payment_reminder',
                    userId: $user->id,
                    status: 'sent'
                );

                // Enviar WhatsApp al cliente
                $whatsapp = app(WhatsAppService::class);
                $whatsapp->sendPaymentReminder($user, true);

                $sent++;
                $this->info("✓ Recordatorio enviado a: {$user->name} ({$user->email})");

            } catch (\Exception $e) {
                EmailLog::logEmail(
                    recipient: $user->email,
                    subject: 'Recordatorio Automático de Pago',
                    type: 'auto_payment_reminder',
                    userId: $user->id,
                    status: 'failed',
                    errorMessage: $e->getMessage()
                );

                $failed++;
                $this->error("✗ Error al enviar a {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info("\n=== Resumen ===");
        $this->info("Enviados: {$sent}");
        $this->info("Fallidos: {$failed}");

        return 0;
    }
}
