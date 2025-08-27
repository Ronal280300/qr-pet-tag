<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\QrCode as QrCodeModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PetQrService
{
    public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): void
    {
        // Slug único legible
        if (empty($qr->slug)) {
            $base = Str::slug($pet->name . '-' . $pet->id);
            $slug = $base;
            $n = 1;
            while (QrCodeModel::where('slug', $slug)->where('id', '!=', $qr->id)->exists()) {
                $slug = $base . '-' . $n++;
            }
            $qr->slug = $slug;
        }

        $publicUrl = url('/pet/' . $qr->slug);
        $dir = 'qrcodes';

        // ¿Está disponible la extensión Imagick?
        $hasImagick = class_exists(\Imagick::class);

        if ($hasImagick) {
            // PNG (requiere imagick)
            $binary = QrCode::format('png')->size(600)->margin(2)->errorCorrection('M')->generate($publicUrl);
            $path = $dir . '/' . $qr->slug . '.png';
            Storage::disk('public')->put($path, $binary);
        } else {
            // Fallback: SVG (sin dependencias, 100% funcional)
            $svg = QrCode::format('svg')->size(600)->margin(2)->errorCorrection('M')->generate($publicUrl);
            $path = $dir . '/' . $qr->slug . '.svg';
            Storage::disk('public')->put($path, $svg);
        }

        $qr->pet_id  = $pet->id;
        $qr->qr_code = $publicUrl;  // URL pública del perfil
        $qr->image   = $path;       // ajusta a image_path si tu columna se llama distinto
        $qr->save();
    }
}