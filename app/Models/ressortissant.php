<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressortissant extends Model
{
    use HasFactory;

    protected $fillable = ['titre1', 'titre2','raisonSociale', 'formeJuridique','rccm', 'capitalSociale','cotisationAnnuelle','secteurActivite', 'promoteur', 'dureeCreation', 'localiteEtRegion', 'userId'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'userId');
    }
}

