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
     * Asegura slug público, activation_code e imagen del QR.
     * Guarda el modelo $qr al final.
     */
    public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): QrCodeModel
    {
        // 1) Slug estable (si no existe): nombre-normalizado + id
        if (empty($qr->slug)) {
            $base = Str::slug($pet->name ?: 'pet');
            $qr->slug = $this->uniqueSlug($base, $qr->id ?? null, $pet->id);
        }

        // 2) Activation code (si no existe)
        if (empty($qr->activation_code)) {
            $qr->activation_code = $this->generateUniqueActivationCode();
        }

        // 3) Generar imagen QR (URL pública del perfil)
        $publicUrl = route('public.pet.show', $qr->slug);

        // Generamos SVG nítido (también puedes usar ->format('png') si prefieres PNG)
        $svg = QrCodeFacade::format('svg')
            ->size(600)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($publicUrl);

        $dir  = 'qrcodes';
        $file = $dir . '/' . $qr->slug . '.svg';

        Storage::disk('public')->put($file, $svg);
        $qr->image = $file;

        $qr->save();

        return $qr;
    }

    /**
     * Slug único tipo: "luna-123"
     */
    private function uniqueSlug(string $base, ?int $qrId, int $petId): string
    {
        $slug = $base . '-' . $petId;
        $i = 0;
        while (QrCodeModel::where('slug', $slug)
                ->when($qrId, fn ($q) => $q->where('id', '!=', $qrId))
                ->exists()) {
            $i++;
            $slug = $base . '-' . $petId . '-' . $i;
        }
        return $slug;
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
