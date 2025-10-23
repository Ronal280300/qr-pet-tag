<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient',
        'subject',
        'type',
        'related_order_id',
        'related_user_id',
        'status',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Relación con pedido
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Scope para emails enviados
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope para emails fallidos
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Registrar email enviado
     */
    public static function logEmail(
        string $recipient,
        string $subject,
        ?string $type = null,
        ?int $orderId = null,
        ?int $userId = null,
        string $status = 'sent',
        ?string $errorMessage = null
    ): self {
        return self::create([
            'recipient' => $recipient,
            'subject' => $subject,
            'type' => $type,
            'related_order_id' => $orderId,
            'related_user_id' => $userId,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }

    /**
     * Obtener contador de emails del mes actual
     */
    public static function getMonthlyCount(): int
    {
        return self::whereMonth('sent_at', now()->month)
            ->whereYear('sent_at', now()->year)
            ->where('status', 'sent')
            ->count();
    }

    /**
     * Obtener contador de emails del día actual
     */
    public static function getDailyCount(): int
    {
        return self::whereDate('sent_at', now()->toDateString())
            ->where('status', 'sent')
            ->count();
    }

    /**
     * Verificar si se está cerca del límite
     */
    public static function isNearLimit(int $limit = 500): bool
    {
        $monthlyCount = self::getMonthlyCount();
        return $monthlyCount >= ($limit * 0.8); // 80% del límite
    }
}
