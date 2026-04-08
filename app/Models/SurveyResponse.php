<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'has_pets',
        'pet_type',
        'main_concern',
        'lost_pet_before',
        'would_buy',
        'price_range',
        'likelihood_score',
        'email',
        'ip_address',
        'user_agent',
        'referrer',
        'source',
    ];

    protected $casts = [
        'likelihood_score' => 'integer',
    ];

    /**
     * Scope: respuestas de los últimos N días
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: con intención de compra positiva
     */
    public function scopeWouldBuy($query)
    {
        return $query->whereIn('would_buy', ['definitivamente_si', 'probablemente_si']);
    }

    /**
     * Obtener distribución de campo como porcentaje
     */
    public static function distribution(string $field): array
    {
        $total = static::whereNotNull($field)->count();
        if ($total === 0) return [];

        return static::selectRaw("{$field} as label, COUNT(*) as total")
            ->whereNotNull($field)
            ->groupBy($field)
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) use ($total) {
                return [
                    'label' => $row->label,
                    'count' => $row->total,
                    'percentage' => round(($row->total / $total) * 100, 1),
                ];
            })
            ->toArray();
    }
}
