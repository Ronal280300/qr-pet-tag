<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'zone',
        'age',
        'medical_conditions',
        'photo',          // compatibilidad: foto única antigua
        'is_lost',
        'province_id',
        'city_id',
        'district_id',
    ];

    protected function casts(): array
    {
        return [
            'age'     => 'integer',
            'is_lost' => 'boolean',
        ];
    }

    /* ===================== Relaciones base ===================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'pet_id');
    }

    public function reward()
    {
        return $this->hasOne(Reward::class, 'pet_id');
    }

    public function scans()
    {
        return $this->hasManyThrough(Scan::class, QrCode::class, 'pet_id', 'qr_code_id');
    }

    public function province() { return $this->belongsTo(Province::class); }
    public function city()     { return $this->belongsTo(City::class); }
    public function district() { return $this->belongsTo(District::class); }

    /* ===================== NUEVO: Fotos múltiples ===================== */

    // Relación para varias fotos (ordenadas)
    public function photos()
    {
        return $this->hasMany(PetPhoto::class, 'pet_id')->orderBy('order')->orderBy('id');
    }

    // URL principal: 1) primera foto múltiple; 2) foto antigua 'photo'; 3) placeholder
    public function getMainPhotoUrlAttribute(): string
    {
        $first = $this->photos()->first();
        if ($first) {
            return asset('storage/' . $first->path);
        }
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://images.unsplash.com/photo-1558944351-cbbdcc8c4fba?q=80&w=1200&auto=format&fit=crop';
    }

    /* ===================== Helpers ===================== */

    // Para mostrar bonito: "San Juan, Grecia, Alajuela"
    public function getFullLocationAttribute(): ?string
    {
        $parts = [
            optional($this->district)->name,
            optional($this->city)->name,
            optional($this->province)->name,
        ];
        $str = implode(', ', array_filter($parts));
        return $str ?: ($this->zone ?: null);
    }
}
