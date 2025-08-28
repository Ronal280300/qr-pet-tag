<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\QrCode as QrCodeModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class PetQrService
{
    /**
     * Asegura slug público, URL pública, imagen del QR y activation_code.
     * Guarda el modelo $qr al final.
     */
    public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): void
    {
        // 1) Si es un QR nuevo, genera un activation_code único
        if (!$qr->exists || empty($qr->activation_code)) {
            $qr->activation_code = $this->generateUniqueActivationCode();
        }

        // 2) Slug estable (nombre + id) si no existe
        if (empty($qr->slug)) {
            $qr->slug = Str::slug($pet->name) . '-' . $pet->id;
        }

        // 3) URL pública del perfil
        $publicUrl = route('public.pet.show', ['slug' => $qr->slug]);
        $qr->qr_code = $publicUrl;

        // 4) Generar imagen del QR (SVG para evitar dependencia de Imagick)
        $folder   = 'qrcodes';
        $filename = $qr->slug . '.svg';
        $path     = $folder . '/' . $filename;

        $svg = QrCodeFacade::format('svg')
            ->size(800)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($publicUrl);

        Storage::disk('public')->put($path, $svg);

        $qr->image = $path;

        // 5) Guardar
        $qr->save();
    }

    /**
     * Genera un activation_code único (legible y con buen “feel” para imprimir).
     * Ej: ABCD-EFGH-1234
     */
    public function generateUniqueActivationCode(): string
    {
        do {
            $code = strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . random_int(1000, 9999);
        } while (QrCodeModel::where('activation_code', $code)->exists());

        return $code;
    }
}