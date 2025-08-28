<?php

namespace App\Http\Controllers;

use App\Models\QrCode;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function showPet(string $slug)
    {
        $qr = QrCode::with(['pet.user', 'pet.reward'])->where('slug', $slug)->firstOrFail();

        // Si el TAG no tiene mascota asociada, mostrar vista especial
        if (!$qr->pet) {
            return view('public.tag-pending', [
                'slug' => $slug,
                'qr'   => $qr,
            ]);
        }

        $pet   = $qr->pet;
        $owner = $pet->user; // puede ser null si aún no activó

        return view('public.pet', compact('qr', 'pet', 'owner'));
    }
}
