<?php

namespace App\Support;

class Phone
{
    /**
     * Catálogo mínimo de países que solemos usar.
     * nsn = National Significant Number length (longitud del número local SIN el prefijo).
     */
    protected static array $COUNTRIES = [
        '506' => ['name' => 'Costa Rica (+506)',           'nsn' => 8],
        '1'   => ['name' => 'Estados Unidos/Canadá (+1)',  'nsn' => 10],
        '52'  => ['name' => 'México (+52)',                'nsn' => 10],
        '34'  => ['name' => 'España (+34)',                'nsn' => 9],
        '57'  => ['name' => 'Colombia (+57)',              'nsn' => 10],
        // agrega aquí los que necesites…
    ];

    /** Solo dígitos. */
    public static function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    /** Devuelve lista de países para drop-down. */
    public static function countries(): array
    {
        $list = [];
        foreach (self::$COUNTRIES as $code => $meta) {
            $list[] = ['code' => $code, 'name' => $meta['name']];
        }
        usort($list, fn($a, $b) => strcmp($a['name'], $b['name']));
        return $list;
    }

    /** Longitud esperada del número local para un prefijo dado (o null si no definido). */
    public static function nsnLength(?string $code): ?int
    {
        $code = self::digits((string) $code);
        return self::$COUNTRIES[$code]['nsn'] ?? null;
    }

    /** ¿El número local cumple la longitud del país? Si no hay regla, se usa 6–12 por defecto. */
    public static function isValidLocal(?string $code, ?string $local): bool
    {
        $local = self::digits((string) $local);
        $len   = strlen($local);
        $req   = self::nsnLength($code);

        return $req ? ($len === $req) : ($len >= 6 && $len <= 12);
    }

    /**
     * Construye E.164: +<code><local>
     *  - Limpia no-dígitos.
     *  - Si el local empieza con el mismo code (usuario pegó todo), lo recorta.
     */
    public static function toE164(?string $code, ?string $local): string
    {
        $code  = self::digits((string) $code);
        $local = self::digits((string) $local);

        if ($code !== '' && str_starts_with($local, (string)$code)) {
            $local = substr($local, strlen($code));
        }

        return $code === '' || $local === '' ? '' : ('+' . $code . $local);
    }

    /**
     * Divide un E.164 en [code, local].
     * Intenta casar por el prefijo más largo de los conocidos.
     * Si no encuentra, toma los primeros 3 como code.
     */
    public static function fromE164(?string $e164): array
    {
        $digits = self::digits((string) $e164);
        if ($digits === '') {
            return [null, null];
        }

        // probar por prefijo más largo primero
        $codes = array_keys(self::$COUNTRIES);
        usort($codes, fn($a, $b) => strlen($b) <=> strlen($a)); // largo a corto

        foreach ($codes as $code) {
            if (str_starts_with($digits, (string)$code)) {
                return [$code, substr($digits, strlen($code))];
            }
        }

        // fallback (toma 3 + resto)
        return [substr($digits, 0, 3), substr($digits, 3)];
    }

    /**
     * Formato humano opcional para UI (no obligatorio).
     * E.164 -> "(+506) 8888-8888", "+1 (212) 555-1212", etc.
     */
    public static function formatHuman(?string $e164): ?string
    {
        $e164 = (string) $e164;
        if ($e164 === '') return null;

        [$code, $local] = self::fromE164($e164);
        if (!$code || !$local) return $e164;

        switch ($code) {
            case '506': // CR 8 dígitos: 4-4
                $fmt = preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $local);
                return "(+$code) $fmt";
            case '1':   // US/CA 10 dígitos: 3-3-4
                $fmt = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $local);
                return "+$code $fmt";
            case '52':  // MX 10 dígitos (simplificado): 3-3-4
                $fmt = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3', $local);
                return "(+$code) " . ($fmt ?: $local);
            case '34':  // ES 9 dígitos: 3-3-3
                $fmt = preg_replace('/^(\d{3})(\d{3})(\d{3})$/', '$1 $2 $3', $local);
                return "(+$code) $fmt";
            case '57':  // CO 10 dígitos: 3-3-4
                $fmt = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3', $local);
                return "(+$code) $fmt";
            default:
                return "(+$code) $local";
        }
    }
}
