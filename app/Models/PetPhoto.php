<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetPhoto extends Model
{
    protected $fillable = ['pet_id', 'path', 'order'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }
}
