<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetFbPost extends Model
{
    protected $guarded = [];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
