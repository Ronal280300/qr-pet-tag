<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\EmailLog;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BlockExpiredAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:block-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bloquear cuentas que no han pagado después de 3 días del vencimiento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando cuentas con planes vencidos hace 3+ días...');

        // Buscar usuarios con plan vencido hace 3 o más días
        $threeDaysAgo = now()->subDays(3)->endOfDay();

        $users = User::where('is_admin', false)
            ->where('plan_is_active', true)
            ->whereNotNull('plan_expires_at')
            ->where('plan_expires_at', '<=', $threeDaysAgo)
            ->with('currentPlan')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No hay cuentas para bloquear.');
            return 0;
        }

        $this->info("Encontrados {$users->count()} usuarios. Bloqueando cuentas...");

        $blocked = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                // Bloquear cuenta
                $user->update([
                    'plan_is_active' => false,
                    'status' => 'inactive',
                    'status_changed_at' => now(),
                ]);

                // Enviar email de notificación
                Mail::send('emails.client.account-blocked', [
                    'user' => $user,
                    'plan' => $user->currentPlan,
                    'expiredAt' => $user->plan_expires_at,
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('⚠️ Cuenta suspendida por falta de pago - ' . config('app.name'));
                    $message->getSwiftMessage()->setContentType('text/html; charset=UTF-8');
                });

                EmailLog::logEmail(
                    recipient: $user->email,
                    subject: 'Cuenta suspendida por falta de pago',
                    type: 'account_blocked',
                    userId: $user->id,
                    status: 'sent'
                );

                // Enviar WhatsApp al cliente
                $whatsapp = app(WhatsAppService::class);
                $whatsapp->sendAccountBlocked($user);

                $blocked++;
                $this->info("✓ Cuenta bloqueada: {$user->name} ({$user->email})");

            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error al bloquear {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info("\n=== Resumen ===");
        $this->info("Bloqueadas: {$blocked}");
        $this->info("Fallidos: {$failed}");

        return 0;
    }
}
