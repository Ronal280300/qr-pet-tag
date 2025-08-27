<?php

namespace App\Http\Controllers;

use App\Models\QrCode as QrCodeModel;
use App\Models\Scan;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    /**
     * Muestra el perfil público de la mascota al escanear el QR.
     * Registra un scan con IP, UA y referrer.
     */
    public function showPet(string $slug, Request $request)
    {
        $qr = QrCodeModel::with(['pet.user', 'pet.reward'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Log de escaneo
        try {
            Scan::create([
                'qr_code_id' => $qr->id,
                'ip'         => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'referrer'   => substr($request->headers->get('referer') ?? '', 0, 255),
            ]);
        } catch (\Throwable $e) {
            // No romper la UX si falla el log
            report($e);
        }

        // Renderizar vista pública (la del Punto 4). De momento pasamos el modelo.
        // Si la mascota está marcada como "perdida/robada", la vista mostrará aviso.
        return view('public.pet', [
            'qr'  => $qr,
            'pet' => $qr->pet,
            'owner' => $qr->pet->user,
            'reward' => $qr->pet->reward,
        ]);
    }
}