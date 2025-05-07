<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Lettre extends Model
{
    protected $table = 'lettres';
    protected $fillable = [
        'ressortissant_id',
        'utilisateur_id',
        'type',
        'file_path',
        'title',
        'description',
        'date_envoi',
    ];
    protected $casts = [
        'date_envoi' => 'datetime',
    ];
    protected $dates = ['date_envoi'];
    public function ressortissant()
    {
        return $this->belongsTo(Ressortissant::class);
    }

    public function utilisateur()
{
    return $this->belongsTo(Utilisateur::class);
}
    
}