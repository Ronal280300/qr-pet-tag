<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'emergency_contact',
        'is_admin',
    ];

    /**
     * Ocultos en arrays/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    /**
     * Relación: un usuario tiene muchas mascotas.
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Helper: ¿es administrador?
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}