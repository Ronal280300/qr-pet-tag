<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'site_name',
                'value' => config('app.name', 'QR Pet Tag'),
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nombre del Sitio',
                'description' => 'Nombre que aparece en el navegador y correos',
                'order' => 1,
            ],
            [
                'key' => 'site_description',
                'value' => 'Sistema de identificación de mascotas mediante códigos QR',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Descripción del Sitio',
                'description' => 'Descripción breve del sitio para SEO',
                'order' => 2,
            ],
            [
                'key' => 'site_timezone',
                'value' => 'America/Costa_Rica',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Zona Horaria',
                'description' => 'Zona horaria del sistema',
                'order' => 3,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Modo Mantenimiento',
                'description' => 'Activar modo mantenimiento (solo admin puede acceder)',
                'order' => 4,
            ],
            [
                'key' => 'plans_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Habilitar Planes',
                'description' => 'Habilitar sistema de planes y pagos',
                'order' => 5,
            ],

            // Contacto
            [
                'key' => 'contact_email',
                'value' => config('mail.from.address', 'info@qrpettag.com'),
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email de Contacto',
                'description' => 'Email principal del sitio (aparece en correos y formularios)',
                'order' => 1,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+50688888888',
                'type' => 'tel',
                'group' => 'contact',
                'label' => 'Teléfono de Contacto',
                'description' => 'Teléfono principal del sitio',
                'order' => 2,
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '+50688888888',
                'type' => 'tel',
                'group' => 'contact',
                'label' => 'WhatsApp de Contacto',
                'description' => 'Número de WhatsApp para atención al cliente',
                'order' => 3,
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '50670000000',
                'type' => 'tel',
                'group' => 'contact',
                'label' => 'Número WhatsApp (Legacy)',
                'description' => 'Número de WhatsApp para soporte (formato antiguo)',
                'order' => 4,
            ],
            [
                'key' => 'whatsapp_message',
                'value' => 'Hola, necesito ayuda con mi pedido de QR Pet Tag',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Mensaje WhatsApp Predeterminado',
                'description' => 'Mensaje predeterminado al hacer clic en WhatsApp',
                'order' => 5,
            ],
            [
                'key' => 'contact_address',
                'value' => 'San José, Costa Rica',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Dirección',
                'description' => 'Dirección física de la empresa',
                'order' => 6,
            ],
            [
                'key' => 'admin_email',
                'value' => config('mail.from.address', 'admin@qrpettag.com'),
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email Admin',
                'description' => 'Email del administrador para notificaciones',
                'order' => 7,
            ],

            // Tema / Colores
            [
                'key' => 'theme_primary_color',
                'value' => '#3b82f6',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Primario',
                'description' => 'Color principal del sitio (botones, enlaces, etc.)',
                'order' => 1,
            ],
            [
                'key' => 'theme_secondary_color',
                'value' => '#8b5cf6',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Secundario',
                'description' => 'Color secundario del sitio',
                'order' => 2,
            ],
            [
                'key' => 'theme_success_color',
                'value' => '#10b981',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Éxito',
                'description' => 'Color para mensajes de éxito',
                'order' => 3,
            ],
            [
                'key' => 'theme_danger_color',
                'value' => '#ef4444',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Peligro',
                'description' => 'Color para mensajes de error',
                'order' => 4,
            ],
            [
                'key' => 'theme_warning_color',
                'value' => '#f59e0b',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Advertencia',
                'description' => 'Color para mensajes de advertencia',
                'order' => 5,
            ],
            [
                'key' => 'theme_info_color',
                'value' => '#3b82f6',
                'type' => 'color',
                'group' => 'theme',
                'label' => 'Color Información',
                'description' => 'Color para mensajes informativos',
                'order' => 6,
            ],

            // Twilio / WhatsApp
            [
                'key' => 'twilio_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'twilio',
                'label' => 'Habilitar Twilio WhatsApp',
                'description' => 'Activar o desactivar envío de mensajes WhatsApp',
                'order' => 1,
            ],
            [
                'key' => 'twilio_sid',
                'value' => env('TWILIO_SID', ''),
                'type' => 'text',
                'group' => 'twilio',
                'label' => 'Twilio Account SID',
                'description' => 'Account SID de Twilio (desde console.twilio.com)',
                'order' => 2,
            ],
            [
                'key' => 'twilio_token',
                'value' => env('TWILIO_AUTH_TOKEN', ''),
                'type' => 'password',
                'group' => 'twilio',
                'label' => 'Twilio Auth Token',
                'description' => 'Token de autenticación de Twilio',
                'order' => 3,
            ],
            [
                'key' => 'twilio_whatsapp_from',
                'value' => env('TWILIO_WHATSAPP_FROM', ''),
                'type' => 'tel',
                'group' => 'twilio',
                'label' => 'Número WhatsApp Twilio',
                'description' => 'Número de WhatsApp de Twilio (formato: +14155238886)',
                'order' => 4,
            ],
            [
                'key' => 'twilio_admin_phone',
                'value' => env('ADMIN_WHATSAPP_PHONE', ''),
                'type' => 'tel',
                'group' => 'twilio',
                'label' => 'WhatsApp Admin',
                'description' => 'Número de WhatsApp del administrador para recibir notificaciones',
                'order' => 5,
            ],

            // Notificaciones
            [
                'key' => 'notifications_email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Habilitar Emails',
                'description' => 'Activar o desactivar envío de correos electrónicos',
                'order' => 1,
            ],
            [
                'key' => 'notifications_whatsapp_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Habilitar WhatsApp',
                'description' => 'Activar o desactivar envío de mensajes WhatsApp',
                'order' => 2,
            ],
            [
                'key' => 'notifications_qr_scan_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Notificar Escaneo QR',
                'description' => 'Enviar notificación cuando se escanea un QR',
                'order' => 3,
            ],

            // Redes Sociales
            [
                'key' => 'social_facebook',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Facebook',
                'description' => 'Página de Facebook de la empresa',
                'order' => 1,
            ],
            [
                'key' => 'social_instagram',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Instagram',
                'description' => 'Perfil de Instagram de la empresa',
                'order' => 2,
            ],
            [
                'key' => 'social_twitter',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Twitter/X',
                'description' => 'Perfil de Twitter/X de la empresa',
                'order' => 3,
            ],
            [
                'key' => 'social_youtube',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL YouTube',
                'description' => 'Canal de YouTube de la empresa',
                'order' => 4,
            ],

            // Email Config
            [
                'key' => 'mail_from_name',
                'value' => config('mail.from.name', 'QR Pet Tag'),
                'type' => 'text',
                'group' => 'email',
                'label' => 'Nombre Remitente Email',
                'description' => 'Nombre que aparece como remitente en los emails',
                'order' => 1,
            ],
            [
                'key' => 'mail_from_address',
                'value' => config('mail.from.address', 'noreply@qrpettag.com'),
                'type' => 'email',
                'group' => 'email',
                'label' => 'Email Remitente',
                'description' => 'Email que aparece como remitente',
                'order' => 2,
            ],
            [
                'key' => 'email_monthly_limit',
                'value' => '500',
                'type' => 'number',
                'group' => 'email',
                'label' => 'Límite Mensual de Emails',
                'description' => 'Límite mensual de emails a enviar (Gmail)',
                'order' => 3,
            ],
            [
                'key' => 'email_warning_threshold',
                'value' => '0.8',
                'type' => 'number',
                'group' => 'email',
                'label' => 'Umbral de Advertencia',
                'description' => 'Porcentaje del límite para mostrar advertencia (0.8 = 80%)',
                'order' => 4,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
