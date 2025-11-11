<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminOnly::class);
    }

    /**
     * Mostrar configuración (con tabs)
     */
    public function index()
    {
        $groups = [
            'general' => 'General',
            'contact' => 'Contacto',
            'theme' => 'Tema y Colores',
            'twilio' => 'Twilio / WhatsApp',
            'notifications' => 'Notificaciones',
            'email' => 'Configuración Email',
            'social' => 'Redes Sociales',
        ];

        // Obtener settings agrupados
        $settings = [];
        foreach (array_keys($groups) as $group) {
            $settings[$group] = Setting::getByGroup($group);
        }

        // Si no hay settings, crear defaults
        $hasSettings = false;
        foreach ($settings as $group) {
            if ($group->isNotEmpty()) {
                $hasSettings = true;
                break;
            }
        }

        if (!$hasSettings) {
            $this->createDefaultSettings();
            // Recargar
            foreach (array_keys($groups) as $group) {
                $settings[$group] = Setting::getByGroup($group);
            }
        }

        return view('portal.admin.settings.index', compact('groups', 'settings'));
    }

    /**
     * Actualizar configuraciones
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Buscar el setting
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                continue;
            }

            // Convertir checkboxes (si no viene, es false)
            if ($setting->type === 'boolean') {
                $value = $request->has($key) ? '1' : '0';
            }

            // Actualizar
            $setting->value = $value;
            $setting->save();
        }

        // Limpiar cache
        Cache::forget('settings.all');
        Cache::flush();

        // Si se actualizó la configuración de Twilio, actualizar config en tiempo real
        if ($request->has('twilio_sid') || $request->has('twilio_token')) {
            config([
                'services.twilio.sid' => Setting::get('twilio_sid'),
                'services.twilio.token' => Setting::get('twilio_token'),
                'services.twilio.whatsapp_from' => Setting::get('twilio_whatsapp_from'),
                'services.twilio.admin_phone' => Setting::get('twilio_admin_phone'),
            ]);
        }

        // Si se actualizó la configuración de email, actualizar config
        if ($request->has('mail_from_name') || $request->has('mail_from_address')) {
            config([
                'mail.from.name' => Setting::get('mail_from_name'),
                'mail.from.address' => Setting::get('mail_from_address'),
            ]);
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    /**
     * Limpiar cache de configuración
     */
    public function clearCache()
    {
        Cache::forget('settings.all');
        Cache::flush();
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return back()->with('success', 'Cache de configuración limpiado correctamente.');
    }

    /**
     * Resetear configuración a valores por defecto
     */
    public function reset()
    {
        Setting::query()->delete();
        $this->createDefaultSettings();
        Cache::forget('settings.all');
        Cache::flush();

        return back()->with('success', 'Configuración reseteada a valores por defecto.');
    }

    /**
     * Crear configuraciones por defecto
     */
    private function createDefaultSettings()
    {
        $defaults = [
            // General
            ['key' => 'site_name', 'value' => 'QR Pet Tag', 'type' => 'string', 'group' => 'general', 'label' => 'Nombre del Sitio', 'description' => 'Nombre que aparece en el encabezado', 'order' => 1],
            ['key' => 'site_description', 'value' => 'Sistema de identificación de mascotas con código QR', 'type' => 'string', 'group' => 'general', 'label' => 'Descripción', 'description' => 'Descripción del sitio', 'order' => 2],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'label' => 'Modo Mantenimiento', 'description' => 'Activar modo mantenimiento', 'order' => 3],

            // Contacto
            ['key' => 'contact_email', 'value' => 'info.qrpettag@gmail.com', 'type' => 'string', 'group' => 'contact', 'label' => 'Email de Contacto', 'description' => 'Email para notificaciones', 'order' => 1],
            ['key' => 'contact_phone', 'value' => '+506 6290-1184', 'type' => 'string', 'group' => 'contact', 'label' => 'Teléfono', 'description' => 'Teléfono de contacto', 'order' => 2],
            ['key' => 'whatsapp_number', 'value' => '50662901184', 'type' => 'string', 'group' => 'contact', 'label' => 'WhatsApp', 'description' => 'Número de WhatsApp (con código de país)', 'order' => 3],
            ['key' => 'contact_address', 'value' => 'San José, Costa Rica', 'type' => 'string', 'group' => 'contact', 'label' => 'Dirección', 'description' => 'Dirección física', 'order' => 4],

            // Tema y Colores
            ['key' => 'primary_color', 'value' => '#115DFC', 'type' => 'color', 'group' => 'theme', 'label' => 'Color Primario', 'description' => 'Color principal del sitio', 'order' => 1],
            ['key' => 'secondary_color', 'value' => '#3466ff', 'type' => 'color', 'group' => 'theme', 'label' => 'Color Secundario', 'description' => 'Color secundario', 'order' => 2],
            ['key' => 'accent_color', 'value' => '#00D4FF', 'type' => 'color', 'group' => 'theme', 'label' => 'Color Acento', 'description' => 'Color de acento', 'order' => 3],

            // Email
            ['key' => 'mail_from_name', 'value' => 'QR Pet Tag', 'type' => 'string', 'group' => 'email', 'label' => 'Nombre del Remitente', 'description' => 'Nombre en correos salientes', 'order' => 1],
            ['key' => 'mail_from_address', 'value' => 'noreply@qrpettag.com', 'type' => 'string', 'group' => 'email', 'label' => 'Email del Remitente', 'description' => 'Email de salida', 'order' => 2],
            ['key' => 'admin_notification_email', 'value' => 'info.qrpettag@gmail.com', 'type' => 'string', 'group' => 'email', 'label' => 'Email Admin', 'description' => 'Email para notificaciones de órdenes', 'order' => 3],

            // Notificaciones
            ['key' => 'notifications_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Habilitar Notificaciones', 'description' => 'Activar sistema de notificaciones', 'order' => 1],
            ['key' => 'notify_new_order', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notificar Nuevas Órdenes', 'description' => 'Notificar al admin de nuevas órdenes', 'order' => 2],

            // Redes Sociales
            ['key' => 'facebook_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Facebook', 'description' => 'URL de Facebook', 'order' => 1],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Instagram', 'description' => 'URL de Instagram', 'order' => 2],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Twitter/X', 'description' => 'URL de Twitter', 'order' => 3],
        ];

        foreach ($defaults as $setting) {
            Setting::create($setting);
        }
    }
}
