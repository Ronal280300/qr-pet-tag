<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PetPhoto extends Model
{
    protected $fillable = [
        'pet_id', 'path', 'is_primary', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // URL pública para usar en las vistas (versión medium por defecto)
    public function getUrlAttribute(): string
    {
        return $this->getMediumUrlAttribute();
    }

    // URL versión thumbnail (150x150) - Para listados
    public function getThumbUrlAttribute(): string
    {
        $thumbPath = $this->getVersionPath('thumb');

        if (Storage::disk('public')->exists($thumbPath)) {
            return Storage::disk('public')->url($thumbPath);
        }

        // Fallback a la imagen original si no existe la versión optimizada
        return Storage::disk('public')->url($this->path);
    }

    // URL versión medium (600x600) - Para perfiles
    public function getMediumUrlAttribute(): string
    {
        $mediumPath = $this->getVersionPath('medium');

        if (Storage::disk('public')->exists($mediumPath)) {
            return Storage::disk('public')->url($mediumPath);
        }

        return Storage::disk('public')->url($this->path);
    }

    // URL versión large (1200x1200) - Para zoom/detalle
    public function getLargeUrlAttribute(): string
    {
        $largePath = $this->getVersionPath('large');

        if (Storage::disk('public')->exists($largePath)) {
            return Storage::disk('public')->url($largePath);
        }

        return Storage::disk('public')->url($this->path);
    }

    // Helper privado para obtener la ruta de una versión
    private function getVersionPath(string $size): string
    {
        $basename = basename($this->path);
        $folder = dirname($this->path);

        // Remover prefijos existentes
        $basename = preg_replace('/^(thumb|medium|large)_/', '', $basename);

        return "{$folder}/{$size}_{$basename}";
    }
}
