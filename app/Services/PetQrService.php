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
        // Si no hay slug, lo construimos con el nombre de la mascota + id
        if (empty($qr->slug)) {
            $qr->slug = Str::slug($pet->name . '-' . $pet->id);
            $qr->save();
        }

        // URL pública del perfil
        $publicUrl = route('public.pet.show', $qr->slug);

        // Genera la imagen y guarda path en $qr->image
        $this->buildFromUrl($qr, $publicUrl);
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
