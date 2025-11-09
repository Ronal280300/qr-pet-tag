<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email_template_id',
        'status',
        'filter_config',
        'filter_type',
        'no_scans_days',
        'payment_due_days',
        'total_recipients',
        'sent_count',
        'failed_count',
        'scheduled_at',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'filter_config' => 'array',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Relación con la plantilla de email
     */
    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    /**
     * Relación con el admin que creó la campaña
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación con los destinatarios
     */
    public function recipients()
    {
        return $this->hasMany(CampaignRecipient::class);
    }

    /**
     * Scope por estado
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para campañas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope para campañas activas (enviando)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['sending', 'scheduled']);
    }

    /**
     * Calcular porcentaje de éxito
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_recipients === 0) {
            return 0;
        }

        return ($this->sent_count / $this->total_recipients) * 100;
    }

    /**
     * Obtener destinatarios filtrados según configuración
     */
    public function getFilteredRecipients()
    {
        $query = User::query()->where('is_admin', false);

        switch ($this->filter_type) {
            case 'all':
                // Todos los clientes
                break;

            case 'no_scans':
                // Clientes sin lecturas en X días
                $days = $this->no_scans_days ?? 30;
                $query->whereDoesntHave('pets.scans', function($q) use ($days) {
                    $q->where('scanned_at', '>=', now()->subDays($days));
                });
                break;

            case 'payment_due':
                // Clientes con pago próximo a vencer
                $days = $this->payment_due_days ?? 5;
                $query->whereHas('orders', function($q) use ($days) {
                    $q->where('status', 'completed')
                      ->whereHas('plan', function($p) {
                          $p->where('type', 'subscription');
                      })
                      ->whereRaw('DATE_ADD(completed_at, INTERVAL (SELECT duration_months FROM plans WHERE plans.id = orders.plan_id) MONTH) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
                });
                break;

            case 'custom':
                // Filtros personalizados desde filter_config
                if ($this->filter_config) {
                    // Aquí se pueden agregar más filtros personalizados
                    if (isset($this->filter_config['has_pets'])) {
                        if ($this->filter_config['has_pets']) {
                            $query->has('pets');
                        } else {
                            $query->doesntHave('pets');
                        }
                    }

                    if (isset($this->filter_config['verified_email'])) {
                        if ($this->filter_config['verified_email']) {
                            $query->whereNotNull('email_verified_at');
                        } else {
                            $query->whereNull('email_verified_at');
                        }
                    }
                }
                break;
        }

        return $query->get();
    }
}
