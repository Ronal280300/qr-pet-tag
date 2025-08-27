<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'pet_id',
        'qr_code',   // aquí guardamos la URL
        'slug',
        'image',     // o image_path si tu migración lo llama así
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function scans()
    {
        return $this->hasMany(Scan::class, 'qr_code_id');
    }
}