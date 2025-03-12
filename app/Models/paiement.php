<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['cotisation_id', 'montant', 'date_paiement', 'methode_paiement'];

    public function cotisation()
    {
        return $this->belongsTo(Cotisation::class);
    }
}
