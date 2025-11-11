<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa.
     * Mantiene compatibilidad con el campo 'photo' (sistema antiguo) y con las
     * claves foráneas de ubicación que ya usas.
     */
    protected $fillable = [
        'user_id',
        'order_id',       // orden asociada cuando se crea desde checkout
        'pending_activation', // mascota pendiente de enlazar
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

        // ===== NUEVOS CAMPOS =====
        'species',        // dog | cat | other
        'sex',            // male | female | unknown
        'size',           // small | medium | large
        'color',          // texto libre
        'is_neutered',    // bool
        'rabies_vaccine', // bool
        
        // ===== CONTACTO DE EMERGENCIA =====
        'has_emergency_contact',    // bool
        'emergency_contact_name',   // string
        'emergency_contact_phone',  // string
    ];

    /**
     * Laravel 10+: caster por método.
     */
    protected function casts(): array
    {
        return [
            'age'            => 'integer',
            'is_lost'        => 'boolean',

            // ===== NUEVOS CASTS =====
            'is_neutered'    => 'boolean',
            'rabies_vaccine' => 'boolean',
            'pending_activation' => 'boolean',
            'last_fb_posted_at' => 'datetime',
            'has_emergency_contact' => 'boolean',
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
        // keys: hasManyThrough(Final, Through, foreignKeyOnThrough, foreignKeyOnFinal)
        return $this->hasManyThrough(Scan::class, QrCode::class, 'pet_id', 'qr_code_id');
    }

    public function province() { return $this->belongsTo(Province::class); }
    public function city()     { return $this->belongsTo(City::class); }
    public function district() { return $this->belongsTo(District::class); }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /* ===================== Fotos múltiples ===================== */

    /**
     * Relación para varias fotos. Se ordena por sort_order (y luego id).
     */
    public function photos()
    {
        return $this->hasMany(PetPhoto::class, 'pet_id')
                    ->orderBy('sort_order')
                    ->orderBy('id');
    }

    /**
     * URL principal:
     * 1) primera foto de pet_photos
     * 2) foto antigua 'photo'
     * 3) placeholder
     */
    public function getMainPhotoUrlAttribute(): string
    {
        $first = $this->photos()->first();

        if ($first && $first->path) {
            return Storage::url($first->path);
        }

        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }

        return 'https://images.unsplash.com/photo-1592194996308-7b43878e84a6?q=80&w=1287&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
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
