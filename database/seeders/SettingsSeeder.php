<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuraciones generales
        Setting::create([
            'key' => 'email_monthly_limit',
            'value' => '500',
            'type' => 'integer',
            'group' => 'email',
            'description' => 'Límite mensual de emails a enviar (Gmail)',
        ]);

        Setting::create([
            'key' => 'email_warning_threshold',
            'value' => '0.8',
            'type' => 'string',
            'group' => 'email',
            'description' => 'Porcentaje del límite para mostrar advertencia (0.8 = 80%)',
        ]);

        Setting::create([
            'key' => 'whatsapp_number',
            'value' => '50670000000',
            'type' => 'string',
            'group' => 'contact',
            'description' => 'Número de WhatsApp para soporte',
        ]);

        Setting::create([
            'key' => 'whatsapp_message',
            'value' => 'Hola, necesito ayuda con mi pedido de QR Pet Tag',
            'type' => 'string',
            'group' => 'contact',
            'description' => 'Mensaje predeterminado de WhatsApp',
        ]);

        Setting::create([
            'key' => 'admin_email',
            'value' => 'admin@qrpettag.com',
            'type' => 'string',
            'group' => 'general',
            'description' => 'Email del administrador para notificaciones',
        ]);

        Setting::create([
            'key' => 'site_name',
            'value' => 'QR Pet Tag',
            'type' => 'string',
            'group' => 'general',
            'description' => 'Nombre del sitio',
        ]);

        Setting::create([
            'key' => 'plans_enabled',
            'value' => 'true',
            'type' => 'boolean',
            'group' => 'plans',
            'description' => 'Habilitar sistema de planes y pagos',
        ]);
    }
}
