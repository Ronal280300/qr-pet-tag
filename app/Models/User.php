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
     * Estados del cliente.
     */
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_PENDING  = 'pending';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * Atributos por defecto.
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE, // Por defecto todo nuevo usuario queda Activo
    ];

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

        // — módulo de clientes —
        'status',             // active|pending|inactive
        'pending_since',      // datetime|null (marca cuándo entró a pending)
        'status_changed_at',  // datetime|null (último cambio de estado)
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

            // — módulo de clientes —
            'pending_since'     => 'datetime',
            'status_changed_at' => 'datetime',
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

    /**
     * Helpers de estado.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Regla de negocio: ¿puede gestionar mascotas?
     * (Activo o Pendiente sí; Inactivo no).
     */
    public function canManagePets(): bool
    {
        return !$this->isInactive();
    }

    /**
     * Scopes útiles (opcionales).
     */
    public function scopeActive($q)
    {
        return $q->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($q)
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function scopeInactive($q)
    {
        return $q->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Notificación de restablecimiento de contraseña (custom).
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        $this->notify(new ResetPasswordEs($url));
    }
}
