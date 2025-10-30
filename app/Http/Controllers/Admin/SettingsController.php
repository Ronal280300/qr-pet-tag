<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
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

        return view('admin.settings.index', compact('groups', 'settings'));
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
        Artisan::call('db:seed', ['--class' => 'SettingsSeeder', '--force' => true]);
        Cache::forget('settings.all');
        Cache::flush();

        return back()->with('success', 'Configuración reseteada a valores por defecto.');
    }
}
