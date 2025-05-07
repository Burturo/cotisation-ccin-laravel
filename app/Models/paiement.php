<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $table = 'paiements';

    protected $casts = [
        'date_paiement' => 'date',
    ];

    protected $fillable = ['ressortissant_id','cotisation_id','type_cotisation_id', 'montant','methode_paiement', 'date_paiement', 'reference'];

    public function cotisation()
    {
        return $this->belongsTo(Cotisation::class);
    }
    public function ressortissant()
    {
        return $this->belongsTo(Ressortissant::class, 'ressortissant_id');
    }
    public function typeCotisation()
    {
        return $this->belongsTo(TypeCotisation::class, 'type_cotisation_id');
    }
}
