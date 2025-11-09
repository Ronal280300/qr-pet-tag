<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'description',
        'html_content',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relación con campañas que usan esta plantilla
     */
    public function campaigns()
    {
        return $this->hasMany(EmailCampaign::class);
    }

    /**
     * Scope para plantillas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope por categoría
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Reemplazar variables en el HTML
     */
    public function renderForUser(User $user, array $additionalVars = []): string
    {
        $html = $this->html_content;

        // Variables estándar disponibles en todas las plantillas
        $variables = array_merge([
            '{{name}}' => $user->name,
            '{{email}}' => $user->email,
            '{{phone}}' => $user->phone ?? 'N/A',
            '{{year}}' => now()->year,
            '{{site_name}}' => Setting::get('site_name', 'QR Pet Tag'),
            '{{site_url}}' => url('/'),
        ], $additionalVars);

        foreach ($variables as $key => $value) {
            $html = str_replace($key, $value, $html);
        }

        return $html;
    }

    /**
     * Categorías disponibles
     */
    public static function getCategories(): array
    {
        return [
            'general' => 'General',
            'payment_reminder' => 'Recordatorio de Pago',
            'update_data' => 'Actualización de Datos',
            'inactive_users' => 'Usuarios Inactivos',
            'promotional' => 'Promocional',
            'newsletter' => 'Newsletter',
        ];
    }
}
