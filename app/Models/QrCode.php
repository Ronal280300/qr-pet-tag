<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'qr_codes';

    // Asignación masiva
    protected $fillable = [
        'pet_id',
        'slug',
        'qr_code',        // si lo usas para guardar el contenido del QR o la url
        'image',          // ruta en storage (public)
        'activation_code' // código de activación impreso en el TAG
    ];

    // Relaciones
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Genera un código de activación aleatorio y único.
     */
    public static function generateActivationCode(int $length = 8): string
    {
        do {
            // Solo letras/números, en mayúsculas
            $code = strtoupper(Str::random($length));
        } while (static::where('activation_code', $code)->exists());

        return $code;
    }

    /**
     * Si no viene 'activation_code' al crear, lo genera automáticamente.
     */
    protected static function booted(): void
    {
        static::creating(function (self $qr) {
            if (empty($qr->activation_code)) {
                $qr->activation_code = static::generateActivationCode();
            }
        });
    }
}
