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
        'photo',
        'is_lost',
        'province_id',
        'city_id',
        'district_id'
    ];

    protected function casts(): array
    {
        return [
            'age'     => 'integer',
            'is_lost' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function reward()
    {
        return $this->hasOne(Reward::class);
    }

    public function scans()
    {
        return $this->hasManyThrough(Scan::class, QrCode::class);
    }
    public function province() { return $this->belongsTo(Province::class); }
    public function city()     { return $this->belongsTo(City::class); }
    public function district() { return $this->belongsTo(District::class); }

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
