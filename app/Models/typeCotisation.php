<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCotisation extends Model
{
    use HasFactory;

    protected $fillable = ['id','name','description','montant_fixe'];

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}
