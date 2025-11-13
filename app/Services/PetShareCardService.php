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

        // ===== Paleta moderna y vibrante
        $cardBg     = '#FFFFFF';
        $primary    = '#667eea'; // morado moderno
        $secondary  = '#764ba2'; // morado oscuro
        $accent     = '#10b981'; // verde éxito
        $warning    = '#ef4444'; // rojo alerta

        $titleColor = '#111827';
        $subtitle   = '#374151';
        $bodyText   = '#4b5563';
        $muted      = '#6b7280';
        $light      = '#9ca3af';

        // ===== Lienzo con gradiente sutil de fondo
        $W = 1080; $H = 1350; // Formato Instagram (4:5)
        $img = $m->create($W, $H);

        // Fondo con gradiente sutil
        for ($y = 0; $y < $H; $y++) {
            $ratio = $y / $H;
            $r = 250 + (int)($ratio * 5);
            $g = 250 + (int)($ratio * 10);
            $b = 255;
            $color = sprintf('#%02x%02x%02x', $r, $g, $b);
            $this->rect($img, $W, 1, 0, $y, $color);
        }

        // ===== Header con gradiente rojo dramático
        $headerH = 180;

        // Gradiente rojo del header
        for ($y = 0; $y < $headerH; $y++) {
            $ratio = $y / $headerH;
            $r = 239 - (int)($ratio * 20);
            $g = 68 - (int)($ratio * 20);
            $b = 68 - (int)($ratio * 20);
            $color = sprintf('#%02x%02x%02x', $r, $g, $b);
            $this->rect($img, $W, 1, 0, $y, $color);
        }

        // Icono de alerta grande
        $iconSize = 64;
        $iconX = (int)($W / 2);
        $iconY = 50;

        $this->circle($img, $iconSize, $iconX, $iconY, '#FFFFFF');
        $this->circle($img, $iconSize - 4, $iconX, $iconY, $warning);
        $this->text($img, '!', $iconX - 20, $iconY - 40, 80, '#FFFFFF', $this->bold());

        // Texto del header
        $this->text($img, '¡MASCOTA PERDIDA!', $W / 2, 130, 52, '#FFFFFF', $this->bold(), ['align' => 'center']);

        // ===== Card principal con foto circular
        $cardTop = $headerH + 40;
        $cardPad = 50;
        $cardW = $W - ($cardPad * 2);

        // Sombra del card
        $this->rect($img, $cardW, 840, $cardPad + 6, $cardTop + 6, '#00000015');
        $this->rect($img, $cardW, 840, $cardPad, $cardTop, $cardBg);

        // Foto circular con borde
        $photoSize = 300;
        $photoX = (int)(($W / 2) - ($photoSize / 2));
        $photoY = $cardTop - ($photoSize / 3); // Sobresale del card

        // Borde blanco grueso
        $this->circle($img, (int)($photoSize / 2) + 12, (int)($W / 2), $photoY + (int)($photoSize / 2), '#FFFFFF');

        // Borde de color
        $this->circle($img, (int)($photoSize / 2) + 8, (int)($W / 2), $photoY + (int)($photoSize / 2), $primary);

        // Foto
        $photoAbs = $this->mainPhotoAbsolute($pet);
        if ($photoAbs && is_file($photoAbs)) {
            $photo = $m->read($photoAbs)->cover($photoSize, $photoSize);

            // Crear máscara circular
            $mask = $m->create($photoSize, $photoSize)->fill('#00000000');
            $this->circle($mask, (int)($photoSize / 2), (int)($photoSize / 2), (int)($photoSize / 2), '#FFFFFF');

            // Aplicar máscara (si la versión de Intervention lo soporta, sino usamos contain)
            $img->place($photo, 'top-left', $photoX, $photoY);
        } else {
            $this->circle($img, (int)($photoSize / 2), (int)($W / 2), $photoY + (int)($photoSize / 2), '#F3F4F6');
            $this->text($img, 'FOTO', $W / 2, $photoY + (int)($photoSize / 2) - 20, 32, $muted, $this->bold(), ['align' => 'center']);
            $this->text($img, 'NO DISPONIBLE', $W / 2, $photoY + (int)($photoSize / 2) + 20, 22, $light, $this->regular(), ['align' => 'center']);
        }

        // ===== Nombre (debajo de la foto)
        $nameY = $photoY + $photoSize + 60;
        $name = trim($pet->name ?: 'Mascota');
        $nameSize = 72;
        $maxNameWidth = $cardW - 100;
        while ($nameSize > 48 && $this->textWidth($name, $nameSize) > $maxNameWidth) {
            $nameSize -= 3;
        }

        // Nombre con sombra sutil
        $this->text($img, $name, ($W / 2) + 2, $nameY + 2, $nameSize, '#00000010', $this->bold(), ['align' => 'center']);
        $this->text($img, $name, $W / 2, $nameY, $nameSize, $titleColor, $this->bold(), ['align' => 'center']);

        // Línea decorativa bajo el nombre
        $lineW = (int)min(250, $this->textWidth($name, $nameSize) * 0.7);
        $lineY = $nameY + $this->lineHeight($nameSize) + 20;

        // Gradiente en la línea
        $this->rect($img, (int)($lineW / 2), 4, (int)(($W / 2) - ($lineW / 2)), $lineY, $primary);
        $this->rect($img, (int)($lineW / 2), 4, (int)($W / 2), $lineY, $secondary);

        // ===== Ubicación destacada
        $locationY = $lineY + 50;
        $zone = $pet->full_location ?: ($pet->zone ?: 'Ubicación no disponible');
        $locationText = '📍 ' . $zone;

        $locBgW = (int)min($cardW - 80, $this->textWidth($locationText, 32) + 80);
        $locBgX = (int)(($W / 2) - ($locBgW / 2));

        // Fondo con gradiente sutil
        $this->rect($img, $locBgW, 70, $locBgX, $locationY - 10, $primary . '15');
        $this->rect($img, $locBgW, 70, $locBgX, $locationY - 10, $cardBg, ['color' => $primary . '40', 'width' => 2]);
        $this->text($img, $locationText, $W / 2, $locationY + 15, 32, $titleColor, $this->bold(), ['align' => 'center']);

        // ===== Info cards (grid 2x2 moderno)
        $gridTop = $locationY + 110;
        $cardSize = 200;
        $cardGap = 30;
        $gridStartX = (int)(($W / 2) - $cardSize - ($cardGap / 2));

        $gridCards = [
            ['icon' => $pet->sex === 'female' ? '♀' : ($pet->sex === 'male' ? '♂' : '?'), 'color' => $pet->sex === 'female' ? '#f472b6' : '#60a5fa', 'label' => 'SEXO', 'value' => $this->sexLabel($pet->sex)],
            ['icon' => '🎂', 'color' => $accent, 'label' => 'EDAD', 'value' => ($pet->age !== null ? $pet->age . ' años' : 'N/D')],
            ['icon' => '💉', 'color' => $pet->rabies_vaccine ? $accent : $warning, 'label' => 'VACUNA', 'value' => $pet->rabies_vaccine ? 'Al día' : 'N/D'],
            ['icon' => '✂️', 'color' => $pet->is_neutered ? $accent : $warning, 'label' => 'ESTERILIZ.', 'value' => $this->shortNeuteredLabel($pet)],
        ];

        $row = 0; $col = 0;
        foreach ($gridCards as $card) {
            $cardX = $gridStartX + ($col * ($cardSize + $cardGap));
            $cardY = $gridTop + ($row * ($cardSize + $cardGap));

            // Sombra
            $this->rect($img, $cardSize, $cardSize, $cardX + 4, $cardY + 4, '#00000010');

            // Card
            $this->rect($img, $cardSize, $cardSize, $cardX, $cardY, '#FFFFFF', ['color' => $card['color'] . '30', 'width' => 3]);

            // Barra superior de color
            $this->rect($img, $cardSize, 8, $cardX, $cardY, $card['color']);

            // Icono
            $this->text($img, $card['icon'], $cardX + (int)($cardSize / 2), $cardY + 50, 48, $card['color'], $this->bold(), ['align' => 'center']);

            // Label
            $this->text($img, $card['label'], $cardX + (int)($cardSize / 2), $cardY + 120, 18, $muted, $this->bold(), ['align' => 'center']);

            // Value
            $this->text($img, $card['value'], $cardX + (int)($cardSize / 2), $cardY + 150, 24, $titleColor, $this->bold(), ['align' => 'center']);

            $col++;
            if ($col >= 2) { $col = 0; $row++; }
        }

        // ===== Contacto destacado
        $contactY = $gridTop + (2 * ($cardSize + $cardGap)) + 40;
        $contactH = 140;
        $contactW = $cardW - 60;
        $contactX = $cardPad + 30;

        // Fondo con gradiente
        $this->rect($img, $contactW, $contactH, $contactX + 4, $contactY + 4, '#00000010');

        // Gradiente verde
        for ($y = 0; $y < $contactH; $y++) {
            $ratio = $y / $contactH;
            $r = 16 + (int)($ratio * 10);
            $g = 185 + (int)($ratio * 15);
            $b = 129 + (int)($ratio * 10);
            $color = sprintf('#%02x%02x%02x', $r, $g, $b);
            $this->rect($img, $contactW, 1, $contactX, $contactY + $y, $color);
        }

        // Título
        $this->text($img, '📞 LLAMAR AHORA', $contactX + (int)($contactW / 2), $contactY + 30, 28, '#FFFFFF', $this->bold(), ['align' => 'center']);

        // Teléfono grande
        $phone = $this->displayPhoneForce($pet);
        $this->text($img, $phone, $contactX + (int)($contactW / 2), $contactY + 75, 48, '#FFFFFF', $this->bold(), ['align' => 'center']);

        // ===== Footer con logo y call to action
        $footerY = $H - 120;

        // Texto motivacional
        $this->text($img, '¡Ayúdanos a encontrarlo!', $W / 2, $footerY, 28, $subtitle, $this->bold(), ['align' => 'center']);
        $this->text($img, 'Comparte esta publicación', $W / 2, $footerY + 40, 22, $muted, $this->regular(), ['align' => 'center']);

        // Logo pequeño
        $logoRel = 'images/qr-pet-tag-logo.png';
        if (Storage::disk('public')->exists($logoRel)) {
            $logoAbs = Storage::disk('public')->path($logoRel);
            $logo = $m->read($logoAbs)->contain(200, 40);
            $placeW = method_exists($logo, 'width') ? $logo->width() : 200;
            $logoX = (int)(($W - $placeW) / 2);
            $img->place($logo, 'top-left', $logoX, $footerY + 70);
        } else {
            $this->text($img, 'QR PET TAG', $W / 2, $footerY + 80, 24, $primary, $this->bold(), ['align' => 'center']);
        }

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
