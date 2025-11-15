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

    // URL pública para usar en las vistas
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
