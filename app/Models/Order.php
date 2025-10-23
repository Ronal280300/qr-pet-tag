<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'plan_id',
        'pets_quantity',
        'subtotal',
        'additional_pets_cost',
        'total',
        'status',
        'payment_proof',
        'payment_uploaded_at',
        'verified_at',
        'verified_by',
        'admin_notes',
        'expires_at',
        'auto_renew',
    ];

    protected function casts(): array
    {
        return [
            'pets_quantity' => 'integer',
            'subtotal' => 'decimal:2',
            'additional_pets_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'payment_uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
            'expires_at' => 'datetime',
            'auto_renew' => 'boolean',
        ];
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generar número de orden único
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Relación con verificador (admin que verificó)
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relación con notificaciones
     */
    public function notifications()
    {
        return $this->hasMany(AdminNotification::class, 'related_order_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaymentUploaded($query)
    {
        return $query->where('status', 'payment_uploaded');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Verificar si el pedido está pendiente de revisión
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'payment_uploaded';
    }

    /**
     * Verificar si el pedido está verificado
     */
    public function isVerified(): bool
    {
        return in_array($this->status, ['verified', 'completed']);
    }

    /**
     * Obtener etiqueta de estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'payment_uploaded' => 'Pago Subido',
            'verified' => 'Verificado',
            'rejected' => 'Rechazado',
            'completed' => 'Completado',
            'expired' => 'Expirado',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener clase de badge según estado
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-gray-500',
            'payment_uploaded' => 'bg-yellow-500',
            'verified' => 'bg-blue-500',
            'rejected' => 'bg-red-500',
            'completed' => 'bg-green-500',
            'expired' => 'bg-gray-400',
            default => 'bg-gray-500',
        };
    }
}
