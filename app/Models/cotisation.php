<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cotisation extends Model
{
    use HasFactory;

    protected $fillable = ['ressortissant_id', 'type_cotisation_id', 'montant', 'date_cotisation'];

    public function ressortissant()
    {
        return $this->belongsTo(Ressortissant::class);
    }

    public function typeCotisation()
    {
        return $this->belongsTo(TypeCotisation::class);
    }
}
