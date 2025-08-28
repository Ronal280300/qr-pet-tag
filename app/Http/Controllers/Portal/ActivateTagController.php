<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivateTagController extends Controller
{
    public function create()
    {
        // Formulario simple: solo código de activación
        return view('portal.activate-tag');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'activation_code' => ['required', 'string'],
        ]);

        $qr = QrCode::with('pet')->where('activation_code', $data['activation_code'])->first();

        if (!$qr) {
            return back()->withErrors(['activation_code' => 'Código de activación inválido.'])->withInput();
        }

        // El TAG debe tener una mascota precargada por el admin
        if (!$qr->pet) {
            return back()->withErrors(['activation_code' => 'Este TAG aún no tiene una mascota asociada. Contáctanos.'])->withInput();
        }

        $pet = $qr->pet;

        // Si ya tiene dueño y no es el actual → bloquear
        if (!is_null($pet->user_id) && $pet->user_id !== Auth::id()) {
            return back()->withErrors(['activation_code' => 'Este TAG ya pertenece a otra cuenta.'])->withInput();
        }

        // Asignar la mascota al usuario (reclamar)
        $pet->user_id = Auth::id();
        $pet->save();

        // Marcar TAG como activado (si no lo está)
        if (!$qr->is_activated) {
            $qr->is_activated = true;
        }
        $qr->activated_at = now();
        $qr->activated_by = Auth::id();
        $qr->save();

        return redirect()->route('portal.pets.show', $pet)
            ->with('status', 'TAG activado. Ya puedes editar los datos de tu mascota.');
    }
}