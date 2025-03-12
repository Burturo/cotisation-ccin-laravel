<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'utilisateurs';

    protected $fillable = ['id','username', 'password','firstname','lastname','gender','phone','address', 'role', 'image'];

    protected $hidden = ['password'];

    public function ressortissant()
    {
        return $this->hasOne(Ressortissant::class, 'userId');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isRessortissant()
    {
        return $this->role === 'ressortissant';
    }

    public function isFinancier()
    {
        return $this->role === 'financier';
    }

    public function isCaissier()
    {
        return $this->role === 'caissier';
    }

}
