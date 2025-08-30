<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;



class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

   protected $fillable = [
        'pet_id',
        'slug',
        'code',
        'activation_code',
        'image',
    ];

    protected $casts = [
        'is_activated' => 'boolean',
        'activated_at' => 'datetime',
    ];

    public function pet()
    {
       return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function activatedByUser()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    /**
     * Código legible (fallback si no existe).
     */
    public static function generateActivationCode(): string
    {
        return strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . random_int(1000, 9999);
    }
}
