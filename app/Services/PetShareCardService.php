<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;

class PetShareCardService
{
    public function generate(Pet $pet): string
    {
        $m = new ImageManager(new Driver());

        // ===== DISEÑO MINIMALISTA Y LIMPIO =====
        $W = 1080; $H = 1350; // Instagram 4:5

        // Colores simples y profesionales
        $white = '#FFFFFF';
        $red = '#EF4444';
        $darkGray = '#1F2937';
        $gray = '#6B7280';
        $lightGray = '#F3F4F6';

        // Crear lienzo blanco simple
        $img = $m->create($W, $H)->fill($white);

        // ===== HEADER ROJO SIMPLE =====
        $this->rect($img, $W, 280, 0, 0, $red);

        // Texto header centrado
        $this->text($img, 'SE BUSCA', $W / 2, 80, 64, $white, $this->bold(), ['align' => 'center']);
        $this->text($img, 'MASCOTA PERDIDA', $W / 2, 170, 42, $white, $this->regular(), ['align' => 'center']);

        // ===== FOTO CUADRADA SIMPLE =====
        $photoSize = 480;
        $photoX = (int)(($W - $photoSize) / 2);
        $photoY = 340;

        // Fondo gris para la foto
        $this->rect($img, $photoSize, $photoSize, $photoX, $photoY, $lightGray);

        // Cargar y colocar foto
        $photoAbs = $this->mainPhotoAbsolute($pet);
        if ($photoAbs && is_file($photoAbs)) {
            $photo = $m->read($photoAbs)->cover($photoSize, $photoSize);
            $img->place($photo, 'top-left', $photoX, $photoY);
        } else {
            $this->text($img, 'SIN FOTO', $W / 2, $photoY + 220, 36, $gray, $this->bold(), ['align' => 'center']);
        }

        // Borde simple alrededor de la foto
        $this->rect($img, $photoSize, $photoSize, $photoX, $photoY, '#00000000', ['color' => $lightGray, 'width' => 3]);

        // ===== NOMBRE DE LA MASCOTA =====
        $nameY = $photoY + $photoSize + 60;
        $name = mb_strtoupper(trim($pet->name ?: 'MASCOTA'));
        $this->text($img, $name, $W / 2, $nameY, 72, $darkGray, $this->bold(), ['align' => 'center']);

        // ===== UBICACIÓN =====
        $locationY = $nameY + 100;
        $zone = $pet->full_location ?: ($pet->zone ?: 'Ubicación desconocida');
        $this->text($img, $zone, $W / 2, $locationY, 36, $gray, $this->regular(), ['align' => 'center']);

        // ===== TELÉFONO DESTACADO =====
        $phoneY = $locationY + 80;
        $phone = $this->displayPhoneForce($pet);

        // Fondo rojo suave para el teléfono
        $phoneBoxW = 600;
        $phoneBoxH = 100;
        $phoneBoxX = (int)(($W - $phoneBoxW) / 2);
        $this->rect($img, $phoneBoxW, $phoneBoxH, $phoneBoxX, $phoneY - 20, $red);

        $this->text($img, 'CONTACTO', $W / 2, $phoneY, 24, $white, $this->regular(), ['align' => 'center']);
        $this->text($img, $phone, $W / 2, $phoneY + 50, 48, $white, $this->bold(), ['align' => 'center']);

        // ===== FOOTER SIMPLE =====
        $footerY = $H - 80;
        $this->text($img, '¡Ayúdanos a encontrarlo!', $W / 2, $footerY, 28, $gray, $this->regular(), ['align' => 'center']);

        // Guardar
        $dir = 'share';
        Storage::disk('public')->makeDirectory($dir);
        $file = $dir . '/pet-' . $pet->id . '-' . time() . '.png';
        $img->save(Storage::disk('public')->path($file));
        return $file;
    }

    /* ================= Helpers de dibujo ================= */

    private function rect($image, int $w, int $h, int $x, int $y, string $bg, array $border = []): void
    {
        $image->drawRectangle($x, $y, function ($r) use ($w, $h, $bg, $border) {
            $r->size($w, $h);
            $r->background($bg);
            if (!empty($border)) {
                $r->border((string)($border['color'] ?? '#000000'), (int)($border['width'] ?? 1));
            }
        });
    }

