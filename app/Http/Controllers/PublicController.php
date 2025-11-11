<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Scan;

class PublicController extends Controller
{
    public function home()
    {
        // Obtener mascotas con fotos para el carousel
        $pets = \App\Models\Pet::with('photos')
            ->whereNotNull('user_id') // Solo mascotas con dueño
            ->where('is_lost', false)   // Excluir perdidas
            ->has('photos')             // Solo con fotos
            ->inRandomOrder()
            ->limit(20)                 // Máximo 20 para el carousel
            ->get();

        return view('public.home', compact('pets'));
    }

    public function showPet(string $slug)
    {
        $qr = QrCode::with(['pet.user', 'pet.reward'])->where('slug', $slug)->firstOrFail();

        // Registrar el escaneo del QR
        Scan::create([
            'qr_code_id' => $qr->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'location'   => null, // Se puede agregar geolocalización después
        ]);

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
