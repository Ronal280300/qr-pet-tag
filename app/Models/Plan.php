<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'duration_months',
        'pets_included',
        'price',
        'additional_pet_price',
        'is_active',
        'allows_additional_pets',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'pets_included' => 'integer',
            'price' => 'decimal:2',
            'additional_pet_price' => 'decimal:2',
            'is_active' => 'boolean',
            'allows_additional_pets' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Relación con pedidos
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación con usuarios que tienen este plan activo
     */
    public function users()
    {
        return $this->hasMany(User::class, 'current_plan_id');
    }

    /**
     * Scope para planes activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para planes de pago único
     */
    public function scopeOneTime($query)
    {
        return $query->where('type', 'one_time');
    }

    /**
     * Scope para planes de suscripción
     */
    public function scopeSubscription($query)
    {
        return $query->where('type', 'subscription');
    }

    /**
     * Calcular el precio total según la cantidad de mascotas
     */
    public function calculateTotal(int $petsQuantity): float
    {
        if ($petsQuantity <= $this->pets_included) {
            return (float) $this->price;
        }

        $additionalPets = $petsQuantity - $this->pets_included;
        $additionalCost = $additionalPets * $this->additional_pet_price;

        return (float) ($this->price + $additionalCost);
    }

    /**
     * Obtener nombre formateado del plan
     */
    public function getFormattedNameAttribute(): string
    {
        if ($this->type === 'one_time') {
            return "Pago Único - {$this->pets_included} mascota(s)";
        }

        return "Suscripción {$this->duration_months} mes(es) - {$this->pets_included} mascota(s)";
    }
}
