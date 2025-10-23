<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class PlanManagementController extends Controller
{
    /**
     * Mostrar configuración de planes
     */
    public function index()
    {
        $plans = Plan::orderBy('type')->orderBy('sort_order')->get();

        $settings = [
            'whatsapp_number' => Setting::get('whatsapp_number', '50670000000'),
            'whatsapp_message' => Setting::get('whatsapp_message', 'Hola, necesito ayuda con QR Pet Tag'),
            'email_monthly_limit' => Setting::get('email_monthly_limit', 500),
            'admin_email' => Setting::get('admin_email', config('mail.from.address')),
        ];

        // Estadísticas de emails
        $emailStats = [
            'monthly_count' => EmailLog::getMonthlyCount(),
            'daily_count' => EmailLog::getDailyCount(),
            'monthly_limit' => $settings['email_monthly_limit'],
            'percentage_used' => ($settings['email_monthly_limit'] > 0)
                ? round((EmailLog::getMonthlyCount() / $settings['email_monthly_limit']) * 100, 2)
                : 0,
            'is_near_limit' => EmailLog::isNearLimit($settings['email_monthly_limit']),
        ];

        return view('admin.plans.index', compact('plans', 'settings', 'emailStats'));
    }

    /**
     * Actualizar un plan
     */
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pets_included' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'additional_pet_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        $plan->update($request->only([
            'name',
            'pets_included',
            'price',
            'additional_pet_price',
            'is_active',
            'description',
        ]));

        return back()->with('success', 'Plan actualizado exitosamente');
    }

    /**
     * Actualizar configuraciones generales
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string|max:20',
            'whatsapp_message' => 'required|string|max:500',
            'email_monthly_limit' => 'required|integer|min:1',
            'admin_email' => 'required|email',
        ]);

        Setting::set('whatsapp_number', $request->whatsapp_number, 'string', 'contact');
        Setting::set('whatsapp_message', $request->whatsapp_message, 'string', 'contact');
        Setting::set('email_monthly_limit', $request->email_monthly_limit, 'integer', 'email');
        Setting::set('admin_email', $request->admin_email, 'string', 'general');

        return back()->with('success', 'Configuración actualizada exitosamente');
    }

    /**
     * Activar/desactivar un plan
     */
    public function toggleActive(Plan $plan)
    {
        $plan->update([
            'is_active' => !$plan->is_active,
        ]);

        $status = $plan->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Plan {$status} exitosamente");
    }
}
