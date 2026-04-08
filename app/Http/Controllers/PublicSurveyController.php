<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class PublicSurveyController extends Controller
{
    /**
     * Mostrar el formulario de encuesta público
     */
    public function show(Request $request)
    {
        return view('public.survey', [
            'source' => $request->query('utm_source', 'direct'),
        ]);
    }

    /**
     * Almacenar respuesta de encuesta
     *
     * Recibe JSON desde el wizard de pasos (AJAX fetch + JSON.stringify).
     * El 422 anterior se debía a que:
     *   1. FormData no captura radios de pasos inactivos
     *   2. likelihood_score llegaba como string "5" y la regla 'integer' fallaba
     *   3. Las reglas 'in:' no contemplaban todos los valores enviados
     */
    public function store(Request $request)
    {
        // Cast explícito antes de validar (llega como string desde JSON)
        $request->merge([
            'likelihood_score' => (int) $request->input('likelihood_score', 0),
        ]);

        $validated = $request->validate([
            'has_pets'         => 'required|string|in:si,no',
            'pet_type'         => 'nullable|string|in:perro,gato,ambos,otro',
            'main_concern'     => 'required|string|in:se_pierda,salud,identificacion,robo,otro',
            'lost_pet_before'  => 'required|string|in:si,no,conozco_alguien',
            'would_buy'        => 'required|string|in:definitivamente_si,probablemente_si,no_estoy_seguro,probablemente_no,definitivamente_no',
            'price_range'      => 'required|string|in:pago_unico,pago_anual,suscripcion_mensual,solo_placa',
            'likelihood_score' => 'required|integer|min:1|max:10',
            'email'            => 'nullable|email|max:255',
            'source'           => 'nullable|string|max:100',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = substr($request->userAgent() ?? '', 0, 500);
        $validated['referrer']   = substr($request->header('referer', ''), 0, 500);
        $validated['source']     = $request->input('source', 'direct');

        SurveyResponse::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Respuesta registrada correctamente.',
        ]);
    }
}
