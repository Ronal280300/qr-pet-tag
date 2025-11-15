<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetFbPost extends Model
{
    protected $fillable = [
        'pet_id',
        'status',
        'post_id',
        'message',
        'fingerprint',
        'attempts',
        'last_attempt_at',
        'error_message',
        'image_kind',
        'image_ref',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'last_attempt_at' => 'datetime',
        ];
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
