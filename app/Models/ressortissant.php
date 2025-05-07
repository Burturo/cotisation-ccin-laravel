<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressortissant extends Model
{
    use HasFactory;
    protected $table = 'ressortissants';
    protected $fillable = ['titre1', 'titre2','raisonSociale', 'formeJuridique','rccm', 'capitalSociale','cotisationAnnuelle','secteurActivite', 'promoteur', 'dureeCreation', 'localiteEtRegion', 'userId'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'userId');
    }
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function lettres()
{
    return $this->hasMany(Lettre::class);
}

public function contributionStatus()
{
    $latestPayment = $this->paiements()->latest('date_paiement')->first();
    $currentYear = now()->year;

    if (!$latestPayment) {
        return ['status' => 'pending', 'message' => 'Aucun paiement enregistré.'];
    }

    $paymentYear = $latestPayment->date_paiement->year;
    if ($paymentYear < $currentYear) {
        return ['status' => 'overdue', 'message' => 'Cotisation en retard pour ' . $currentYear . '.'];
    }

    return ['status' => 'up-to-date', 'message' => 'Cotisation à jour pour ' . $currentYear . '.'];
}

}

