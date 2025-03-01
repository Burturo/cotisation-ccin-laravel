<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ressortissant extends Model
{
    use HasFactory;

    protected $fillable = ['titre1', 'titre2','RaisonSocial', 'FormJuri','RCCM', 'CapitalSocial','SecteurActi', 'Promoteur', 'Sexe', 'DureeCrea','telephone', 'adresse', 'utilisateur_id'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}

