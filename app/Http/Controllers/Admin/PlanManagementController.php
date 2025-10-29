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
            'allows_additional_pets' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $plan->update($request->only([
            'name',
            'pets_included',
            'price',
            'additional_pet_price',
            'is_active',
            'allows_additional_pets',
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
            'support_whatsapp' => 'nullable|string|max:20',
            'support_email' => 'nullable|email|max:255',
            'email_monthly_limit' => 'nullable|integer|min:1',
            'grace_days_before_block' => 'nullable|integer|min:0',
        ]);

        if ($request->filled('support_whatsapp')) {
            Setting::set('support_whatsapp', $request->support_whatsapp, 'string', 'contact');
        }

        if ($request->filled('support_email')) {
            Setting::set('support_email', $request->support_email, 'string', 'contact');
        }

        if ($request->filled('email_monthly_limit')) {
            Setting::set('email_monthly_limit', $request->email_monthly_limit, 'integer', 'email');
        }

        if ($request->filled('grace_days_before_block')) {
            Setting::set('grace_days_before_block', $request->grace_days_before_block, 'integer', 'system');
        }

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

    /**
     * Crear un nuevo plan
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:one_time,subscription',
            'pets_included' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'additional_pet_price' => 'required|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'allows_additional_pets' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $data = $request->only([
            'name',
            'type',
            'pets_included',
            'price',
            'additional_pet_price',
            'duration_months',
            'description',
        ]);

        $data['is_active'] = $request->boolean('is_active', false);
        $data['allows_additional_pets'] = $request->boolean('allows_additional_pets', true);

        // Obtener el último sort_order para este tipo
        $maxSortOrder = Plan::where('type', $data['type'])->max('sort_order') ?? 0;
        $data['sort_order'] = $maxSortOrder + 1;

        Plan::create($data);

        return back()->with('success', 'Plan creado exitosamente');
    }

    /**
     * Eliminar un plan
     */
    public function destroy(Plan $plan)
    {
        // Verificar si el plan tiene órdenes asociadas
        $ordersCount = $plan->orders()->count();

        if ($ordersCount > 0) {
            return back()->with('error', "No se puede eliminar el plan porque tiene {$ordersCount} órdenes asociadas. Puedes desactivarlo en su lugar.");
        }

        // Verificar si hay usuarios actualmente usando este plan
        $activeUsersCount = \App\Models\User::where('current_plan_id', $plan->id)
            ->where('plan_is_active', true)
            ->count();

        if ($activeUsersCount > 0) {
            return back()->with('error', "No se puede eliminar el plan porque {$activeUsersCount} usuarios lo están usando actualmente. Puedes desactivarlo en su lugar.");
        }

        $planName = $plan->name;
        $plan->delete();

        return back()->with('success', "Plan '{$planName}' eliminado exitosamente");
    }
}
