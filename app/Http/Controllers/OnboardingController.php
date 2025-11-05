<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar formulario de onboarding
     */
    public function show()
    {
        $user = Auth::user();

        // Si ya tiene teléfono, redirigir a donde corresponda
        if ($user->phone) {
            return redirect()->route('plans.index');
        }

        return view('auth.onboarding');
    }

    /**
     * Guardar número de teléfono del onboarding
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20|unique:users,phone,' . Auth::id(),
        ], [
            'phone.required' => 'El número de teléfono es requerido.',
            'phone.unique' => 'Este número de teléfono ya está registrado.',
        ]);

        $user = Auth::user();
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('plans.index')
            ->with('success', '¡Perfil completado! Ahora puedes seleccionar un plan.');
    }
}
