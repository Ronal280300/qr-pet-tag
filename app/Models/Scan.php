<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'ip',
        'user_agent',
        'referrer',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}