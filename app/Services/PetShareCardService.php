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

        // ===== Paleta
        $cardBg     = '#FFFFFF';
        $primary    = '#4169E1'; // azul
        $secondary  = '#7C3AED'; // morado
        $accent     = '#059669'; // verde
        $warning    = '#DC6803'; // naranja

        $titleColor = '#111827'; // casi negro
        $subtitle   = '#374151'; // gris oscuro
        $bodyText   = '#4B5563'; // gris medio
        $muted      = '#6B7280'; // gris claro
        $light      = '#9CA3AF'; // gris muy claro

        $surface    = '#F9FAFB'; // gris claro superficie
        $surfaceBrd = '#E5E7EB'; // borde sutil

        // ===== Lienzo
        $W = 1080; $H = 1500;
        $img = $m->create($W, $H)->fill('#F5F5F5');

        // Sombras + tarjeta
        $cardPad = 40;
        $cardW = $W - ($cardPad * 2);
        $cardH = $H - ($cardPad * 2);

        $this->rect($img, $cardW, $cardH, $cardPad + 8, $cardPad + 12, '#00000010');
        $this->rect($img, $cardW, $cardH, $cardPad + 4, $cardPad + 6,  '#00000015');
        $this->rect($img, $cardW, $cardH, $cardPad + 2, $cardPad + 3,  '#00000008');

        $this->rect($img, $cardW, $cardH, $cardPad, $cardPad, $cardBg, [
            'color' => $surfaceBrd,
            'width' => 1
        ]);

        // ===== Header estilo alerta
        $bannerH = 64;
        $bannerW = $cardW - 80;
        $bannerX = $cardPad + 40;
        $bannerY = $cardPad + 28;

        $this->rect($img, $bannerW, $bannerH, $bannerX, $bannerY, '#FFF1F2', [
            'color' => '#FECACA',
            'width' => 1
        ]);

        $this->circle($img, 12, $bannerX + 30, $bannerY + (int)($bannerH/2), '#DC2626'); // rojo 600
        $this->text($img, '!', $bannerX + 26, $bannerY + (int)($bannerH/2) - 18, 28, '#FFFFFF', $this->bold());

        $this->text(
            $img,
            '¡Mascota reportada como perdida!',
            (int)($bannerX + 60),
            $bannerY + 18,
            30,
            '#991B1B', // rojo 800
            $this->bold()
        );

        // ===== Contenido
        $innerPad     = 48;
        $contentTop   = $bannerY + $bannerH + 50;
        $contentLeft  = $cardPad + $innerPad;
        $contentRight = $W - $cardPad - $innerPad;
        $contentWidth = $contentRight - $contentLeft;

        // Foto
        $photoSize = 420;
        $photoX = (int)(($W / 2) - ($photoSize / 2));
        $photoY = $contentTop;

        $photoAbs = $this->mainPhotoAbsolute($pet);
        if ($photoAbs && is_file($photoAbs)) {
            $photo = $m->read($photoAbs)->cover($photoSize - 12, $photoSize - 12);
            $img->place($photo, 'top-left', $photoX + 6, $photoY + 6);
        } else {
            $this->text($img, 'MASCOTA', $photoX + (int)($photoSize/2), $photoY + (int)($photoSize/2) - 20, 28, $muted, $this->bold(), ['align' => 'center']);
            $this->text($img, 'Foto no disponible', $photoX + (int)($photoSize/2), $photoY + (int)($photoSize/2) + 15, 22, $light, $this->regular(), ['align' => 'center']);
        }

        // Nombre
        $infoTop  = $photoY + $photoSize + 50;
        $name     = trim($pet->name ?: 'Mascota');
        $nameSize = 92;
        $maxNameWidth = $contentWidth - 80;
        while ($nameSize > 52 && $this->textWidth($name, $nameSize) > $maxNameWidth) {
            $nameSize -= 3;
        }

        $nameY = $infoTop;
        $this->text($img, $name, ($W / 2) + 1, $nameY + 1, $nameSize, '#00000012', $this->bold(), ['align' => 'center']);
        $this->text($img, $name,  $W / 2,       $nameY,     $nameSize, $titleColor, $this->bold(), ['align' => 'center']);

        $nameEndY = $nameY + $this->lineHeight($nameSize) + 16;
        $lineW = (int)min(200, $this->textWidth($name, $nameSize) * 0.8);
        $this->rect($img, $lineW, 3, (int)(($W / 2) - ($lineW / 2)), $nameEndY, $primary);

        // Ubicación
        $locationY = $nameEndY + 32;
        $zone = $pet->full_location ?: ($pet->zone ?: 'Ubicación no disponible');

        $locationStr = 'Ubicación: ' . $zone;
        $locationBgW = (int)min($contentWidth - 40, $this->textWidth($locationStr, 34) + 60);
        $locationBgX = (int)(($W / 2) - ($locationBgW / 2));
        $this->rect($img, $locationBgW, 50, $locationBgX, $locationY - 8, $surface, ['color' => $surfaceBrd, 'width' => 1]);
        $this->text($img, $locationStr, $W / 2, $locationY + 8, 34, $subtitle, $this->regular(), ['align' => 'center']);

        // Edad
        $ageY = $locationY + 60;
        if ($pet->age !== null) {
            $ageText = 'Edad: ' . $pet->age . ' ' . ($pet->age == 1 ? 'año' : 'años');
            $this->text($img, $ageText, $W / 2, $ageY, 28, $bodyText, $this->regular(), ['align' => 'center']);
            $ageY += $this->lineHeight(28) + 20;
        }

        // ===== Grid 2x2 de info
        $gridTop = $ageY + 40;
        $gridW   = $contentWidth - 100;
        $gridH   = 160;
        $gridX   = (int)(($W / 2) - ($gridW / 2));

        $this->rect($img, (int)$gridW, $gridH, $gridX, $gridTop, $surface, ['color' => $surfaceBrd, 'width' => 1]);
        $this->text($img, 'INFORMACIÓN', $gridX + 24, $gridTop + 24, 24, $muted, $this->bold());

        $gridItemW = (int)(($gridW - 60) / 2);
        $gridItemH = 45;
        $gridItemGap = 20;
        $gridContentY = $gridTop + 60;

        $gridItems = [
            ['dot' => $secondary,                             'label' => 'Sexo',           'value' => $this->sexLabel($pet->sex)],
            ['dot' => $pet->is_neutered ? $accent : $warning,'label' => 'Esterilización','value' => $this->shortNeuteredLabel($pet)],
            ['dot' => $pet->rabies_vaccine ? $accent : $warning, 'label' => 'Antirrábica','value' => $pet->rabies_vaccine ? 'Al día' : 'N/D'],
            ['dot' => $primary,                               'label' => 'ID',             'value' => '#' . str_pad($pet->id, 4, '0', STR_PAD_LEFT)],
        ];

        $row = 0; $col = 0;
        foreach ($gridItems as $item) {
            $itemX = $gridX + 30 + ($col * ($gridItemW + $gridItemGap));
            $itemY = $gridContentY + ($row * ($gridItemH + 15));

            $this->rect($img, $gridItemW, $gridItemH, (int)$itemX, $itemY, '#FFFFFF', [
                'color' => $item['dot'] . '30',
                'width' => 1
            ]);

            $this->circle($img, 8, (int)$itemX + 16, $itemY + (int)($gridItemH/2) - 1, $item['dot']);

            $textX = $itemX + 36;
            $this->text($img, $item['label'], (int)$textX, $itemY + 4, 18, $muted, $this->bold());
            $this->text($img, $item['value'], (int)$textX, $itemY + 26, 20, $titleColor, $this->regular());

            $col++;
            if ($col >= 2) { $col = 0; $row++; }
        }

        // ===== Contacto (teléfono en la MISMA línea)
        $contactTop = $gridTop + $gridH + 60;
        $contactH   = 140;

        $this->rect($img, (int)$contentWidth, $contactH, $contentLeft, $contactTop, '#FAFAFA', ['color' => $surfaceBrd, 'width' => 2]);
        $this->rect($img, (int)$contentWidth, 4, $contentLeft, $contactTop, $primary);
        $this->text($img, 'INFORMACIÓN DE CONTACTO', $contentLeft + 32, $contactTop + 32, 20, $muted, $this->bold());

        $phone = $this->displayPhoneForce($pet);
        $contactY = $contactTop + 70;

        $phoneContainerW = $contentWidth - 64;
        $phoneContainerX = $contentLeft + 32;
        $this->rect($img, (int)$phoneContainerW, 50, (int)$phoneContainerX, $contactY, $primary . '10', ['color' => $primary . '40', 'width' => 1]);

        $this->circle($img, 10, (int)$phoneContainerX + 28, $contactY + 25, $primary);

        $labelText  = 'Llamar ahora:';
        $labelSize  = 22;
        $labelX     = (int)$phoneContainerX + 52;
        $labelY     = $contactY + 17;

        $this->text($img, $labelText, $labelX, $labelY, $labelSize, $titleColor, $this->bold());

        $labelW     = (int) $this->textWidth($labelText, $labelSize);
        $numberX    = $labelX + $labelW + 10;
        $numberY    = $contactY + 17;

        $this->text($img, $phone, $numberX, $numberY, 28, $primary, $this->bold());

        // ===== Footer con LOGO pequeño =====
        $footerY = $H - $cardPad - 100;

        // Separador fino sobre el logo
        $separatorY = $footerY - 40;
        $separatorW = 300;
        $separatorX = (int)(($W / 2) - ($separatorW / 2));
        $this->rect($img, $separatorW, 2, $separatorX, $separatorY, $light);

        $logoRel = 'images/qr-pet-tag-logo.png'; // storage/app/public/images/qr-pet-tag-logo.png
        if (Storage::disk('public')->exists($logoRel)) {
            $logoAbs = Storage::disk('public')->path($logoRel);

            // Limite de tamaño para que siempre se vea pequeño/limpio
            $maxW = 260; 
            $maxH = 130;

            // Contener dentro del cuadro (mantiene proporción)
            $logo = $m->read($logoAbs)->contain($maxW, $maxH);

            // Si tu versión no expone width()/height(), centra usando el cuadro previsto ($maxW/$maxH)
            $placeW = method_exists($logo, 'width') ? $logo->width() : $maxW;
            $logoX  = (int)(($W - $placeW) / 2);
            $logoY  = $footerY - 25;

            $img->place($logo, 'top-left', $logoX, $logoY);
        } else {
            // Fallback si no encuentra el archivo
            $this->text($img, 'QR Pet Tag', $W / 2, $footerY + 30, 26, $primary, $this->bold(), ['align' => 'center']);
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
