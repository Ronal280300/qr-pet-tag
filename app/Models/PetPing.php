<?php
// app/Models/PetPing.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetPing extends Model
{
    protected $fillable = [
        'pet_id','qr_code_id','source','lat','lng','accuracy',
        'city','region','country','ip','user_agent',
    ];

    public function pet()    { return $this->belongsTo(Pet::class); }
    public function qrCode() { return $this->belongsTo(QrCode::class); }
}
