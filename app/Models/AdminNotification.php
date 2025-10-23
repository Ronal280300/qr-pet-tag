<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'url',
        'related_order_id',
        'related_user_id',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Relación con pedido relacionado
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }

    /**
     * Relación con usuario relacionado
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope para notificaciones leídas
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Marcar como leída
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Marcar como no leída
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Crear notificación de nuevo pedido
     */
    public static function createNewOrderNotification(Order $order): self
    {
        return self::create([
            'type' => 'new_order',
            'title' => 'Nuevo Pedido Recibido',
            'message' => "El cliente {$order->user->name} realizó un nuevo pedido #{$order->order_number}",
            'icon' => 'fa-shopping-cart',
            'url' => route('portal.admin.orders.show', $order->id),
            'related_order_id' => $order->id,
            'related_user_id' => $order->user_id,
        ]);
    }

    /**
     * Crear notificación de pago subido
     */
    public static function createPaymentUploadedNotification(Order $order): self
    {
        return self::create([
            'type' => 'payment_uploaded',
            'title' => 'Comprobante de Pago Recibido',
            'message' => "El cliente {$order->user->name} subió el comprobante de pago para el pedido #{$order->order_number}",
            'icon' => 'fa-file-invoice-dollar',
            'url' => route('portal.admin.orders.show', $order->id),
            'related_order_id' => $order->id,
            'related_user_id' => $order->user_id,
        ]);
    }
}
