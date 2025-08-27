<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'zone',
        'age',
        'medical_conditions',
        'photo',
        'is_lost',
    ];

    protected function casts(): array
    {
        return [
            'age'     => 'integer',
            'is_lost' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function reward()
    {
        return $this->hasOne(Reward::class);
    }

    public function scans()
    {
        return $this->hasManyThrough(Scan::class, QrCode::class);
    }
}