<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCotisation extends Model
{
    use HasFactory;
    protected $table = 'type_cotisations';

    protected $fillable = ['id','name','description','montant','formeJuridique'];

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}
