<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;
    protected $table = 'cotisations';

    protected $fillable = ['ressortissant_id', 'type_cotisation_id', 'montant','annee', 'date_cotisation','statut'];

    public function ressortissant()
    {
        return $this->belongsTo(Ressortissant::class);
    }

    public function typeCotisation()
{
    return $this->belongsTo(TypeCotisation::class, 'type_cotisation_id');
}
}
