<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Mostrar todos los planes activos
     */
    public function index()
    {
        $oneTimePlans = Plan::active()
            ->oneTime()
            ->orderBy('sort_order')
            ->get();

        $subscriptionPlans = Plan::active()
            ->subscription()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('duration_months');

        return view('public.plans.index', compact('oneTimePlans', 'subscriptionPlans'));
    }

    /**
     * Mostrar detalles de un plan específico
     */
    public function show(Plan $plan)
    {
        if (!$plan->is_active) {
            abort(404, 'Plan no disponible');
        }

        return view('public.plans.show', compact('plan'));
    }
}
