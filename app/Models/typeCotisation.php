<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeCotisation extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'montant_fixe'];

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}
