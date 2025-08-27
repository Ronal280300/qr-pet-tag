<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'active',
        'amount',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}