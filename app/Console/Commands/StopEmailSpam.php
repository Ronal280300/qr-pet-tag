<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailCampaign;
use App\Models\CampaignRecipient;

class StopEmailSpam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:stop-spam {--force : Forzar detención sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detiene campañas que están enviando emails en bucle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->error('🚨 DETENCIÓN DE EMAILS EN SPAM');
        $this->newLine();

        // Buscar campañas en estado "sending"
        $sendingCampaigns = EmailCampaign::where('status', 'sending')->get();

        if ($sendingCampaigns->isEmpty()) {
            $this->info('✅ No hay campañas enviándose actualmente.');
            return 0;
        }

        $this->warn("⚠️  Se encontraron {$sendingCampaigns->count()} campañas en estado 'sending'");
        $this->newLine();

        // Mostrar detalles
        foreach ($sendingCampaigns as $campaign) {
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID', $campaign->id],
                    ['Nombre', $campaign->name],
                    ['Estado', $campaign->status],
                    ['Total destinatarios', $campaign->total_recipients],
                    ['Enviados', $campaign->sent_count],
                    ['Fallidos', $campaign->failed_count],
                    ['Iniciada', $campaign->started_at?->format('Y-m-d H:i:s')],
                ]
            );

            // Contar pendientes
            $pending = CampaignRecipient::where('email_campaign_id', $campaign->id)
                ->where('status', 'pending')
                ->count();

            $this->line("  📧 Destinatarios pendientes: {$pending}");
            $this->newLine();
        }

        // Confirmación
        if (!$this->option('force')) {
            if (!$this->confirm('¿Detener estas campañas y marcar emails pendientes como "cancelled"?', true)) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        // Detener campañas
        foreach ($sendingCampaigns as $campaign) {
            $this->info("Deteniendo campaña: {$campaign->name}");

            // Marcar destinatarios pendientes como cancelados
            $cancelled = CampaignRecipient::where('email_campaign_id', $campaign->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'error_message' => 'Cancelado por administrador para detener spam'
                ]);

            // Actualizar campaña
            $campaign->update([
                'status' => 'failed',
                'completed_at' => now(),
            ]);

            $this->line("  ✅ {$cancelled} emails cancelados");
        }

        $this->newLine();
        $this->info('🎉 Todas las campañas han sido detenidas');
        $this->newLine();
        $this->comment('💡 Verifica la configuración de email en .env antes de reenviar campañas');

        return 0;
    }
}
