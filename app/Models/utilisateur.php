<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nom', 'email', 'password', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ressortissant()
    {
        return $this->hasOne(Ressortissant::class);
    }
}
