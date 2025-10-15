<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordEs;

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
        'google_id',
        'avatar',
        'avatar_original',

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

public function sendPasswordResetNotification($token): void
{
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $this->getEmailForPasswordReset(),
    ], false));

    $this->notify(new ResetPasswordEs($url));
}

    
}