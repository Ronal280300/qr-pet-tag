<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetScanPing extends Model
{
    protected $fillable = [
        'pet_id','method','lat','lng','accuracy','ip','user_agent',
        'city','region','country','address','notified',
    ];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }
}
