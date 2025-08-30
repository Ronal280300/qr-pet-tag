<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\QrCode as QrCodeModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PetQrService
{
    /**
     * Asegura slug (si falta) y genera la imagen del QR
     * para el flujo normal de mascotas.
     */
   public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): void
{
    // Asegurar relación
    if (!$qr->exists) {
        $qr->pet_id = $pet->id;
    }

    // Slug único (si falta)
    if (blank($qr->slug)) {
        // Puedes ajustar el formato del slug a tu gusto
        $qr->slug = Str::slug($pet->name . '-' . $pet->id . '-' . Str::lower(Str::random(6)));
    }

    // >>> FIX PRINCIPAL: generar activation_code si falta <<<
    if (blank($qr->activation_code)) {
        $qr->activation_code = QrCodeModel::generateActivationCode();
        $qr->is_activated   = false;     // por consistencia con tu modelo de activación
        $qr->activated_by   = null;
        $qr->activated_at   = null;
    }

    // Guardar (ya con activation_code)
    $qr->save();

    // Construir URL pública del perfil
    $url = url('/p/' . $qr->slug);

    // Generar archivo SVG y guardar en storage
    $filename = 'qrcodes/' . $qr->slug . '.svg';
    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(512)
        ->margin(1)
        ->generate($url);

    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $svg);

    // Persistir ruta de imagen si cambió
    if ($qr->image !== $filename) {
        $qr->image = $filename;
        $qr->save();
    }
}


    /**
     * Genera la imagen del QR a partir de una URL y guarda la ruta en $qr->image.
     * Útil cuando el TAG aún no está asignado a una mascota pero ya tiene slug.
     */
    public function buildFromUrl(QrCodeModel $qr, string $url): void
    {
        // Aseguramos un slug para poder nombrar el archivo
        if (empty($qr->slug)) {
            $qr->slug = 'tag-' . $qr->id . '-' . Str::lower(Str::random(5));
            $qr->save();
        }

        // Carpeta donde guardamos los QR dentro del disco "public"
        $dir = 'qrcodes';

        // Nombre de archivo (SVG por defecto; puedes cambiar a PNG si prefieres)
        $filename = $dir . '/' . $qr->slug . '.svg';

        // Generar el SVG del QR
        $svg = QrCode::format('svg')
            ->size(512)      // tamaño del canvas
            ->margin(1)      // margen
            ->generate($url);

        // Guardar el archivo en storage/app/public/qrcodes/xxx.svg
        Storage::disk('public')->put($filename, $svg);

        // Persistir ruta en el modelo
        $qr->image = $filename;
        $qr->save();
    }
}