    private function circle($img, int $radius, int $x, int $y, string $color): void
    {
        $img->drawCircle($x, $y, function ($c) use ($radius, $color) {
            $c->radius($radius);
            $c->background($color);
        });
    }

    private function text($image, string $text, int $x, int $y, int $size, string $color, string $fontPath, array $opts = []): void
    {
        $image->text($text, $x, $y, function (FontFactory $f) use ($size, $color, $fontPath, $opts) {
            $f->filename($fontPath);
            $f->size($size);
            $f->color($color);
            $f->align($opts['align'] ?? 'left');
            $f->valign($opts['valign'] ?? 'top');
        });
    }

    private function textWidth(string $text, int $fontSize): float
    {
        return mb_strlen($text) * ($fontSize * 0.58);
    }

    private function lineHeight(int $fontSize): int
    {
        return (int)round($fontSize * 1.3);
    }

    /* ================= Helpers de datos ================= */

    private function sexLabel(?string $sex): string
    {
        return $sex === 'female' ? 'Hembra' : ($sex === 'male' ? 'Macho' : 'N/D');
    }

    private function shortNeuteredLabel(Pet $pet): string
    {
        if ($pet->is_neutered === null) return 'N/D';
        return $pet->is_neutered ? 'Sí' : 'No';
    }

    private function mainPhotoAbsolute(Pet $pet): ?string
    {
        if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
            return Storage::disk('public')->path($pet->photo);
        }
        $ph = $pet->photos()->orderBy('sort_order')->first();
        if ($ph && Storage::disk('public')->exists($ph->path)) {
            return Storage::disk('public')->path($ph->path);
        }
        return null;
    }

    private function regular(): string
    {
        $p = resource_path('fonts/Inter-Regular.ttf');
        if (!is_file($p)) $p = $this->fallbackFont(false);
        return $p;
    }

    private function bold(): string
    {
        $p = resource_path('fonts/Inter-Bold.ttf');
        if (!is_file($p)) $p = $this->fallbackFont(true);
        return $p;
    }

   private function fallbackFont(bool $bold): string
{
    // 1) Busca primero dentro del proyecto (lo único 100% portable)
    $basename = 'DejaVuSans' . ($bold ? '-Bold' : '') . '.ttf';
    $candidates = [
        resource_path('fonts/' . $basename),     // resources/fonts/DejaVuSans(-Bold).ttf
        public_path('fonts/' . $basename),       // public/fonts/...  (por si las pones públicas)
        storage_path('app/fonts/' . $basename),  // storage/app/fonts/... (otra opción)
        // 2) Fallbacks comunes del sistema (por si existen)
        '/usr/share/fonts/truetype/dejavu/' . $basename,
        '/usr/share/fonts/dejavu/' . $basename,
        'C:\Windows\Fonts\\' . ($bold ? 'arialbd.ttf' : 'arial.ttf'),
    ];

    foreach ($candidates as $p) {
        if ($p && is_file($p)) {
            return $p;
        }
    }

    // Si llegamos aquí, no hay fuente disponible => lanzamos un error legible
    throw new \RuntimeException('No se encontró ninguna fuente TTF. Copia DejaVuSans.ttf y DejaVuSans-Bold.ttf en resources/fonts/');
}


    /**
     * Devuelve SIEMPRE un número formateado.
     */
    private function displayPhoneForce(Pet $pet): string
    {
        $candidates = [];
        $candidates[] = (string) optional($pet->user)->phone;
        $candidates[] = isset($pet->phone) ? (string) $pet->phone : '';
        $candidates[] = isset($pet->owner_phone) ? (string) $pet->owner_phone : '';
        $candidates[] = isset($pet->contact_phone) ? (string) $pet->contact_phone : '';

        foreach ($candidates as $raw) {
            $digits = preg_replace('/\D+/', '', (string)$raw);
            if ($digits && strlen($digits) >= 8) {
                return $this->formatCrPhone($digits);
            }
        }
        return '+506 0000 0000';
    }

    private function formatCrPhone(string $digits): string
    {
        if (strlen($digits) >= 11 && str_starts_with($digits, '506')) {
            $n = substr($digits, -8);
            return '+506 ' . substr($n, 0, 4) . ' ' . substr($n, 4);
        }
        $n = strlen($digits) >= 8 ? substr($digits, -8) : str_pad($digits, 8, '0', STR_PAD_LEFT);
        return '+506 ' . substr($n, 0, 4) . ' ' . substr($n, 4);
    }
}
