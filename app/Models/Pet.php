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
        'age',            // mantener por compatibilidad legacy
        'age_years',      // años (0-50)
        'age_months',     // meses adicionales (0-11)
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

        // ===== REGISTRO PENDIENTE (Admin crea mascota para cliente no registrado) =====
        'pending_email',            // email del cliente invitado
        'pending_token',            // token único para el link
        'pending_plan_id',          // plan seleccionado por admin
        'is_pending_registration',  // flag para mascotas pendientes
        'pending_sent_at',          // cuándo se envió la invitación
        'pending_completed_at',     // cuándo se completó el registro
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

            // ===== REGISTRO PENDIENTE =====
            'is_pending_registration' => 'boolean',
            'pending_sent_at' => 'datetime',
            'pending_completed_at' => 'datetime',
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

    public function pendingPlan()
    {
        return $this->belongsTo(Plan::class, 'pending_plan_id');
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
     * 1) foto principal 'photo'
     * 2) primera foto de pet_photos
     * 3) placeholder
     */
    public function getMainPhotoUrlAttribute(): string
    {
        // Priorizar foto principal (campo 'photo') primero
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }

        // Si no hay foto principal, usar primera foto opcional
        $first = $this->photos()->first();
        if ($first && $first->path) {
            return Storage::url($first->path);
        }

        // Placeholder
        return 'https://images.unsplash.com/photo-1592194996308-7b43878e84a6?q=80&w=1287&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    }

    /**
     * Obtiene TODAS las fotos en orden: principal primero, luego opcionales
     * Retorna una colección con objetos stdClass que tienen 'path' e 'is_main'
     */
    public function getAllPhotosAttribute()
    {
        $allPhotos = collect();

        // 1. Agregar foto principal primero (si existe)
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            $allPhotos->push((object)[
                'path' => $this->photo,
                'is_main' => true,
            ]);
        }

        // 2. Agregar fotos opcionales
        foreach ($this->photos as $photo) {
            $allPhotos->push((object)[
                'path' => $photo->path,
                'is_main' => false,
            ]);
        }

        return $allPhotos;
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

    // Edad legible: "3 años", "6 meses", "1 año y 6 meses"
    public function getAgeDisplayAttribute(): ?string
    {
        $years = $this->age_years ?? 0;
        $months = $this->age_months ?? 0;

        // Si ambos son 0 o null, no mostrar nada
        if ($years == 0 && $months == 0) {
            return null;
        }

        $parts = [];

        // Agregar años si existen
        if ($years > 0) {
            $parts[] = $years . ($years == 1 ? ' año' : ' años');
        }

        // Agregar meses si existen
        if ($months > 0) {
            $parts[] = $months . ($months == 1 ? ' mes' : ' meses');
        }

        return implode(' y ', $parts);
    }
}
